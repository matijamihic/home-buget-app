<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateExpenseCategoryRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use App\Models\Income;
use App\Services\ExpenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expense = Expense::where('user_id', Auth::id())
            ->get();

        return response()->json($expense);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateExpenseCategoryRequest $request, ExpenseService $expenseService)
    {
        $data = $request->toArray();

        $income = $expenseService->createExpense($data);

        return response()->json($income, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user_id = auth()->id();
        $expense = Expense::where('user_id', $user_id)->findOrFail($id);

        return response()->json($expense);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, UpdateExpenseRequest $request, ExpenseService $expenseService)
    {
        $data = $request->toArray();

        $expense = $expenseService->updateExpense($data, $id);

        return response()->json($expense, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, ExpenseService $expenseService)
    {
        $expenseService->deleteExpense($id);

        return response()->json(['message' => 'Expense deleted successfully']);
    }
}
