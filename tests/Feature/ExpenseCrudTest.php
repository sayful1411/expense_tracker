<?php

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;

test('expense list shows only the users own expenses', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $expense = Expense::factory()->for($user)->create(['title' => 'My Lunch']);
    $otherExpense = Expense::factory()->for($otherUser)->create(['title' => 'Their Lunch']);

    $this->actingAs($user);

    $response = $this->get(route('expenses.index'));

    $response->assertOk()
        ->assertSee('My Lunch')
        ->assertDontSee('Their Lunch');
});

test('create page lists only the users own categories', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    Category::factory()->for($otherUser)->create(['name' => 'Foreign Category']);

    $this->actingAs($user);

    $response = $this->get(route('expenses.create'));

    $response->assertOk()
        ->assertSee($user->categories->first()->name)
        ->assertDontSee('Foreign Category');
});

test('user can create an expense', function () {
    $user = User::factory()->create();
    $category = $user->categories()->first();

    $this->actingAs($user);

    $response = $this->post(route('expenses.store'), [
        'category_id' => $category->id,
        'title' => 'Groceries',
        'amount' => '40',
        'date' => '2026-09-05',
    ]);

    $response->assertRedirect(route('expenses.index'))
        ->assertSessionHas('status');

    $this->assertDatabaseHas('expenses', [
        'user_id' => $user->id,
        'category_id' => $category->id,
        'title' => 'Groceries',
        'amount' => 4000,
    ]);
});

test('expense cannot be created with another users category', function () {
    $user = User::factory()->create();
    $foreignCategory = Category::factory()->for(User::factory()->create())->create();

    $this->actingAs($user);

    $response = $this->post(route('expenses.store'), [
        'category_id' => $foreignCategory->id,
        'title' => 'Sneaky expense',
        'amount' => '100',
        'date' => '2026-09-05',
    ]);

    $response->assertSessionHasErrors('category_id');
    $this->assertDatabaseMissing('expenses', ['title' => 'Sneaky expense']);
});

test('expense creation requires title, amount, category and date', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->post(route('expenses.store'), []);

    $response->assertSessionHasErrors(['category_id', 'title', 'amount', 'date']);
});

test('user can view the edit page for their own expense', function () {
    $user = User::factory()->create();
    $expense = Expense::factory()->for($user)->create(['title' => 'Coffee']);

    $this->actingAs($user);

    $this->get(route('expenses.edit', $expense))
        ->assertOk()
        ->assertSee('Coffee');
});

test('user cannot edit another users expense', function () {
    $user = User::factory()->create();
    $otherExpense = Expense::factory()->for(User::factory()->create())->create();

    $this->actingAs($user);

    $this->get(route('expenses.edit', $otherExpense))->assertNotFound();
});

test('user can update their own expense', function () {
    $user = User::factory()->create();
    $category = $user->categories()->first();
    $expense = Expense::factory()->for($user)->create();

    $this->actingAs($user);

    $response = $this->put(route('expenses.update', $expense), [
        'category_id' => $category->id,
        'title' => 'Updated title',
        'amount' => '99',
        'date' => '2026-09-05',
    ]);

    $response->assertRedirect(route('expenses.index'))
        ->assertSessionHas('status');

    $this->assertDatabaseHas('expenses', [
        'id' => $expense->id,
        'title' => 'Updated title',
        'amount' => 9900,
    ]);
});

test('user cannot update another users expense', function () {
    $user = User::factory()->create();
    $otherExpense = Expense::factory()->for(User::factory()->create())->create(['title' => 'Original']);

    $this->actingAs($user);

    $this->put(route('expenses.update', $otherExpense), [
        'category_id' => $user->categories()->first()->id,
        'title' => 'Hijacked',
        'amount' => '1',
        'date' => '2026-09-05',
    ])->assertNotFound();

    $this->assertDatabaseHas('expenses', ['id' => $otherExpense->id, 'title' => 'Original']);
});

test('user can delete their own expense', function () {
    $user = User::factory()->create();
    $expense = Expense::factory()->for($user)->create();

    $this->actingAs($user);

    $response = $this->delete(route('expenses.destroy', $expense));

    $response->assertRedirect(route('expenses.index'))
        ->assertSessionHas('status');

    $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
});

test('user cannot delete another users expense', function () {
    $user = User::factory()->create();
    $otherExpense = Expense::factory()->for(User::factory()->create())->create();

    $this->actingAs($user);

    $this->delete(route('expenses.destroy', $otherExpense))->assertNotFound();

    $this->assertDatabaseHas('expenses', ['id' => $otherExpense->id]);
});
