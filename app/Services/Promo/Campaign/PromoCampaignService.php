<?php

namespace App\Services\Promo\Campaign;

use App\Dtos\Promo\PromoCampaignDto;
use App\Http\Requests\Promo\CreatePromoCampaignRequest;
use App\Models\Promo\Campaign\PromoCampaign;
use App\Models\User;
use App\Utils\General\MiscUtils;
use App\Utils\Promo\PromoCampaignUtils;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use InvalidArgumentException;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class PromoCampaignService implements IPromoCampaignService
{

    public function createCampaign(CreatePromoCampaignRequest $request): PromoCampaignDto
    {
        /** @var User $user */
        $user = $request->user();

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
        if($request->has('professions') && !empty($request['professions'])){
            foreach ($request['professions'] as $professionId){
                $campaign->professions()->firstOrCreate([
                    'profession_id' => $professionId
                ]);
            }
        }

        //add interests if any
        if($request->has('interests') && !empty($request['interests'])){
            foreach ($request['interests'] as $interestId){
                $campaign->interests()->firstOrCreate([
                    'interest_id' => $interestId
                ]);
            }
        }

        //add schedules if any
        if($request->has('schedules') && !empty($request['schedules'])){
            foreach ($request['schedules'] as $scheduleId){
                $campaign->schedules()->firstOrCreate([
                    'schedule_id' => $scheduleId
                ]);
            }
        }

        return new PromoCampaignDto();
    }

    public function getCampaigns(User $user): LengthAwarePaginator
    {
        // TODO: Implement getCampaigns() method.
    }

    public function deleteCampaign(User $user, int $id)
    {
        // TODO: Implement deleteCampaign() method.
    }
}
