<?php

namespace App\Services;

use App\Models\User;
use App\Models\Vocabulary;
use App\Models\Quiz;
use Carbon\Carbon;

class VocabularyStatusService
{
    public function refreshStatusForUser(int $userId): void
    {

        //ユーザのタイムゾーンを取得
        $timezone = $this->getTimeZone($userId);

        //ユーザタイムゾーンで2週間前の0:00:00をUTCに変換→最後に正解した日付のしきい値とする
        $threshold_utc = Carbon::now($timezone)
        ->subDays(14) //14を任意の日数に変更可。（忘却までの日数）
        ->startOfDay()
        ->setTimezone('UTC');

        //最後に正解した日付が$threshold_utc以前のvocabulary_idのリストを取得
         $vocabularyIds = Quiz::selectRaw('vocabulary_id, MAX(created_at) as last_correct_at')
            ->where('user_id', $userId)
            ->where('is_correct', true)
            ->groupBy('vocabulary_id')
            ->having('last_correct_at', '<', $threshold_utc)
            ->pluck('vocabulary_id'); //後でwhereInに引数として入れるため、pluckを使い配列の形で取得

        //該当する単語がなければ処理終了
        if ($vocabularyIds->isEmpty()) {
        return;
        }

        //ユーザーの単語帳の中の、'mastered'ステータスになっているものの中から、二週間以上正解のない単語を'learning'に格下げ
        Vocabulary::where('user_id', $userId)
            ->where('status', 'mastered')
            ->whereIn('id', $vocabularyIds)
            ->update(['status' => 'learning']);
    }

    public function promoteLearningToMastered(int $userId): void
    {

        //ユーザのタイムゾーンを取得
        $timezone = $this->getTimeZone($userId);

        // ユーザーTZ基準で「7日前の開始」
        $fromUserTz = Carbon::now($timezone)
            ->subDays(7)
            ->startOfDay();

        // UTC に変換（DB用）
        $fromUtc = $fromUserTz->setTimezone('UTC');

        //ユーザの単語帳の中から'learning'ステータスの単語リストを取得
        $learningVocabularyIds = Vocabulary::where('user_id', $userId)
            ->where('status', 'learning')
            ->pluck('id');

        //該当する単語がなければ処理終了
        if ($learningVocabularyIds->isEmpty()) {
            return;
        }

        //一週間(fromUTC)以内にクイズを行った単語のidとクイズの試行回数、正解数を取得
        $stats = Quiz::selectRaw('
                vocabulary_id,
                COUNT(*) as total,
                SUM(is_correct) as correct
            ')
            //真偽値の場合trueが1、falseが0なのでSUMがtrueの数を数えたことになる
            ->where('user_id', $userId)
            ->whereIn('vocabulary_id', $learningVocabularyIds)
            ->where('created_at', '>=', $fromUtc)
            ->groupBy('vocabulary_id')
            ->get();

        //各単語の正解率を計算し、3回以上試行＋正答率8割で、learningステータスの単語がmasteredステータスに昇格
        foreach ($stats as $stat) {
            $accuracy = $stat->correct / $stat->total;

            if ($stat->total >= 3 && $accuracy >= 0.8) {
                Vocabulary::where('id', $stat->vocabulary_id)
                    ->update([
                        'status' => 'mastered',
                    ]);
            }
        }
    }

    private function getTimezone(int $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return;
        }

        return $user->timezone ?? 'UTC'; //Null合体演算子　$user->timezoneがセットされていてかつNullでない場合そのものを返す。条件を満たさないなら'UTC'を返す
    }
}