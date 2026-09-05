<?php

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;

test('category list shows only the users own categories', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    Category::factory()->for($otherUser)->create(['name' => 'Foreign Category']);

    $this->actingAs($user);

    $response = $this->get(route('categories.index'));

    $response->assertOk()
        ->assertSee($user->categories->first()->name)
        ->assertDontSee('Foreign Category');
});

test('user can create a category', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->post(route('categories.store'), ['name' => 'Groceries']);

    $response->assertRedirect(route('categories.index'))
        ->assertSessionHas('status');

    $this->assertDatabaseHas('categories', [
        'user_id' => $user->id,
        'name' => 'Groceries',
    ]);
});

test('category name must be unique per user', function () {
    $user = User::factory()->create();
    Category::factory()->for($user)->create(['name' => 'Groceries']);

    $this->actingAs($user);

    $response = $this->post(route('categories.store'), ['name' => 'Groceries']);

    $response->assertSessionHasErrors('name');
    $this->assertDatabaseCount('categories', $user->categories()->count());
});

test('two users can have categories with the same name', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    Category::factory()->for($otherUser)->create(['name' => 'Groceries']);

    $this->actingAs($user);

    $response = $this->post(route('categories.store'), ['name' => 'Groceries']);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('categories', [
        'user_id' => $user->id,
        'name' => 'Groceries',
    ]);
});

test('category creation requires a name', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->post(route('categories.store'), []);

    $response->assertSessionHasErrors('name');
});

test('user can edit their own category', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create(['name' => 'Old Name']);

    $this->actingAs($user);

    $this->get(route('categories.edit', $category))
        ->assertOk()
        ->assertSee('Old Name');
});

test('user cannot edit another users category', function () {
    $user = User::factory()->create();
    $otherCategory = Category::factory()->for(User::factory()->create())->create();

    $this->actingAs($user);

    $this->get(route('categories.edit', $otherCategory))->assertNotFound();
});

test('user can update their own category', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create(['name' => 'Old Name']);

    $this->actingAs($user);

    $response = $this->put(route('categories.update', $category), ['name' => 'New Name']);

    $response->assertRedirect(route('categories.index'))
        ->assertSessionHas('status');

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'New Name',
    ]);
});

test('user cannot update another users category', function () {
    $user = User::factory()->create();
    $otherCategory = Category::factory()->for(User::factory()->create())->create(['name' => 'Original']);

    $this->actingAs($user);

    $this->put(route('categories.update', $otherCategory), ['name' => 'Hijacked'])->assertNotFound();

    $this->assertDatabaseHas('categories', [
        'id' => $otherCategory->id,
        'name' => 'Original',
    ]);
});

test('category can be renamed to its own name', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create(['name' => 'Same Name']);

    $this->actingAs($user);

    $response = $this->put(route('categories.update', $category), ['name' => 'Same Name']);

    $response->assertSessionHasNoErrors();
});

test('user can delete a category without expenses', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();

    $this->actingAs($user);

    $response = $this->delete(route('categories.destroy', $category));

    $response->assertRedirect(route('categories.index'))
        ->assertSessionHas('status');

    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});

test('category with expenses cannot be deleted', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();
    Expense::factory()->for($user)->create(['category_id' => $category]);

    $this->actingAs($user);

    $response = $this->delete(route('categories.destroy', $category));

    $response->assertRedirect(route('categories.index'))
        ->assertSessionHas('error');

    $this->assertDatabaseHas('categories', ['id' => $category->id]);
});

test('user cannot delete another users category', function () {
    $user = User::factory()->create();
    $otherCategory = Category::factory()->for(User::factory()->create())->create();

    $this->actingAs($user);

    $this->delete(route('categories.destroy', $otherCategory))->assertNotFound();

    $this->assertDatabaseHas('categories', ['id' => $otherCategory->id]);
});
