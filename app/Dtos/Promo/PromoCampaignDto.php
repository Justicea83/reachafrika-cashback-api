<?php

namespace App\Dtos\Promo;

use App\Dtos\BaseDto;
use App\Models\Promo\Campaign\PromoCampaign;
use App\Models\Promo\Campaign\PromoCampaignSchedule;
use App\Services\Promo\Campaign\IPromoCampaignService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;

class PromoCampaignDto extends BaseDto
{
    public string $media;
    public string $media_stream;
    public int $impressions;
    public string $duration;
    public ?float $lat;
    public ?float $lng;
    public ?int $min_age;
    public ?int $max_age;
    public string $callback_url;
    public string $message;
    public ?string $gender;
    public ?string $marital_status;
    public string $description;
    public float $budget;
    public string $title;
    public string $type;
    public string $start;
    public string $end;
    public string $thumbnail;
    public string $thumbnail_stream;
    public bool $blocked;
    public ?string $delete_requested_at;
    public ?array $frequency;
    public ?array $schedules;
    public string $status;
    public int $id;

    /**
     * @param PromoCampaign $model
     * @return void
     */
    public function mapFromModel($model): PromoCampaignDto
    {
        /** @var IPromoCampaignService $promoCampaignService */
        $promoCampaignService = App::make(IPromoCampaignService::class);


        return self::instance()
            ->setId($model->id)
            ->setLat($model->lat)
            ->setLng($model->lng)
            ->setImpressions($model->impressions_track)
            ->setDeleteRequestedAt(is_null($model->delete_requested_at) ? null : Carbon::parse($model->delete_requested_at)->toDateTimeString())
            ->setBlocked($model->blocked)
            ->setFrequency([
                'id' => $model->frequency->id,
                'name' => $model->frequency->name,
            ])
            ->setSchedules($model->schedules->map(fn(PromoCampaignSchedule $item) => ScheduleDto::map($item->schedule))->toArray())
            //TODO format the thumbnail
            ->setThumbnail(route('promo.campaigns.download', ['path' => $model->thumbnail]))
            ->setThumbnailStream($promoCampaignService->streamUrl($model->thumbnail, now()->addSeconds($model->duration + 50)->unix()))
            ->setMediaStream($promoCampaignService->streamUrl($model->media, now()->addSeconds($model->duration + 50)->unix()))
            ->setMedia(route('promo.campaigns.download', ['path' => $model->media]))
            ->setType($model->type)
            ->setDuration(
                $model->duration
            )
            ->setCallbackUrl($model->callback_url)
            ->setMinAge($model->min_age)
            ->setMaxAge($model->max_age)
            ->setTitle($model->title)
            ->setBudget($model->budget)
            ->setDescription($model->description)
            ->setMessage($model->message)
            ->setMaritalStatus($model->marital_status)
            ->setGender($model->gender)
            ->setStart(Carbon::parse($model->start)->toDateTimeString())
            ->setEnd(Carbon::parse($model->end)->toDateTimeString())
            ->setStatus($model->status);
    }

    private static function instance(): PromoCampaignDto
    {
        return new PromoCampaignDto();
    }

    public static function map(PromoCampaign $promoCampaign): PromoCampaignDto
    {
        $instance = self::instance();
        return $instance->mapFromModel($promoCampaign);
    }

    //<editor-fold desc="Fluent Setters">

    /**
     * @param string $media
     * @return PromoCampaignDto
     */
    public function setMedia(string $media): PromoCampaignDto
    {
        $this->media = $media;
        return $this;
    }

    /**
     * @param int $impressions
     * @return PromoCampaignDto
     */
    public function setImpressions(int $impressions): PromoCampaignDto
    {
        $this->impressions = $impressions;
        return $this;
    }

    /**
     * @param ?float $lng
     * @return PromoCampaignDto
     */
    public function setLng(?float $lng): PromoCampaignDto
    {
        $this->lng = $lng;
        return $this;
    }

    /**
     * @param float|null $lat
     * @return PromoCampaignDto
     */
    public function setLat(?float $lat): PromoCampaignDto
    {
        $this->lat = $lat;
        return $this;
    }

