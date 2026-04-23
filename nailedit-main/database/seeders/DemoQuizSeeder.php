<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class DemoQuizSeeder extends Seeder
{
    public function run(): void
    {
        if (Quiz::query()->exists()) {
            return;
        }

        $quiz = Quiz::query()->create([
            'title' => 'General Knowledge Demo',
            'description' => 'A sample quiz seeded for demos and recruiter walkthroughs.',
            'is_published' => true,
        ]);

        $questions = [
            [
                'prompt' => 'Which planet is known as the Red Planet?',
                'points' => 1000,
                'time_limit_seconds' => 20,
                'options' => [
                    ['text' => 'Mars', 'correct' => true],
                    ['text' => 'Venus', 'correct' => false],
                    ['text' => 'Jupiter', 'correct' => false],
                    ['text' => 'Mercury', 'correct' => false],
                ],
            ],
            [
                'prompt' => 'What does PHP originally stand for?',
                'points' => 1000,
                'time_limit_seconds' => 25,
                'options' => [
                    ['text' => 'Personal Home Page', 'correct' => true],
                    ['text' => 'Private Hypertext Processor', 'correct' => false],
                    ['text' => 'Public Hosting Protocol', 'correct' => false],
                    ['text' => 'Programmed Hyperlink Pages', 'correct' => false],
                ],
            ],
            [
                'prompt' => 'Which country is Wellington the capital of?',
                'points' => 1000,
                'time_limit_seconds' => 15,
                'options' => [
                    ['text' => 'Australia', 'correct' => false],
                    ['text' => 'New Zealand', 'correct' => true],
                    ['text' => 'Canada', 'correct' => false],
                    ['text' => 'Ireland', 'correct' => false],
                ],
            ],
        ];

        foreach ($questions as $index => $definition) {
            $question = Question::query()->create([
                'quiz_id' => $quiz->id,
                'prompt' => $definition['prompt'],
                'question_type' => 'single_choice',
                'time_limit_seconds' => $definition['time_limit_seconds'],
                'points' => $definition['points'],
                'sort_order' => $index + 1,
            ]);

            foreach ($definition['options'] as $optionIndex => $option) {
                QuestionOption::query()->create([
                    'question_id' => $question->id,
                    'option_text' => $option['text'],
                    'is_correct' => $option['correct'],
                    'sort_order' => $optionIndex + 1,
                ]);
            }
        }
    }
}
