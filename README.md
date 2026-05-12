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
### 機能概要
    ユーザーが他ユーザとの会話の中で見つけた単語やフレーズを単語帳に保存できる機能。
    もちろん、自分自身でカスタムに登録したい単語やフレーズを保存することも可能。
    単語帳に保存した単語やフレーズのフラッシュカード機能もある。
    出題モードをカスタマイズできる上、クイズの正誤によりステータスが変化し
    （単語に対して新規/習得中/習得済のいずれかが学習記録に基づいて当てられる）、
    単語やフレーズの記憶定着の助けとなる。
    クイズは一日になんどでも挑戦でき、学習記録がカレンダーとしてホーム画面に表示される。
### 実装で工夫した部分
    このアプリのユーザは日本だけでなく全世界に存在することを想定しているため、
    クイズの実行履歴がユーザのタイムゾーンに合わせてカレンダー上に表示される必要があった。
    ユーザのタイムゾーンでDBに保存するか、UTCでDBに保存するか迷ったが、
    1テーブル上の整合性を重視するためUTCで保存し、ホームに表示する際にcontrollerで
    ユーザのローカルタイムゾーンに変換するロジックを組み込むこととした。
    また、単語の学習状況ステータスに関しては、
    習得中の単語を特定の基準に基づいて習得済みに格上げしたり、
    習得済になっている単語も2週間クイズで正答していない場合習得中に格下げしたりするロジックをサービスに切り出した。


## 修正履歴
20260317 ChatControllerコードリファクタリング、chat.bladeも修正しuserの本名が表示されてしまうミスを修正
20260512 HomeControllerのカレンダーのユーザーローカルタイムゾーン反映

