# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

- `composer dev` — runs artisan serve, queue listener, and Vite together
- `php artisan test` or `./vendor/bin/pest` — full test suite (Pest, in-memory SQLite via phpunit.xml)
- `./vendor/bin/pest tests/Feature/ExpenseCrudTest.php` — single file; add `--filter="test name"` for one test
- `./vendor/bin/pint <file>` — format changed PHP files (also enforced by the CI lint workflow)
- `npm run build` / `npm run dev` — Vite assets (Tailwind v4; rebuild after adding new utility classes)

## Architecture

Laravel 12 + Livewire (Volt) + Flux UI + Tailwind v4, based on the Livewire starter kit. Auth, settings, and dashboard use Livewire/Volt components (`app/Livewire`); the expense domain uses classic server-rendered controllers + Blade views (`app/Http/Controllers/ExpenseController.php`, `resources/views/expenses/`).

### Multi-user data model

- **Categories are per-user.** `categories.user_id` with unique `(user_id, name)`. New users get default categories automatically via `User::booted()` → `Category::createDefaultsFor()` (list in `Category::DEFAULT_NAMES`). `DatabaseSeeder` only creates test users; their categories come from that hook.
- **Amounts are stored as integer cents** (`unsignedBigInteger`). The `Expense::amount()` accessor/mutator converts on read/write. Do not use `number_format()` in the accessor — thousands separators break `<input type="number">` prefill.
- `expenses.category_id` uses `restrictOnDelete` — deleting a category that has expenses must fail, not cascade. User FKs do cascade.
- Always scope expense/category queries to `auth()->user()`. Validation lives in `SaveExpenseRequest`, whose `category_id` rule verifies the category belongs to the current user; controller ownership checks use `abort_unless(..., 404)`.

### UI conventions

- Flux components (`flux:modal`, `flux:button`, `flux:icon.*`) — free tier only; there is no `flux:toast`, so flash feedback uses the custom `x-toast` Alpine component (`resources/views/components/toast.blade.php`), which renders `session('status')` bottom-right and is mounted once in the sidebar layout.
- Expense pages share a card style: `rounded-2xl`, layered shadow `shadow-[0_1px_2px_rgba(16,24,40,0.05),0_12px_32px_-8px_rgba(16,24,40,0.18)]`, `ring-1 ring-gray-950/5`.
- `ExpenseFactory` auto-creates a category owned by the expense's user: `Expense::factory()->for($user)->create()`.

## Git & CI

- Feature work happens on `develop`; `main` is the deploy branch (deploy workflow SSHes to the server, pulls `main`, and restarts the service after tests pass). Tests and lint workflows run on pushes/PRs to both branches.

## Development rules

- Follow Laravel conventions (Form Requests for validation, Eloquent relationships/scopes, dedicated migrations).
- Every new feature or bug fix comes with Pest tests — write them as part of the change, not as a follow-up.
- Format changed PHP files with `./vendor/bin/pint <file>` before committing.
- Run the full test suite (`php artisan test`) before finishing any change.
- Commit messages must not mention Claude or include any co-author/generated-by trailers — clean messages only.
