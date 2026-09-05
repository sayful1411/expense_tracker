<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    public const DEFAULT_NAMES = [
        'Food',
        'Transport',
        'Housing',
        'Utilities',
        'Health',
        'Education',
        'Entertainment',
        'Shopping',
        'Travel',
        'Others',
    ];

    protected $fillable = [
        'user_id',
        'name',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public static function createDefaultsFor(User $user): void
    {
        foreach (self::DEFAULT_NAMES as $name) {
            $category = new self;
            $category->user()->associate($user);
            $category->name = $name;
            $category->save();
        }
    }
}
