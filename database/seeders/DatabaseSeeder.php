<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

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

        $student = User::updateOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Student',
                'password' => 'password',
                'role' => 'student',
            ]
        );

        $exam = Exam::updateOrCreate(
            ['title' => 'Đề thi mẫu Laravel'],
            [
                'description' => 'Đề thi mẫu gồm câu trắc nghiệm và câu tự luận. Nội dung bài làm nằm ở MongoDB.',
                'duration_minutes' => 60,
                'created_by' => $admin->id,
            ]
        );

        $submission = Submission::updateOrCreate(
            [
                'exam_id' => $exam->id,
                'student_id' => $student->id,
            ],
            [
                'answers' => [
                    [
                        'question' => 'Laravel dùng pattern nào?',
                        'type' => 'single_choice',
                        'value' => 'MVC',
                    ],
                    [
                        'question' => 'Vì sao bài làm nằm ở MongoDB?',
                        'type' => 'essay',
                        'value' => 'Mỗi loại câu hỏi có cấu trúc câu trả lời khác nhau.',
                    ],
                ],
                'score' => null,
                'submitted_at' => now(),
            ]
        );

        $stored = Submission::query()->find($submission->getKey());

        if ($stored === null || count($stored->answers) !== 2) {
            throw new RuntimeException('MongoDB model could not read the submission it just wrote.');
        }
    }
}
