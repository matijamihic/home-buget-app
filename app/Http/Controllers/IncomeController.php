<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Http\Requests\CreateIncomeRequest;
use App\Http\Requests\UpdateIncomeRequest;
use Illuminate\Http\JsonResponse;
use App\Services\IncomeService;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    private $userId;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->userId = auth()->id();
    }

    /**
     * Display a listing of the incomes.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $incomes = Income::where('user_id', $this->userId)->orderBy('created_at', 'desc')->get();

        return response()->json($incomes);
    }

    public function show($id): JsonResponse
    {
        $income = Income::where('user_id', $this->userId)->findOrFail($id);

        return response()->json($income);
    }

    /**
     * Store a newly created income in storage.
     *
     * @param  CreateIncomeRequest  $request
     * @param  IncomeService  $incomeService
     * @return JsonResponse
     */
    public function store(CreateIncomeRequest $request, IncomeService $incomeService): JsonResponse
    {
        $data = $request->toArray();
        $data["user_id"] = $this->userId;

        $income = $incomeService->createIncome($data);

        return response()->json($income, 201);
    }


    /**
     * Update the specified income.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, UpdateIncomeRequest $request, IncomeService $incomeService): JsonResponse
    {
        $data = $request->toArray();
        $data["user_id"] = $this->userId;

        $income = $incomeService->updateIncome($data, $id);

        return response()->json($income, 200);
    }

    /**
     * Remove the specified income from storage.
     *
     * @param  int  $id
     * @param  IncomeService  $incomeService
     * @return JsonResponse
     */
    public function destroy($id, IncomeService $incomeService): JsonResponse
    {
        $response = $incomeService->deleteIncome($id, $this->userId);

        return response()->json($response, 204);
    }
}
