<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Interest;
use Illuminate\Support\Str;


class Profile extends Model
{

    use HasFactory;

    
    protected $fillable = [
        'user_id',
        'nickname',
        'bio',
        'handle',
        //other fillable fields can be added here
    ];

    // App\Models\Profile.php
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'int';
    protected $attributes = [
        'hidden' => true,
        'age_hidden' => true,
        'country_hidden' => true,
        'region_hidden' => true,
    ];
    
    /**
     *  Which user this profile belongs to (one-to-one relationship)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     *  Get the interest categories of this profile (retrieved via User)
     */
    public function interests()
    {
        return $this->user ? $this->user->interests : collect();
    }
            
    protected static function boot(){
        parent::boot();
        static::creating(function ($profile) {
            
            $handle = Str::random(8);
    
            while (Profile::where('handle', $handle)->exists()) {
                $handle = Str::random(8);
            }    
            $profile->handle = $handle;
        });
    }
    
}
