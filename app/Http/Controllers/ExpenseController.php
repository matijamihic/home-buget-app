<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use App\Services\ExpenseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    private $userId;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->userId = auth()->id();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $expenses = Expense::where('user_id', $this->userId)->orderBy('created_at', 'desc')->get();

        return response()->json($expenses);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $expense = Expense::where('user_id', $this->userId)->findOrFail($id);

        return response()->json($expense);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateExpenseRequest $request, ExpenseService $expenseService)
    {
        $data = $request->toArray();
        $data["user_id"] = $this->userId;

        $income = $expenseService->createExpense($data);

        return response()->json($income, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, UpdateExpenseRequest $request, ExpenseService $expenseService)
    {
        $data = $request->toArray();
        $data["user_id"] = $this->userId;

        $expense = $expenseService->updateExpense($data, $id);

        return response()->json($expense, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, ExpenseService $expenseService)
    {
        $response = $expenseService->deleteExpense($id);

        return response()->json($response, 204);
    }
}
