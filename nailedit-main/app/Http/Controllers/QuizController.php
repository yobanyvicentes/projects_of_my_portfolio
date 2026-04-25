<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $quizzes = Quiz::query()
            ->withCount('questions')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('quizzes.index', compact('quizzes'));
    }

    public function create(): View
    {
        return view('quizzes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $quiz = Quiz::query()->create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'is_published' => (bool) ($validated['is_published'] ?? false),
        ]);

        return redirect()
            ->route('quizzes.show', $quiz)
            ->with('status', 'Quiz created successfully.');
    }

    public function show(Quiz $quiz): View
    {
        abort_unless($quiz->user_id === Auth::id(), 403);

        $quiz->load(['questions.options', 'sessions']);

        return view('quizzes.show', compact('quiz'));
    }
}