    /**
     * @param int|null $min_age
     * @return PromoCampaignDto
     */
    public function setMinAge(?int $min_age): PromoCampaignDto
    {
        $this->min_age = $min_age;
        return $this;
    }

    /**
     * @param int|null $max_age
     * @return PromoCampaignDto
     */
    public function setMaxAge(?int $max_age): PromoCampaignDto
    {
        $this->max_age = $max_age;
        return $this;
    }

    /**
     * @param string $callback_url
     * @return PromoCampaignDto
     */
    public function setCallbackUrl(string $callback_url): PromoCampaignDto
    {
        $this->callback_url = $callback_url;
        return $this;
    }

    /**
     * @param string $message
     * @return PromoCampaignDto
     */
    public function setMessage(string $message): PromoCampaignDto
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param string|null $gender
     * @return PromoCampaignDto
     */
    public function setGender(?string $gender): PromoCampaignDto
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @param string|null $marital_status
     * @return PromoCampaignDto
     */
    public function setMaritalStatus(?string $marital_status): PromoCampaignDto
    {
        $this->marital_status = $marital_status;
        return $this;
    }

    /**
     * @param string $description
     * @return PromoCampaignDto
     */
    public function setDescription(string $description): PromoCampaignDto
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param float $budget
     * @return PromoCampaignDto
     */
    public function setBudget(float $budget): PromoCampaignDto
    {
        $this->budget = $budget;
        return $this;
    }

    /**
     * @param string $title
     * @return PromoCampaignDto
     */
    public function setTitle(string $title): PromoCampaignDto
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $type
     * @return PromoCampaignDto
     */
    public function setType(string $type): PromoCampaignDto
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string $start
     * @return PromoCampaignDto
     */
    public function setStart(string $start): PromoCampaignDto
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @param string $end
     * @return PromoCampaignDto
     */
    public function setEnd(string $end): PromoCampaignDto
    {
        $this->end = $end;
        return $this;
    }

    /**
     * @param string $thumbnail
     * @return PromoCampaignDto
     */
    public function setThumbnail(string $thumbnail): PromoCampaignDto
    {
        $this->thumbnail = $thumbnail;
        return $this;
    }

    public function setDeleteRequestedAt(?string $delete_requested_at): PromoCampaignDto
    {
        $this->delete_requested_at = $delete_requested_at;
        return $this;
    }

    /**
     * @param bool $blocked
     * @return PromoCampaignDto
     */
    public function setBlocked(bool $blocked): PromoCampaignDto
    {
        $this->blocked = $blocked;
        return $this;
    }

    /**
     * @param array|null $frequency
     * @return PromoCampaignDto
     */
    public function setFrequency(?array $frequency): PromoCampaignDto
    {
        $this->frequency = $frequency;
        return $this;
    }

    /**
     * @param string $status
     * @return PromoCampaignDto
     */
    public function setStatus(string $status): PromoCampaignDto
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param int $id
     * @return PromoCampaignDto
     */
    public function setId(int $id): PromoCampaignDto
    {
        $this->id = $id;
        return $this;
    }

    public function setSchedules(?array $schedules): PromoCampaignDto
    {
        $this->schedules = $schedules;
        return $this;
    }

    /**
     * @param string $media_stream
     * @return PromoCampaignDto
     */
    public function setMediaStream(string $media_stream): PromoCampaignDto
    {
        $this->media_stream = $media_stream;
        return $this;
    }

    /**
     * @param string $thumbnail_stream
     * @return PromoCampaignDto
     */
    public function setThumbnailStream(string $thumbnail_stream): PromoCampaignDto
    {
        $this->thumbnail_stream = $thumbnail_stream;
        return $this;
    }

    //</editor-fold>

    /**
     * @param string $duration
     * @return PromoCampaignDto
     */
    public function setDuration(string $duration): PromoCampaignDto
    {
        if ($duration >= 60) {
            $minutes = floor($duration / 60);
            $seconds = $duration - (60 * $minutes);
            if ($seconds < 10) {
                $seconds = '0' . $seconds;
            }
            $this->duration = $minutes . ':' . $seconds;
        } else {
            $this->duration = '0:' . $duration;
        }
        return $this;
    }
}
