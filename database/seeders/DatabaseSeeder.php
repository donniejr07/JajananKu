<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $expenseCategories = [
            ['Food & Dining', 'restaurant', '#e11d48'],
            ['Transportation', 'directions_car', '#4338ca'],
            ['Housing', 'home', '#b45309'],
            ['Entertainment', 'movie', '#7c3aed'],
            ['Groceries', 'shopping_bag', '#0e7490'],
            ['Utilities', 'bolt', '#ca8a04'],
        ];

        $incomeCategories = [
            ['Salary', 'work', '#006c49'],
            ['Freelance', 'laptop_mac', '#4338ca'],
            ['Investment', 'trending_up', '#0e7490'],
        ];

        foreach ($expenseCategories as [$name, $icon, $color]) {
            $categories[] = $user->categories()->create([
                'name' => $name,
                'type' => 'expense',
                'icon' => $icon,
                'color' => $color,
            ]);
        }

        foreach ($incomeCategories as [$name, $icon, $color]) {
            $categories[] = $user->categories()->create([
                'name' => $name,
                'type' => 'income',
                'icon' => $icon,
                'color' => $color,
            ]);
        }

        $expenseIds = collect($categories)->where('type', 'expense')->pluck('id');
        $incomeIds = collect($categories)->where('type', 'income')->pluck('id');

        for ($i = 0; $i < 60; $i++) {
            $isExpense = fake()->boolean(70);
            $categoryId = $isExpense ? $expenseIds->random() : $incomeIds->random();

            $user->transactions()->create([
                'category_id' => $categoryId,
                'type' => $isExpense ? 'expense' : 'income',
                'amount' => $isExpense
                    ? fake()->randomFloat(2, 5000, 500000)
                    : fake()->randomFloat(2, 500000, 10000000),
                'date' => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
                'note' => fake()->optional(0.7)->sentence(4),
            ]);
        }
    }
}
