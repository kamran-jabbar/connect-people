<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the tasks of a user.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'user_id', 'id');
    }

    /**
     * @return User
     */
    public function getOtherUsers()
    {
        return  User::select('name', 'latitude', 'longitude', 'location')->where('id', '<>' , auth()->user()->id)->get();
    }

    /**
     * @return User
     */
    public function getCurrentUser()
    {
        return  User::select('name', 'latitude', 'longitude', 'location')->where('id', '=' , auth()->user()->id)->get();
    }
}
