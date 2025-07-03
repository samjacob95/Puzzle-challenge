# 🧠 Async Puzzle Backend (Laravel)

This Laravel backend handles student submissions for word puzzles, grades them, and maintains a high-score leaderboard.

---

## 🏗 Features

- ✅ Random puzzle generation per student
- ✅ Letter-by-letter validation with deduction
- ✅ Offline English word validation (static dictionary)
- ✅ Real-time scoring per word
- ✅ Tracks remaining letters
- ✅ Puzzle session ending with valid unplayed words
- ✅ Global Top 10 leaderboard (unique words only)
- ✅ Clean service-based architecture
- ✅ Fully tested with Laravel test suite

---

## 🚀 Tech Stack

- PHP 8.1+
- Laravel 10
- Static dictionary for word validation
- PHPUnit for tests

---

## 🛠️ Setup Instructions

```bash
git clone https://github.com/yourusername/async-puzzle.git
cd async-puzzle

composer install
cp .env.example .env
php artisan key:generate

# Create DB and update .env with DB credentials
php artisan migrate

# English wordlist Reference
https://raw.githubusercontent.com/jeremy-rifkin/Wordlist/refs/heads/master/res/e.txt

php artisan serve

## 🧪 Run Tests

We use Laravel’s built-in test suite (`php artisan test`) with a smaller dictionary file for efficient testing.

### ✅ Test Dictionary Setup

To prevent memory issues during tests, we use a smaller wordlist (storage/app/dictionary/test_words.txt) just for PHPUnit.

1. Change the value of APP_ENV in .env as testing
