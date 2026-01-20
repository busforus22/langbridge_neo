@extends('layouts.auth')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-sm rounded-4 border-0 p-4" style="width: 100%; max-width: 750px; background-color: #fff;">

        <!-- Title -->
        <div class="text-center mb-4">
            <h2 class="mt-2 fw-semibold">Let's get to know you!</h2>
        </div>
        <div class="register-input-area">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- fullname -->
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                    placeholder="name" value="{{ old('name') }}" required autofocus>
                @error('name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- email -->
            <div class="mb-3">
                <label for="email" class="form-label">E-mail Address</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                    placeholder="email" value="{{ old('email') }}" required>
                @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- PW -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" placeholder="password" required>
                @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- PW confirm -->
            <div class="mb-3">
                <label for="password-confirm" class="form-label">Confirm Password</label>
                <input id="password-confirm" type="password" placeholder="password" class="form-control" name="password_confirmation" required>
            </div>

            <!-- ROle -->
            <div class="mb-3">
                <label class="form-label">Role in the app</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="role" id="role1" value="learner_jp_teacher_en"
                        checked>
                    <label class="form-check-label ms-2 mt-1" for="role1">
                        Japanese learner & English teacher
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="role" id="role2" value="learner_en_teacher_jp">
                    <label class="form-check-label ms-2 mt-1" for="role2">
                        English learner & Japanese teacher
                    </label>
                </div>
            </div>

            <!-- Age -->
            <div class="mb-3">
                <label for="birthday" class="form-label">Date of Birth</label>
                <input id="birthday" type="date" class="form-control @error('birthday') is-invalid @enderror"
                    name="birthday" placeholder="yyyy-mm-dd" value="{{ old('birthday') }}" required>
                @error('birthday')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>


            <!-- Country -->
            <div class="mb-3">
                <label for="country" class="form-label">Country</label>
                <input id="country" type="text" class="form-control" name="country" placeholder="country"
                    value="{{ old('country') }}">
            </div>

            <!-- Region -->
            <div class="mb-3">
                <label for="region" class="form-label">Region</label>
                <input id="region" type="text" class="form-control" name="region" placeholder="region"
                    value="{{ old('region') }}">
            </div>

            <!-- Kiyaku -->
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                <label class="form-check-label ms-2 mt-1" for="terms">
                    I agree to the
                    <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms of Service</a>
                    and
                    <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy Policy</a>
                </label>
            </div>

            <!--TimeZone(hidden)-->
            <input type="hidden" name="timezone" id="timezone" value="DEBUG">
            <!--後で消す-->

            <!-- Register button -->
            <button type="submit" class="btn login-btn w-100">Register</button>
        </form>
         </div>
    </div>
</div>

{{-- modal windows for terms of service --}}
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xxl modal-dialog-scrollable custom-modal-width">

        <div class="modal-content mx-auto" style="max-width: 70%; width: 70%;">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="container py-4">

                    <h1 class="mb-4" style="color:#1f2937;">Terms of Use</h1>

                    <!-- 英語版 -->
                    <div class="mb-5">
                        <h2 class="h5 fw-bold">Terms of Use (English)</h2>
                        <pre style="white-space: pre-wrap; font-family: inherit;">

Terms of Use (LangBridge – For Learning Purposes)

These Terms of Use (hereinafter referred to as “the Terms”) set forth the conditions for using this application (“the Service”).
All users (“Users”) are required to agree to these Terms before using the Service.

<strong class="h6">Article 1 (Purpose)</strong>
This application is designed to connect English learners of Japanese and Japanese learners of English for one-on-one language exchange through chat, and to provide a vocabulary notebook and flashcard feature to support individual study.

Users may only use the Service for the purpose of language exchange and mutual learning.

<strong class="h6">Article 2 (Prohibited Acts)</strong>

Users shall not engage in the following activities while using the Service.

1. Acts contrary to the purpose of language exchange

Conversations, posts, or streams unrelated to language learning
Using the Service for dating or romantic purposes
Soliciting business, investments, or multi-level marketing

2. Acts that make others uncomfortable

Harassment, bullying, or discriminatory remarks
Insults, threats, or defamation of other users
Sending or posting inappropriate images, videos, or links

3. Advertising, solicitation, or spam

Directing users to external websites or social media
Promoting or advertising products, services, or investments
Repeated posting or sending of the same content

<strong class="h6">Article 3 (Report Function)</strong>

Users may report inappropriate behavior to the administrator through the in-app “Report Form.”

Reports can be made from the following screens:

Chat
User profile

When submitting a report, users can select the reason for reporting from a list such as the following:

🔹 Chat
Off-topic conversation unrelated to language learning
Offensive or inappropriate messages
Solicitation or spam messages
Sending inappropriate images or links

🔹 User Profile
Inappropriate or false information
External links or social media promotion
Self-promotion or solicitation
Offensive or inappropriate language

<strong class="h6">Article 4 (Administrative Actions)</strong>

The administrator may take appropriate measures based on reports, including:

Issuing warnings to users
Removing reported message
Temporarily or permanently suspending the account

Note: This Service is for educational purposes only. Administrative actions are simulated and do not carry legal effect.

<strong class="h6">Article 5 (Disclaimer)</strong>

This Service is provided solely for educational and training purposes in software development.
It does not mediate actual disputes, manage personal data, or perform real-world moderation.

<strong class="h6">Article 6 (Revisions)</strong>

The Terms may be updated as part of the team’s development and learning process.
All revisions will be shared within the development environment.

📘 Developer’s Note

This document is a sample Terms of Use for implementing a “Report” feature and managing user behavior within a learning project.
For real-world deployment, legal review by a professional is required.

        </pre>
                    </div>

                    <!-- 日本語版 -->
                    <div>
                        <h2 class="h5 fw-bold">LangBridge 利用規約（日本語）</h2>
                        <pre style="white-space: pre-wrap; font-family: inherit;">

LangBridge 利用規約（学習用）

本利用規約（以下「本規約」といいます。）は、本アプリケーション（以下「本サービス」といいます。）の利用条件を定めるものです。
本サービスを利用するすべてのユーザー（以下「ユーザー」といいます。）は、本規約に同意した上でご利用ください。

<strong class="">第1条（目的）</strong>

本サービスは、日本語を学びたい英語話者と、英語を学びたい日本語話者が
1対1のチャットを通して互いの言語を学習するためのアプリであり、
また、ユーザーが個人的な学習を支援するための単語帳機能（記録・フラッシュカード）を提供します。

ユーザーは、言語交換および相互学習の目的の範囲内でのみ本サービスを利用することができます。

<strong class="h6">第2条（禁止行為）</strong>

ユーザーは、本サービスの利用にあたり、以下の行為を行ってはなりません。

1. 言語交換の目的に反する行為

言語学習と無関係な話題・投稿・配信の実施
出会い目的や交際目的での利用
商材・投資・マルチビジネス等への勧誘行為

2. 他者を不快にさせる行為

暴言、差別的発言、いやがらせ、嫌がらせ目的での接触
侮辱、脅迫、誹謗中傷に該当する言動
不適切な画像・動画・リンクの送信

3. 宣伝・勧誘・スパム行為

他SNS・外部サービスへの誘導
自身または第三者の商品・サービスの宣伝行為
同内容のメッセージを繰り返す行為

<strong class="h6">第3条（報告機能）</strong>

ユーザーは、他ユーザーによる不適切行為を発見した場合、
アプリ内の「報告フォーム」から管理者へ報告することができます。

報告は、以下の各画面から行えます：

チャット画面
ユーザープロフィール

報告フォームでは、不適切行為の種類を以下のようなリストから選択できます：

🔹チャット画面
言語交換と関係のない内容
不快・攻撃的な発言
勧誘・宣伝・スパム
不適切な画像やリンクの送信

🔹ユーザープロフィール
不適切・虚偽の情報
外部リンクやSNS誘導
勧誘目的の自己紹介
攻撃的・不快な表現

<strong class="h6">第4条（対応）</strong>

管理者は、報告内容を確認のうえ、以下の対応を取ることがあります。

当該ユーザーへの注意・警告
チャットメッセージの削除
一時的または恒久的な利用停止

※本サービスは学習目的で運用されており、実際の対応措置はシミュレーションを目的とします。

<strong class="h6">第5条（免責事項）</strong>

本サービスは学習目的で提供されるものであり、
実際の商用運用や個人情報管理、ユーザー間トラブルの仲裁等を目的とするものではありません。

<strong class="h6">第6条（改定）</strong>

本規約は、学習内容や機能拡張に応じて随時改定されることがあります。
改定後の内容は、チーム開発環境内で共有・確認するものとします。

📘 制作者注

本規約はプログラミング学習における「報告機能」「利用規約表示」「禁止行為管理」の実装練習を目的としたサンプル文です。
実運用サービスで利用する場合は、法務専門家による監修が必要です。

        </pre>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

{{-- modal window for privacy policy --}}
<div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xxl modal-dialog-scrollable custom-modal-width">

        {{-- <div class="modal-content"> --}}
            <div class="modal-content mx-auto" style="max-width: 70%; width: 70%;">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacyModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container py-4">
                        <h1 class="mb-4" style="color:#1f2937;">Privacy Policy</h1>

                        <!-- English Version -->
                        <div class="mb-5">
                            <h2 class="h5 fw-bold">Privacy Policy (English)</h2>
                            <pre style="white-space: pre-wrap; font-family: inherit;">

Privacy Policy (LangBridge – For Learning Purposes)

This Privacy Policy explains how this application (“the Service”) handles user information.
By using the Service, all users (“Users”) agree to the handling of information described below.

<strong class="h6">Article 1 (Purpose of Information Use)</strong>

This application is designed for language exchange between English learners of Japanese and Japanese learners of English.
The Service provides chat, vocabulary notebook, and flashcard features for learning purposes.

User information is used solely for:

- Displaying user profiles
- Enabling chat communication
- Managing vocabulary and quiz features
- Improving the learning experience within the Service

<strong class="h6">Article 2 (Information Collected)</strong>

The Service may collect the following information:

- User-provided profile information (name, language role, country, region, etc.)
- Messages exchanged in chat
- Vocabulary and quiz records
- Technical information such as browser type or access logs

This information is used only within the learning environment and is not shared externally.

<strong class="h6">Article 3 (Prohibited Use of Information)</strong>

Users must not use information obtained through the Service for:

- Harassment, discrimination, or harmful behavior
- Advertising, solicitation, or spam
- Any purpose unrelated to language learning

<strong class="h6">Article 4 (Data Handling and Storage)</strong>

All data is stored within the development environment for educational purposes.
The Service does not provide commercial-level data protection or encryption.
Data may be modified or deleted as part of the learning process.

<strong class="h6">Article 5 (Disclaimer)</strong>

This Service is for educational and training purposes in software development.
It does not manage personal data in a commercial or legal sense.
Users should avoid sharing sensitive personal information.

<strong class="h6">Article 6 (Revisions)</strong>

This Privacy Policy may be updated as part of the development and learning process.
All revisions will be shared within the development environment.

📘 Developer’s Note

This document is a sample Privacy Policy created for learning purposes.
For real-world deployment, legal review by a professional is required.

        </pre>
                        </div>

                        <!-- Japanese Version -->
                        <div>
                            <h2 class="h5 fw-bold">LangBridge プライバシーポリシー（日本語）</h2>
                            <pre style="white-space: pre-wrap; font-family: inherit;">

LangBridge プライバシーポリシー（学習用）

本プライバシーポリシー（以下「本ポリシー」といいます。）は、本アプリケーション（以下「本サービス」といいます。）におけるユーザー情報の取扱いについて定めるものです。
本サービスを利用するすべてのユーザー（以下「ユーザー」といいます。）は、本ポリシーに同意した上でご利用ください。

<strong class="h6">第1条（情報利用の目的）</strong>

本サービスは、日本語を学ぶ英語話者と、英語を学ぶ日本語話者が言語交換を行うための学習アプリです。
チャット機能、単語帳機能、フラッシュカード機能などを提供します。

ユーザー情報は以下の目的でのみ利用されます：

- プロフィール表示
- チャットでのコミュニケーション
- 単語帳・クイズ機能の管理
- 学習体験の向上

<strong class="h6">第2条（収集する情報）</strong>

本サービスは、以下の情報を収集する場合があります：

- ユーザーが入力したプロフィール情報（名前、学習ロール、国、地域など）
- チャットで送受信されるメッセージ
- 単語帳やクイズの記録
- ブラウザ情報やアクセスログなどの技術情報

これらの情報は学習環境内でのみ利用され、外部に共有されることはありません。

<strong class="h6">第3条（情報の不正利用の禁止）</strong>

ユーザーは、本サービスを通じて得た情報を以下の目的で利用してはなりません：

- いやがらせ、差別、攻撃的行為
- 宣伝、勧誘、スパム行為
- 言語学習と無関係な目的

<strong class="h6">第4条（データの管理）</strong>

本サービスのデータは学習目的で開発環境内に保存されます。
商用レベルのデータ保護や暗号化は提供されません。
学習過程においてデータが変更・削除される場合があります。

<strong class="h6">第5条（免責事項）</strong>

本サービスはソフトウェア開発学習のためのものであり、
商用サービスとしての個人情報管理を行うものではありません。
ユーザーは、機密性の高い個人情報を入力しないよう注意してください。

<strong class="h6">第6条（改定）</strong>

本ポリシーは、学習内容や機能拡張に応じて随時改定されることがあります。
改定内容は開発環境内で共有されます。

📘 制作者注

本プライバシーポリシーは、プログラミング学習におけるサンプル文書です。
実運用サービスで利用する場合は、法務専門家による監修が必要です。

        </pre>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection