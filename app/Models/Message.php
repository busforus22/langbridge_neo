<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Report;

class Message extends Model
{

    protected $fillable = [
        'user_id',
        'to_user_id',
        'content',
        'image_path',
        'emoji',
        'is_read',
        'sent_at',
    ];

    //Userと一対多の関係　User has many Messages その中でも送り主のユーザとの関係
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //Userと一対多の関係　User has many Messages その中でも送り先のユーザとの関係
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    //Reportのポリモーフィックリレーションのうちの一つ　カラム名が間違っているのでデッドコード
    public function reports(){
        return $this->morphMany(Report::class, 'reportedContent');
    }
}
