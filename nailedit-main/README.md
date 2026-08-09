# Nailedit Live

Nailedit Live is a Laravel quiz-game application for creating quizzes, launching hosted sessions and allowing players to join with a generated PIN.

## How it works

Hosts sign in to create and manage quizzes. A host can launch a game session, share its PIN and control the progression of the session. Players can join a live game without creating an account, submit answers and accumulate points for correct responses.

## Implemented features

### Quiz authoring

- authenticated quiz management
- quiz creation and viewing
- question creation
- answer options associated with each question

### Hosted game sessions

- create a game session from a quiz
- generate a unique game PIN
- lobby/session view for the host
- start the session
- advance to the next question
- track the active question and session state

### Player experience

- join a session using a PIN
- participate without a registered account
- submit one answer per active question
- validate whether the selected option is correct
- award question points for correct answers
- maintain player scores for the session
- display game/leaderboard information through the session interface

## Technology stack

- PHP 8.1+
- Laravel 10
- Eloquent ORM
- Blade
- Laravel authentication middleware
- Vite

## Main application areas

- `routes/web.php` — quiz, session, join and play routes
- `app/Http/Controllers/QuizController.php` — quiz workflows
- `app/Http/Controllers/GameSessionController.php` — host session creation and management
- `app/Http/Controllers/SessionControlController.php` — session progression
- `app/Http/Controllers/JoinGameController.php` — PIN-based player entry
- `app/Http/Controllers/PlayerAnswerController.php` — answer validation and scoring
- `app/Services/GamePinService.php` — unique PIN generation

## Running locally

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
php artisan serve
```

Configure the database connection in `.env` before running the migrations.

For frontend development:

```bash
npm run dev
```

## Scope

The project implements a server-driven live quiz workflow. It does not depend on WebSockets for the core game logic; session state, answers and scores are handled through Laravel requests and persisted application data.
