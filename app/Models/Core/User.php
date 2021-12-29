<?php

namespace App\Models\Core;


/**
 * @property mixed $firstname
 * @property mixed $lastname
 * @property mixed $email
 * @property mixed $id
 * @property mixed $avatar
 * @property mixed $image
 */
class User extends BaseCoreModel
{
    protected $appends = ['image'];

    public function getImageAttribute(): ?string
    {
        if ($this->avatar)
            return config('core.app.api.url') . '/avatars/medium/' . $this->avatar;
        return null;
    }
}
