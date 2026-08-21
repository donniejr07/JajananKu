<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TransactionController extends Controller
{
    public function __construct(protected TransactionService $transactions)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $transactions = $this->transactions->list($request->user(), $request->only(['type', 'from', 'to', 'search']));

        return TransactionResource::collection($transactions);
    }

    public function store(StoreTransactionRequest $request): TransactionResource
    {
        $transaction = $this->transactions->create($request->user(), $request->validated());

        return new TransactionResource($transaction->load('category'));
    }

    public function show(Request $request, Transaction $transaction): TransactionResource
    {
        abort_unless($transaction->user_id === $request->user()->id, 404);

        return new TransactionResource($transaction->load('category'));
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction): TransactionResource
    {
        abort_unless($transaction->user_id === $request->user()->id, 404);

        $transaction = $this->transactions->update($transaction, $request->validated());

        return new TransactionResource($transaction->load('category'));
    }

    public function destroy(Request $request, Transaction $transaction): JsonResponse
    {
        abort_unless($transaction->user_id === $request->user()->id, 404);

        $this->transactions->delete($transaction);

        return response()->json(['message' => 'Transaction deleted.']);
    }
}
