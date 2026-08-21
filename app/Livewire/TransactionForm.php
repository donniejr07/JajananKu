<?php

namespace App\Livewire;

use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class TransactionForm extends Component
{
    public bool $show = false;

    public ?int $transactionId = null;

    public string $type = 'expense';

    public ?string $amount = null;

    public ?int $categoryId = null;

    public string $date = '';

    public ?string $note = null;

    public function mount(): void
    {
        $this->date = now()->format('Y-m-d');
    }

    #[On('open-transaction-form')]
    public function open(?int $id = null, ?string $type = null): void
    {
        $this->reset();
        $this->date = now()->format('Y-m-d');
        $this->type = $type ?? 'expense';
        $this->categoryId = null;

        if ($id) {
            $transaction = Transaction::findOrFail($id);
            abort_unless($transaction->user_id === auth()->id(), 403);

            $this->transactionId = $transaction->id;
            $this->type = $transaction->type;
            $this->amount = (string) $transaction->amount;
            $this->categoryId = $transaction->category_id;
            $this->date = $transaction->date->format('Y-m-d');
            $this->note = $transaction->note;
        }

        $this->show = true;
    }

    public function close(): void
    {
        $this->show = false;
    }

    public function save(TransactionService $service): void
    {
        $data = $this->validate([
            'type' => ['required', Rule::in(['income', 'expense'])],
            'amount' => ['required', 'numeric', 'min:1'],
            'categoryId' => [
                'required',
                Rule::exists('categories', 'id')
                    ->where('user_id', auth()->id())
                    ->where('type', $this->type),
            ],
            'date' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $payload = [
            'type' => $data['type'],
            'amount' => $data['amount'],
            'category_id' => $data['categoryId'],
            'date' => $data['date'],
            'note' => $data['note'],
        ];

        if ($this->transactionId) {
            $transaction = Transaction::findOrFail($this->transactionId);
            abort_unless($transaction->user_id === auth()->id(), 403);
            $service->update($transaction, $payload);
            session()->flash('status', 'Transaksi berhasil diperbarui.');
        } else {
            $service->create(auth()->user(), $payload);
            session()->flash('status', 'Transaksi berhasil ditambahkan.');
        }

        $this->show = false;
        $this->dispatch('transaction-saved');
    }

    public function render()
    {
        $categories = auth()->user()->categories()
            ->where('type', $this->type)
            ->orderBy('name')
            ->get();

        return view('livewire.transaction-form', [
            'categories' => $categories,
        ]);
    }
}
