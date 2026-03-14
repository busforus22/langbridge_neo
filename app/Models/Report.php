<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ReportViolationReason;
use App\Models\User;


class Report extends Model
{
    protected $fillable = [
        'reporter_id',
        'violation_reason_id',
        'detail',
        'file',
        'reported_content_id',
        'reported_content_type',
        'action_status',
        'created_at',
        'updated_at',
    ];

    //Userと一対多の関係　User has many Reports
    public function reporter(){
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /*
    ポリモーフィックリレーション。こちらが多になる一対多のリレーションを複数のテーブルに対して持つ
    */
    public function reportedContent(){
        return $this->morphTo()->withTrashed();
    }

    //action_statusのレベルの段階をここで定義
    public function nextActionLabel()
    {
        $map = [
            'pending'    => 'Warn',
            'warn'      => 'Suspend',
            'suspend'   => 'SoftDelete',
            'user_deleted'=> 'Restore',
            'restore'    => 'Pending',
        ];
    
        return $map[$this->action_status] ?? 'Pending';
    }

    //Messageと一対多の関係　Message has many Reports これ違うかも。仕様上一対一にしかならないし、morphToをつかったほうがよいのでは
    public function message()
    {
        return $this->belongsTo(Message::class, 'reported_content_id');
    }

}
