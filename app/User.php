<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'username', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function reports()
    {
        return $this->hasMany('App\Report', 'user_id');
    }

    public function groups()
    {
        return $this->belongsToMany('App\Group');
    }

    public function assignGroup(Group $group)
    {
        return $this->groups()->save($group);
    }

    public function hasGroup($groupId)
    {
        return $this->groups->contains($groupId);
    }
}
