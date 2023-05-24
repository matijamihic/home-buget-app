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
     * @OA\Get(
     *     path="/expenses",
     *     summary="Get a list of expenses",
     *     @OA\Response(response="200", description="List of expenses")
     * )
     */
    public function index(): JsonResponse
    {
        $expenses = Expense::where('user_id', $this->userId)->orderBy('created_at', 'desc')->get();

        return response()->json($expenses);
    }

    /**
     * @OA\Get(
     *     path="/expenses/{id}",
     *     summary="Get details of a specific expense",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the expense",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Expense details"),
     *     @OA\Response(response="404", description="Expense not found")
     * )
     */
    public function show($id)
    {
        $expense = Expense::where('user_id', $this->userId)->findOrFail($id);

        return response()->json($expense);
    }

    /**
     * @OA\Post(
     *     path="/expenses",
     *     summary="Create a new expense",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="property1", type="type1"),
     *         )
     *     ),
     *     @OA\Response(response="201", description="Expense created"),
     *     @OA\Response(response="422", description="Validation error")
     * )
     */
    public function store(CreateExpenseRequest $request, ExpenseService $expenseService)
    {
        $data = $request->toArray();
        $data["user_id"] = $this->userId;

        $income = $expenseService->createExpense($data);

        return response()->json($income, 201);
    }

    /**
     * @OA\Put(
     *     path="/expenses/{id}",
     *     summary="Update a specific expense",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the expense",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="property1", type="type1"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Expense updated"),
     *     @OA\Response(response="404", description="Expense not found"),
     *     @OA\Response(response="422", description="Validation error")
     * )
     */
    public function update($id, UpdateExpenseRequest $request, ExpenseService $expenseService)
    {
        $data = $request->toArray();
        $data["user_id"] = $this->userId;

        $expense = $expenseService->updateExpense($data, $id);

        return response()->json($expense, 200);
    }

    /**
     * @OA\Delete(
     *     path="/expenses/{id}",
     *     summary="Delete a specific expense",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the expense",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="Expense deleted"),
     *     @OA\Response(response="404", description="Expense not found")
     * )
     */
    public function destroy($id, ExpenseService $expenseService)
    {
        $response = $expenseService->deleteExpense($id);

        return response()->json($response, 204);
    }
}
