<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Interest extends Model
{
    // Allow mass assignment for 'name' attribute in tests
    protected $fillable = ['name'];

    //Userと多対多の関係　User has many Interests, Interest has many Users
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_interest');
    }
}
