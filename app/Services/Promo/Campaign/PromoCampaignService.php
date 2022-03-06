<?php

namespace App\Services\Promo\Campaign;

use App\Dtos\Promo\PromoCampaignDto;
use App\Exceptions\Merchant\AccountNotFoundException;
use App\Http\Requests\Promo\CreatePromoCampaignRequest;
use App\Http\Requests\Promo\GetTargetCountRequest;
use App\Models\Core\SiteSetting;
use App\Models\Promo\Campaign\PromoCampaign;
use App\Models\Promo\Campaign\PromoCampaignSchedule;
use App\Models\Promo\Schedule;
use App\Models\User;
use App\Utils\Finance\Merchant\Account\AccountUtils;
use App\Utils\General\FilterOptions;
use App\Utils\General\MiscUtils;
use App\Utils\Promo\PromoCampaignUtils;
use App\Utils\Promo\PromoDayUtils;
use App\Utils\Status;
use Aws\CloudFront\CloudFrontClient;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use InvalidArgumentException;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class PromoCampaignService implements IPromoCampaignService
{

    private PromoCampaign $promoCampaignModel;
    private SiteSetting $siteSettingModel;

    function __construct(PromoCampaign $promoCampaignModel, SiteSetting $siteSettingModel)
    {
        $this->promoCampaignModel = $promoCampaignModel;
        $this->siteSettingModel = $siteSettingModel;
    }

    /**
     * @throws Throwable
     */
    public function createCampaign(CreatePromoCampaignRequest $request): PromoCampaignDto
    {
        /** @var User $user */
        $user = $request->user();

        //get the profile id for a particular merchant
        $merchantProfileId = $this->getMerchantProfileDetails($user);

        /** @var SiteSetting $siteSetting */
        $siteSetting = $this->siteSettingModel->query()->where('profile_id', $merchantProfileId)->select(['id', 'per_impression', 'flyer_duration'])->first();
        if ($siteSetting == null) throw new ModelNotFoundException();

        DB::beginTransaction();

        try {
            //deduct merchant credit account balance and move it to escrow

            AccountUtils::intraAccountMoneyMovement($user->merchant, $user, AccountUtils::ACCOUNT_TYPE_CREDIT, AccountUtils::ACCOUNT_TYPE_ESCROW, $request['budget']);

            $campaign = new PromoCampaign;
            $campaign->merchant_id = $user->merchant_id;
            $campaign->currency = $user->merchant->country->currency;
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
                    $campaign->duration = $siteSetting->flyer_duration;
                    break;
                case PromoCampaignUtils::CAMPAIGN_TYPE_VIDEO:
                    $thumbnailPath = sprintf("thumbnails/%s.png", MiscUtils::getToken(60));
                    $campaign->thumbnail = $thumbnailPath;
                    $media = FFMpeg::fromDisk('campaigns')
                        ->open($request->file('media'));
                    $duration = $media->getDurationInSeconds();


                    $campaign->duration = $duration;

                    $media->getFrameFromSeconds(10)
                        ->export()
                        ->toDisk('thumbnails')
                        ->save($thumbnailPath);

                    break;
                default:
                    throw new InvalidArgumentException('media type not found');
            }

            $impressions = $request['budget'] / $siteSetting->per_impression;

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

            //return the mapped dto
            return PromoCampaignDto::map($updatedCampaign, [
                'siteSetting' => $siteSetting
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error(get_class(), ['message' => sprintf('%s --> %s', 'There was am error while attempting to create a campaign', $e->getMessage())]);
            throw $e;
        }
    }

    public function getCampaigns(User $user, FilterOptions $filterOptions): LengthAwarePaginator
    {
        //get the profile id for a particular merchant
        $merchantProfileId = $this->getMerchantProfileDetails($user);

        /** @var SiteSetting $siteSetting */
        $siteSetting = $this->siteSettingModel->query()->where('profile_id', $merchantProfileId)->select(['id', 'per_impression', 'flyer_duration'])->first();

        if ($siteSetting == null) throw new ModelNotFoundException();

        $pagedData = $this->promoCampaignModel->query()
            ->where('merchant_id', $user->merchant_id)
            ->latest()
            ->paginate($filterOptions->pageSize, ['*'], 'page', $filterOptions->page);

        $pagedData->getCollection()->transform(function (PromoCampaign $campaign) use ($siteSetting) {
            return PromoCampaignDto::map($campaign, [
                'siteSetting' => $siteSetting
            ]);
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
                return $query->whereRaw('LOWER(users.education) = ?', $education);
            })
            ->when($professions, function (Builder $query, $professions) {
                return $query->join('user_professions', 'users.id', 'user_professions.user_id')->whereIn('user_professions.profession_id', $professions);
            })
            ->when($interests, function (Builder $query, $interests) {
                return $query->join('user_interests', 'users.id', 'user_interests.user_id')->whereIn('user_interests.interest_id', $interests);
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

    private function getBuilderForMerchantPotentialBuilder(int $merchantProfile): Builder
    {
        return DB::connection('reachafrika_core')->table('roles')->where('name', 'user')
            ->join('role_user', 'role_user.role_id', 'roles.id')
            ->join('user_profiles', 'user_profiles.user_id', 'role_user.user_id')
            ->where('user_profiles.profile_id', $merchantProfile);
    }

    public function downloadBlob(string $path): StreamedResponse
    {
        return Storage::disk('s3')->response($path);
    }

    /**
     * @throws FileNotFoundException
     */
    public function streamUrl(string $path, ?int $expiry = null): string
    {
        if (is_null($expiry)) $expiry = now()->addMinutes(200)->unix();
        $cloud = new CloudFrontClient([
            'region' => 'us-east-1',
            'version' => 'latest'
        ]);

        return $cloud->getSignedUrl([
            'url' => config('cloudfront.url') . '/' . $path,
            'expires' => $expiry,
            'key_pair_id' => config('cloudfront.key_pair_id'),
            'private_key' => Storage::disk('files')->get('cloudfront.pem'),
        ]);
    }

    public function schedule()
    {
        /** @var PromoCampaign $promoCampaign */
        foreach (
            $this->promoCampaignModel->query()->whereNotNull('approved_at')
                ->whereNotIn('status', [Status::PROMO_CAMPAIGN_STATUS_EXPIRED])
                ->latest()
                ->cursor()
            as $promoCampaign
        ) {

            if ($promoCampaign->blocked || $promoCampaign->status != Status::PROMO_CAMPAIGN_STATUS_ACTIVE) {
                $promoCampaign->last_scheduled_at = null;
                $promoCampaign->save();
                continue;
            }


            /** @var PromoCampaignSchedule $promoCampaignSchedule */
            foreach ($promoCampaign->schedules()->cursor() as $promoCampaignSchedule) {

                if (!$promoCampaignSchedule->schedule->active) continue;

                switch ($promoCampaignSchedule->schedule->day->description) {
                    case PromoDayUtils::MONDAYS_TO_FRIDAYS:
                        $days = [1, 2, 3, 4, 5];
                        break;
                    case PromoDayUtils::ALL_DAYS:
                        $days = [0, 1, 2, 3, 4, 5, 6];
                        break;
                    case PromoDayUtils::SUNDAYS:
                        $days = [0];
                        break;
                    case PromoDayUtils::MONDAYS:
                        $days = [1];
                        break;
                    case PromoDayUtils::TUESDAYS:
                        $days = [2];
                        break;
                    case PromoDayUtils::WEDNESDAYS:
                        $days = [3];
                        break;
                    case PromoDayUtils::THURSDAYS:
                        $days = [4];
                        break;
                    case PromoDayUtils::FRIDAYS:
                        $days = [5];
                        break;
                    case PromoDayUtils::SATURDAYS:
                        $days = [6];
                        break;
                    default:
                        throw new InvalidArgumentException('schedule out of range');
                }

                if (in_array(now()->dayOfWeek, $days)) {
                    if ($this->scheduleTimeInRange($promoCampaignSchedule->schedule)) {
                        $promoCampaign->last_scheduled_at = now()->unix();
                        $promoCampaign->save();
                        break;
                    }
                } else {
                    $promoCampaign->last_scheduled_at = null;
                    $promoCampaign->save();
                }
            }
        }
    }

    private function scheduleTimeInRange(Schedule $schedule): bool
    {
        $start = Carbon::parse($schedule->fromTime->name_12)->unix();
        $end = Carbon::parse($schedule->toTime->name_12)->unix();
        $now = now()->unix();

        return $now >= $start && $now <= $end;
    }

    /**
     * @throws AccountNotFoundException
     */

    public function processCampaigns()
    {
        $this->promoCampaignModel->query()->whereIn('status', ['active', 'expiring'])->chunkById(
            50,
            function ($campaigns) {
                /** @var PromoCampaign $campaign */
                foreach ($campaigns as $campaign) {

                    $start = $campaign->start;
                    $end = $campaign->end;

                    if (now()->isBetween(Carbon::parse($start), Carbon::parse($end))) {
                        if ($campaign->impressions_track > 0) {
                            Log::info(get_class(), ['message' => sprintf("campaign with id: %s has been updated", $campaign->id)]);
                            $campaign->visible = true;
                        } else {
                            Log::info(get_class(), ['message' => sprintf("campaign with id: %s is out of gas", $campaign->id)]);
                            //emails may be sent here
                            $campaign->visible = false;
                        }

                    } else {
                        $campaign->visible = false;
                        $campaign->status = Status::PROMO_CAMPAIGN_STATUS_EXPIRED;

                        $merchant = $campaign->merchant;
                        $remainingAmount = $campaign->budget * $campaign->impressions_track;

                        AccountUtils::intraAccountMoneyMovement($merchant, null, AccountUtils::ACCOUNT_TYPE_ESCROW, AccountUtils::ACCOUNT_TYPE_CREDIT, $remainingAmount);

                        //update campaign
                        $campaign->impressions_track = 0;
                        //send email alerts here
                    }

                    $campaign->save();
                }
            });
    }
}
