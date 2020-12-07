<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $dates = ['last_login'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'confirm_code', 'activated',
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'confirm_code', 'password', 'remember_token',
    ];

    protected $casts = ['activated' => 'boolean',];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function isAdmin()
    {
        return ($this->id === 1) ? true : false;
    }
}
