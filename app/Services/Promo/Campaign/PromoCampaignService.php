<?php

namespace App\Services\Promo\Campaign;

use App\Dtos\Promo\PromoCampaignDto;
use App\Http\Requests\Promo\CreatePromoCampaignRequest;
use App\Http\Requests\Promo\GetTargetCountRequest;
use App\Models\Promo\Campaign\PromoCampaign;
use App\Models\User;
use App\Utils\Finance\Merchant\Account\AccountUtils;
use App\Utils\General\FilterOptions;
use App\Utils\General\MiscUtils;
use App\Utils\Promo\PromoCampaignUtils;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use InvalidArgumentException;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Throwable;

class PromoCampaignService implements IPromoCampaignService
{

    private PromoCampaign $promoCampaignModel;

    function __construct(PromoCampaign $promoCampaignModel)
    {
        $this->promoCampaignModel = $promoCampaignModel;
    }

    /**
     * @throws Throwable
     */
    public function createCampaign(CreatePromoCampaignRequest $request): PromoCampaignDto
    {
        /** @var User $user */
        $user = $request->user();

        DB::beginTransaction();

        try {
            //deduct merchant credit account balance and move it to escrow

            AccountUtils::intraAccountMoneyMovement($user, AccountUtils::ACCOUNT_TYPE_CREDIT, AccountUtils::ACCOUNT_TYPE_ESCROW, $request['budget']);

            $campaign = new PromoCampaign;
            $campaign->merchant_id = $user->merchant_id;

            $path = $request->file('media')->store('campaigns', 'campaigns');

            $campaign->media = $path;

            switch ($request['type']) {
                case PromoCampaignUtils::CAMPAIGN_TYPE_FLYER:
                    //save the thumbnail
                    $thumbnailPath = sprintf("thumbnails/%s.%s", MiscUtils::getToken(60), $request->file('media')->getClientOriginalExtension());
                    $image = Image::make($request->file('media'))->fit(400, 400);
                    Storage::disk('thumbnails')->put($thumbnailPath, $image->stream());

                    //save the thumbnail
                    $campaign->thumbnail = $thumbnailPath;
                    break;
                case PromoCampaignUtils::CAMPAIGN_TYPE_VIDEO:
                    $thumbnailPath = sprintf("thumbnails/%s.png", MiscUtils::getToken(60));
                    $campaign->thumbnail = $thumbnailPath;
                    FFMpeg::fromDisk('campaigns')
                        ->open($request->file('media'))
                        ->getFrameFromSeconds(10)
                        ->export()
                        ->toDisk('thumbnails')
                        ->save($thumbnailPath);
                    break;
                default:
                    throw new InvalidArgumentException('media type not found');
            }

            //TODO compute the impressions from the site admin settings
            $impressions = $request['budget'] / 2;

            $campaign->start = Carbon::parse($request['start'])->unix();
            $campaign->end = Carbon::parse($request['end'])->unix();
            $campaign->type = $request['type'];
            $campaign->title = $request['title'];
            $campaign->budget = $request['budget'];
            $campaign->description = $request['description'];

            $campaign->marital_status = $request['marital_status'] ?? 'all';
            $campaign->gender = $request['gender'] ?? 'all';
            $campaign->message = $request['message'];
            $campaign->callback_url = $request['callback_url'];

            $campaign->promo_frequency_id = $request['promo_frequency_id'];
            $campaign->max_age = $request['max_age'];
            $campaign->min_age = $request['min_age'];
            $campaign->lng = $request['lng'];
            $campaign->lat = $request['lat'];

            $campaign->impressions = $impressions;
            $campaign->impressions_track = $impressions;

            $campaign->save();


            //add professions if any
            if ($request->has('professions') && !empty($request['professions'])) {
                foreach ($request['professions'] as $professionId) {
                    $campaign->professions()->firstOrCreate([
                        'profession_id' => $professionId
                    ]);
                }
            }

            //add interests if any
            if ($request->has('interests') && !empty($request['interests'])) {
                foreach ($request['interests'] as $interestId) {
                    $campaign->interests()->firstOrCreate([
                        'interest_id' => $interestId
                    ]);
                }
            }

            //add schedules if any
            if ($request->has('schedules') && !empty($request['schedules'])) {
                foreach ($request['schedules'] as $scheduleId) {
                    $campaign->schedules()->firstOrCreate([
                        'schedule_id' => $scheduleId
                    ]);
                }
            }

            /** @var PromoCampaign $updatedCampaign */
            $updatedCampaign = $this->promoCampaignModel->query()->find($campaign->id);

            //commit all the changes to the db
            DB::commit();

            return PromoCampaignDto::map($updatedCampaign);
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function getCampaigns(User $user, FilterOptions $filterOptions): LengthAwarePaginator
    {
        $pagedData = $this->promoCampaignModel->query()
            ->where('merchant_id', $user->merchant_id)
            ->latest()
            ->paginate($filterOptions->pageSize, ['*'], 'page', $filterOptions->page);

        $pagedData->getCollection()->transform(function (PromoCampaign $campaign) {
            return PromoCampaignDto::map($campaign);
        });

        return $pagedData;
    }

    public function deleteCampaign(User $user, int $id)
    {
        /** @var PromoCampaign $campaign */
        $campaign = $this->promoCampaignModel->query()
            ->where('merchant_id', $user->merchant_id)
            ->where('id', $id)
            ->first();
        if (is_null($campaign)) return;

        $campaign->blocked = true;
        $campaign->delete_requested_at = now()->unix();

        $campaign->save();
    }


    public function pauseCampaign(User $user, int $id)
    {
        /** @var PromoCampaign $campaign */
        $campaign = $this->promoCampaignModel->query()
            ->where('merchant_id', $user->merchant_id)
            ->where('id', $id)
            ->first();
        if (is_null($campaign)) return;
        $campaign->blocked = !$campaign->blocked;
        $campaign->save();
    }

    public function getCampaign(User $user, int $id): PromoCampaignDto
    {
        /** @var PromoCampaign $campaign */
        $campaign = $this->promoCampaignModel->query()
            ->where('merchant_id', $user->merchant_id)
            ->where('id', $id)
            ->first();

        if (is_null($campaign)) throw new ModelNotFoundException("we cannot find campaign with id ", $id);

        return PromoCampaignDto::map($campaign);
    }

    public function getImpressionsByBudget(User $user, float $budget): int
    {
        return floor($budget / 2);
    }

    public function getTargetCount(GetTargetCountRequest $request): int
    {
        /** @var User $user */
        $user = $request->user();

        $merchantProfile = $this->getMerchantProfileDetails($user);

        if ($merchantProfile == 0) return $merchantProfile;

        $professions = $request->professions ?? null;
        $interests = $request->interests ?? null;
        $gender = strtolower($request->gender) == 'all' || $request->gender == 'null' ? null : strtolower($request->gender);
        $maritalStatus = strtolower($request->marital_status) == 'all' || $request->marital_status == 'null' ? null : strtolower($request->marital_status);
        $religion = strtolower($request->religion) == 'all' || $request->religion == 'null' ? null : strtolower($request->religion);
        $minAge = $request->min_age == 'null' || empty($request->min_age) ? null : $request->min_age;
        $maxAge = $request->max_age == 'null' ? null : $request->max_age;
        $education = strtolower($request->education) == 'all' || $request->education == 'null' ? null : strtolower($request->education);

        return $this->getBuilderForMerchantPotentialBuilder($merchantProfile)
            ->join('users', 'user_profiles.user_id', 'users.id')
            ->when($gender, function (Builder $query, $gender) {
                return $query->where('users.gender', strtolower($gender));
            })
            ->when($religion, function (Builder $query, $religion) {
                return $query->where('users.religion', strtolower($religion));
            })
            ->when($maritalStatus, function (Builder $query, $maritalStatus) {
                return $query->where('users.marital_status', strtolower($maritalStatus));
            })
            ->when($minAge, function (Builder $query, $minAge) {
                return $query->whereRaw("TIMESTAMPDIFF(YEAR, DATE(dob), curdate()) >= ?", $minAge);
            })
            ->when($maxAge, function (Builder $query, $maxAge) {
                return $query->whereRaw("TIMESTAMPDIFF(YEAR, DATE(dob), curdate()) <= ?", $maxAge);
            })
            ->when($education, function (Builder $query, $education) {
                return $query->whereRaw('LOWER(users.education) = ?',$education);
            })
            ->when($professions, function (Builder $query, $professions) {
                return $query->join('user_professions','users.id','user_professions.user_id')->whereIn('user_professions.profession_id',$professions);
            })
            ->when($interests, function (Builder $query, $interests) {
                return $query->join('user_interests','users.id','user_interests.user_id')->whereIn('user_interests.interest_id',$interests);
            })
            ->count();
    }

    private function getMerchantProfileDetails(User $user)
    {
        $profileDetails = DB::connection('reachafrika_core')->table('countries')
            ->where('currency', $user->merchant->country->currency)
            ->join('profiles', 'profiles.avatar', 'countries.id')
            ->select('profiles.id as merchant_profile')
            ->first();

        return $profileDetails->merchant_profile ?? 0;
    }

    public function getPotentialCount(User $user): int
    {
        $merchantProfile = $this->getMerchantProfileDetails($user);

        if ($merchantProfile == 0) return $merchantProfile;

        return $this->getBuilderForMerchantPotentialBuilder($merchantProfile)
            // ->where('user_profiles.active',true)
            ->count();
    }

    public function getBuilderForMerchantPotentialBuilder(int $merchantProfile): Builder
    {
        return DB::connection('reachafrika_core')->table('roles')->where('name', 'user')
            ->join('role_user', 'role_user.role_id', 'roles.id')
            ->join('user_profiles', 'user_profiles.user_id', 'role_user.user_id')
            ->where('user_profiles.profile_id', $merchantProfile);
    }
}
