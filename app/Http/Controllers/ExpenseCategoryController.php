<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateExpenseCategoryRequest;
use App\Http\Requests\UpdateExpenseCategoryRequest;

class ExpenseCategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/expense-categories",
     *     summary="Get a list of expense categories",
     *     @OA\Response(response="200", description="List of expense categories")
     * )
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
     * @OA\Post(
     *     path="/expense-categories",
     *     summary="Create a new expense category",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Expense category created"),
     *     @OA\Response(response="400", description="Expense category already exists"),
     *     @OA\Response(response="422", description="Validation error")
     * )
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
     * @OA\Get(
     *     path="/expense-categories/{id}",
     *     summary="Get details of a specific expense category",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the expense category",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Expense category details"),
     *     @OA\Response(response="404", description="Expense category not found")
     * )
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
     * @OA\Put(
     *     path="/expense-categories/{id}",
     *     summary="Update a specific expense category",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the expense category",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Expense category updated"),
     *     @OA\Response(response="404", description="Expense category not found"),
     *     @OA\Response(response="422", description="Validation error")
     * )
     */
    public function update($id, UpdateExpenseCategoryRequest $request)
    {
        $data = $request->toArray();

        $expenseCategory = ExpenseCategory::where('user_id', Auth::id())->findOrFail($id);
        
        $expenseCategory->update($data);

        return response()->json($expenseCategory);
    }

    /**
     * @OA\Delete(
     *     path="/expense-categories/{id}",
     *     summary="Delete a specific expense category",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the expense category",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="Expense category deleted"),
     *     @OA\Response(response="404", description="Expense category not found")
     * )
     */
    public function destroy($id, ExpenseCategory $expenseCategory)
    {
        $expenseCategory = ExpenseCategory::where('user_id', Auth::id())->findOrFail($id);

        $expenseCategory->delete();
    }
}
