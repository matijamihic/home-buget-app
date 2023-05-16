<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateExpenseCategoryRequest;
use App\Http\Requests\UpdateExpenseCategoryRequest;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $userId = Auth::id();

        $expenseCategories = ExpenseCategory::where('user_id', $userId)
            ->orWhereNull('user_id')
            ->get();

        return response()->json($expenseCategories, 200);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function store(CreateExpenseCategoryRequest $request)
    {
        $data = $request->toArray();
        $data['user_id'] = Auth::id();
        $data['name'] = strtolower($data['name']);

        $expenseCcategory = ExpenseCategory::where('user_id', $data['user_id'])
            ->where('name', $data['name'])
            ->first();

        if($expenseCcategory) {
            return response()->json([
                'message' => 'Expense category already exists'
            ], 400);
        }

        $expenseCategory = ExpenseCategory::create($data);

        return response()->json($expenseCategory, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id, ExpenseCategory $expenseCategory)
    {
        $user_id = auth()->id();
        $expenseCategory = ExpenseCategory::where('user_id', $user_id)
            ->orWhereNull('user_id')
            ->findOrFail($id);

        return response()->json($expenseCategory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, UpdateExpenseCategoryRequest $request)
    {
        $data = $request->toArray();

        $expenseCategory = ExpenseCategory::where('user_id', Auth::id())->findOrFail($id);
        
        $expenseCategory->update($data);

        return response()->json($expenseCategory);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, ExpenseCategory $expenseCategory)
    {
        $expenseCategory = ExpenseCategory::where('user_id', Auth::id())->findOrFail($id);

        $expenseCategory->delete();
    }
}
