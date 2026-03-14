<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyStatistic extends Model
{
    use HasFactory;

    protected $table = 'daily_statistics';

    protected $fillable = [
        'user_id',
        'date',
        'total_questions',
        'correct_questions',
        'accuracy',
        'attempt_number',
    ];

    //文字列で返さないようPHPの適切な型に変換
    protected $casts = [
        'date' => 'date',
        'accuracy' => 'float'
    ];

    //Userと一対多の関係　User has many DailyStatistics
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
