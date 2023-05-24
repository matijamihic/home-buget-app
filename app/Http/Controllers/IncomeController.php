<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Http\Requests\CreateIncomeRequest;
use App\Http\Requests\UpdateIncomeRequest;
use Illuminate\Http\JsonResponse;
use App\Services\IncomeService;
use OpenApi\Annotations as OA;

class IncomeController extends Controller
{
    private $userId;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->userId = auth()->id();
    }

    /**
     * @OA\Get(
     *     path="/incomes",
     *     summary="Get all incomes",
     *     @OA\Response(response="200", description="List of incomes")
     * )
     */
    public function index(): JsonResponse
    {
        $incomes = Income::where('user_id', $this->userId)->orderBy('created_at', 'desc')->get();

        return response()->json($incomes);
    }

    /**
     * @OA\Get(
     *     path="/incomes/{id}",
     *     summary="Get a specific income",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the income",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Income details"),
     *     @OA\Response(response="404", description="Income not found")
     * )
     */
    public function show($id): JsonResponse
    {
        $income = Income::where('user_id', $this->userId)->findOrFail($id);

        return response()->json($income);
    }

    /**
     * @OA\Post(
     *     path="/incomes",
     *     summary="Create a new income",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Schema(
     *                 @OA\Property(property="property1", type="string"),
     *                 @OA\Property(property="property2", type="integer"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="201", description="Income created"),
     *     @OA\Response(response="422", description="Validation error")
     * )
     */
    public function store(CreateIncomeRequest $request, IncomeService $incomeService): JsonResponse
    {
        $data = $request->toArray();
        $data["user_id"] = $this->userId;

        $income = $incomeService->createIncome($data);

        return response()->json($income, 201);
    }

    /**
     * @OA\Put(
     *     path="/incomes/{id}",
     *     summary="Update a specific income",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the income",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Schema(
     *                 @OA\Property(property="property1", type="string"),
     *                 @OA\Property(property="property2", type="integer"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Income updated"),
     *     @OA\Response(response="404", description="Income not found"),
     *     @OA\Response(response="422", description="Validation error")
     * )
     */
    public function update($id, UpdateIncomeRequest $request, IncomeService $incomeService): JsonResponse
    {
        $data = $request->toArray();
        $data["user_id"] = $this->userId;

        $income = $incomeService->updateIncome($data, $id);

        return response()->json($income, 200);
    }

    /**
     * @OA\Delete(
     *     path="/incomes/{id}",
     *     summary="Delete a specific income",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the income",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="Income deleted"),
     *     @OA\Response(response="404", description="Income not found")
     * )
     */
    public function destroy($id, IncomeService $incomeService): JsonResponse
    {
        $response = $incomeService->deleteIncome($id, $this->userId);

        return response()->json($response, 204);
    }
}
