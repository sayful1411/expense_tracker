<?php

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;

test('summary shows current month expenses by default', function () {
    $user = User::factory()->create();
    $currentCategory = Category::factory()->for($user)->create(['name' => 'Current Month Cat']);
    $pastCategory = Category::factory()->for($user)->create(['name' => 'Past Month Cat']);

    Expense::factory()->for($user)->create(['category_id' => $currentCategory, 'date' => now()->toDateString()]);
    Expense::factory()->for($user)->create(['category_id' => $pastCategory, 'date' => now()->subMonth()->toDateString()]);

    $this->actingAs($user);

    $response = $this->get(route('expenses.summary'));

    $response->assertOk()->assertSee('Current Month Cat')->assertDontSee('Past Month Cat');
});

test('summary can be filtered by month', function () {
    $user = User::factory()->create();
    $marchCategory = Category::factory()->for($user)->create(['name' => 'March Cat']);
    $aprilCategory = Category::factory()->for($user)->create(['name' => 'April Cat']);

    Expense::factory()->for($user)->create(['category_id' => $marchCategory, 'date' => '2026-03-15']);
    Expense::factory()->for($user)->create(['category_id' => $aprilCategory, 'date' => '2026-04-15']);

    $this->actingAs($user);

    $response = $this->get(route('expenses.summary', ['month' => '2026-03']));

    $response->assertOk()->assertSee('March Cat')->assertDontSee('April Cat');
});

test('summary can be filtered by date range', function () {
    $user = User::factory()->create();
    $inCategory = Category::factory()->for($user)->create(['name' => 'In Range Cat']);
    $outCategory = Category::factory()->for($user)->create(['name' => 'Out Range Cat']);

    Expense::factory()->for($user)->create(['category_id' => $inCategory, 'date' => '2026-03-10']);
    Expense::factory()->for($user)->create(['category_id' => $outCategory, 'date' => '2026-03-20']);

    $this->actingAs($user);

    $response = $this->get(route('expenses.summary', ['from' => '2026-03-01', 'to' => '2026-03-15']));

    $response->assertOk()->assertSee('In Range Cat')->assertDontSee('Out Range Cat');
});

test('date range overrides the month filter', function () {
    $user = User::factory()->create();
    $rangeCategory = Category::factory()->for($user)->create(['name' => 'Range Cat']);
    $monthCategory = Category::factory()->for($user)->create(['name' => 'Month Cat']);

    Expense::factory()->for($user)->create(['category_id' => $rangeCategory, 'date' => '2026-02-10']);
    Expense::factory()->for($user)->create(['category_id' => $monthCategory, 'date' => '2026-03-10']);

    $this->actingAs($user);

    $response = $this->get(route('expenses.summary', ['month' => '2026-03', 'from' => '2026-02-01', 'to' => '2026-02-28']));

    $response->assertOk()->assertSee('Range Cat')->assertDontSee('Month Cat');
});

test('summary only includes the users own expenses', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $otherCategory = Category::factory()->for($otherUser)->create(['name' => 'Their Cat']);

    Expense::factory()->for($otherUser)->create(['category_id' => $otherCategory, 'date' => now()->toDateString()]);

    $this->actingAs($user);

    $response = $this->get(route('expenses.summary'));

    $response->assertOk()->assertDontSee('Their Cat');
});

test('date range validation rejects to before from', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get(route('expenses.summary', ['from' => '2026-03-15', 'to' => '2026-03-01']));

    $response->assertSessionHasErrors('to');
});
