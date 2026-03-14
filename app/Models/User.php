<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;
use App\Models\Report;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;
   
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'target_language',
        'birthday',
        'country',
        'region',
        'is_admin',
        'suspended',
        'deleted_at',
        'updated_at',
        'timezone',
    ];

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'suspended' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    //Profileと一対一の関係
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    //Interestと多対多の関係　User has many Interests, Intesest has many Users
    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'user_interest');
    }

    //$user->ageで年齢にアクセスできるアクセサー。DBにないカラム"age"を仮想的に作っている。
    public function getAgeAttribute(){
        return Carbon::parse($this->birthday)->age;
        //$this->birthdayで取得した日付の文字列をCarbonオブジェクトに変換しageプロパティを使用
    }

    //Vocabularyと一対多の関係　User has many Vocabularies
    public function vocabularies()
    {
        return $this->hasMany(Vocabulary::class);
    }

    //DailyStatisticsと一対多の関係　User has many DailyStatistics
    public function dailyStatistics()
    {
        return $this->hasMany(DailyStatistic::class);
    }

    /*
    Userと多対多の関係（自己参照）
    pivot tableが実装されておらず、デッドコードになってる。
    かわりに、messagesテーブルにuser_idとto_user_idカラムを実装してそちらで運用している。
    */
    public function recentChats()
    {
        return $this->belongsToMany(User::class, 'chat_sessions', 'user_id', 'partner_id')
                    ->withTimestamps()
                    ->orderBy('chat_sessions.updated_at', 'desc');
    }

    /*
    ポリモーフィックリレーション。
    User has many Reportsだし、ほかのModelもhas many Reportsできる。
    どのModelとつながるかはreportedContentカラムで参照される。
    reportsテーブルのカラムと一致していないせいでリレーションが死んでいる
    */
    public function reports(){
        return $this->morphMany(Report::class, 'reportedContent');
    }

    //idを暗号化して返す
    public function getRouteKey()
    {
        return encrypt($this->getKey());
    }

    /*上記に加えてこちらも必要
    public function resolveRouteBinding($value, $field = null)
    {
        try {
          return $this->findOrFail(decrypt($value));
      } catch (\Exception $e) {
          abort(403);
      }
    }


    さらに
    ② routes/web.php を変更
    Route::get('show/{user}', [ProfileController::class, 'show'])->name('show');

    ③ ProfileController を変更
    // 変更前
    public function show($user_id) { ... }

    // 変更後
    public function show(User $user) { ... } // 自動でモデルが注入される
    */

}

