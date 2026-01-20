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


    public function reporter(){
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reportedContent(){
        return $this->morphTo()->withTrashed();
    }

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

public function message()
{
    return $this->belongsTo(Message::class, 'reported_content_id'); 
}

}
