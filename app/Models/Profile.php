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
        'age_hidden',
        'country_hidden',
        'region_hidden',
        'hidden',
        //other fillable fields can be added here
    ];


    // protected static function boot()
    // {
    //     parent::boot();
    // }

    //主キーをuser_idとする（デフォルトのidカラムがないため）
    protected $primaryKey = 'user_id';
    //主キーがauto_incrementではない
    public $incrementing = false;
    //主キーのタイプがint
    protected $keyType = 'int';
    //デフォ値の指定
    protected $attributes = [
        'hidden' => true,
        'age_hidden' => true,
        'country_hidden' => true,
        'region_hidden' => true,
    ];

    //handleの自動生成
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($profile) {

            $handle = Str::random(8);

            while (Profile::where('handle', $handle)->exists()) {
                $handle = Str::random(8);
            }
            $profile->handle = '@' . $handle;
        });
    }

    //Userと一対一の関係　User has a Profile
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //$profile->interests()でUserに紐づくInterestを取得できる
    public function interests()
    {
        return $this->user ? $this->user->interests : collect();
    }

    public const LEVEL_MAP = [
        'Beginner' => 1,
        'Intermediate' => 2,
        'Advanced' => 3,
        'Native' => 4,
    ];

    public const LEVEL_MAP_REVERSE = [
        1 => 'Beginner',
        2 => 'Intermediate',
        3 => 'Advanced',
        4 => 'Native',
    ];

    public function getJPLevelTextAttribute()
    {
        return self::LEVEL_MAP_REVERSE[$this->JP_level] ?? '';
    }

    public function getENLevelTextAttribute()
    {
        return self::LEVEL_MAP_REVERSE[$this->EN_level] ?? '';
    }


}
