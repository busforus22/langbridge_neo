# プロジェクト　Langbridge
    チームメンバー　Y氏、H氏、Busf

## Busfの担当領域：

    ・ER図の作成
        https://dbdiagram.io/d/69022ef36735e11170537bd6
    ・Vocabulary機能実装
        Model
            app\Models\Vocabulary.php
            app\Models\Quiz.php
            app\Models\DailyStatistic.php
        Controller
            app\Http\Controllers\VocabularyController.php
            app\Http\Controllers\QuizController.php
        Service
            app\Services\VocabularyStatusService.php
        Blade
            resources\views\pages\quiz
            resources\views\pages\vocabulary
        Modal
            app\Livewire\VocabularyModal.php
            resources\views\livewire\vocabulary-modal.blade.php
            resources\js\vocabulary-modal.js
    ・ロケール設定
            app\Http\Middleware\SetLocale.php
            resources\lang\en\messages.php
            resources\lang\ja\messages.php

## ER図について
    要件定義中、ページごとの要素を考えながらER図を作成。
    ただし、中間報告会での指摘を受けて大幅に要件が削られ新しい要素（Vocabulary機能）が加わったため、大きな改修をする羽目になった。

    つまずいた部分
    ・もともと年齢をintで格納するageカラムを想定していたが、経年で年齢が変化することを失念していたことに気づいていなかった。
    　birth_dateをユーザにフォームで登録させそこから年齢を計算するロジックをbladeに組み込む実装に変更。
    ・usersテーブルのカラムが多くなりすぎる懸念から、プロフィール編集ページで編集する情報を表すカラムをprofilesテーブルに切り出した。
    　これのせいで他のメンバーがusersから参照すればいいのかprofilesから参照すればよいのか実装上で迷う場面が頻発していた。
    　アナウンスが甘かったのが悪かったのか、そもそもこの垂直分割がナンセンスだったのか判断がつかない。
    　初心者の開発ということで、シンプルにusersテーブルにすべてのカラムをまとめてもよかったのかもしれない。
    　個人的には、いちおう意味のある分割であったつもりなので間違ってなかったと信じたいが...
    ・Soft Deleteの実装有無について要件定義の時点で考えておらず、
    　usersテーブルとprofilesテーブルにあとからdeleted_atを加えることになった。
## Vocabulary機能について
    

## 修正履歴
20260317 ChatControllerコードリファクタリング、chat.bladeも修正しuserの本名が表示されてしまうミスを修正

