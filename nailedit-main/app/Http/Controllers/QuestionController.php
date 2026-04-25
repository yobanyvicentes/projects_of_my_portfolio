<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Quiz $quiz): RedirectResponse
    {
        abort_unless($quiz->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'prompt' => ['required', 'string'],
            'time_limit_seconds' => ['required', 'integer', 'min:5', 'max:120'],
            'points' => ['required', 'integer', 'min:100', 'max:5000'],
            'option_a' => ['required', 'string', 'max:255'],
            'option_b' => ['required', 'string', 'max:255'],
            'option_c' => ['required', 'string', 'max:255'],
            'option_d' => ['required', 'string', 'max:255'],
            'correct_option' => ['required', 'in:a,b,c,d'],
        ]);

        $question = Question::query()->create([
            'quiz_id' => $quiz->id,
            'prompt' => $validated['prompt'],
            'question_type' => 'single_choice',
            'time_limit_seconds' => $validated['time_limit_seconds'],
            'points' => $validated['points'],
            'sort_order' => (int) $quiz->questions()->max('sort_order') + 1,
        ]);

        collect([
            'a' => $validated['option_a'],
            'b' => $validated['option_b'],
            'c' => $validated['option_c'],
            'd' => $validated['option_d'],
        ])->each(function (string $optionText, string $key) use ($question, $validated): void {
            $question->options()->create([
                'option_text' => $optionText,
                'is_correct' => $validated['correct_option'] === $key,
                'sort_order' => match ($key) {
                    'a' => 1,
                    'b' => 2,
                    'c' => 3,
                    default => 4,
                },
            ]);
        });

        return redirect()
            ->route('quizzes.show', $quiz)
            ->with('status', 'Question added successfully.');
    }
}
