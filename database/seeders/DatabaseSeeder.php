<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 必要ならユーザーをファクトリで生成
        // User::factory(10)->create();

        // 個別の Seeder を呼び出す
        $this->call([
            // InterestTableSeeder::class,
            ReportViolationReasonsSeeder::class, // ← 追加
            // UserTableSeeder::class, // 必要ならコメントアウト解除
        ]);
    }
}
