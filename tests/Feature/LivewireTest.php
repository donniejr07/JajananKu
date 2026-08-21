<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Livewire\TransactionForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LivewireTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_open_modal_and_add_income(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => 'Salary',
            'type' => 'income',
            'icon' => 'work',
            'color' => '#006c49',
        ]);

        Livewire::actingAs($user)
            ->test(TransactionForm::class)
            ->dispatch('open-transaction-form', type: 'income')
            ->assertSet('type', 'income')
            ->assertSet('show', true)
            ->set('amount', '5000000')
            ->set('categoryId', $category->id)
            ->set('date', '2026-08-21')
            ->set('note', 'Gaji bulanan')
            ->call('save')
            ->assertSet('show', false);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'type' => 'income',
            'amount' => 5000000.00,
            'note' => 'Gaji bulanan',
        ]);
    }
}
