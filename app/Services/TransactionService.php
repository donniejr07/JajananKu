<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TransactionService
{
    public function list(User $user, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $user->transactions()
            ->with('category')
            ->orderByDesc('date')
            ->orderByDesc('id');

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (! empty($filters['from'])) {
            $query->whereDate('date', '>=', $filters['from']);
        }

        if (! empty($filters['to'])) {
            $query->whereDate('date', '<=', $filters['to']);
        }

        if (! empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('note', 'like', "%{$filters['search']}%")
                    ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$filters['search']}%"));
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function create(User $user, array $data): Transaction
    {
        return $user->transactions()->create($data);
    }

    public function update(Transaction $transaction, array $data): Transaction
    {
        $transaction->update($data);

        return $transaction;
    }

    public function delete(Transaction $transaction): void
    {
        $transaction->delete();
    }

    public function recent(User $user, int $limit = 5): Collection
    {
        return $user->transactions()
            ->with('category')
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }
}
