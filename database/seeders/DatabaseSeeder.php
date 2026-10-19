<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed accounts and a sample exam in MySQL.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => 'password',
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Student',
                'password' => 'password',
                'role' => 'student',
            ]
        );

        Exam::updateOrCreate(
            ['title' => 'Đề thi mẫu Laravel'],
            [
                'description' => 'Đề thi mẫu gồm câu trắc nghiệm và câu tự luận. Nội dung bài làm nằm ở MongoDB.',
                'duration_minutes' => 60,
                'created_by' => $admin->id,
            ]
        );
    }
}
