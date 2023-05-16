<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Http\Requests\CreateIncomeRequest;
use App\Http\Requests\UpdateIncomeRequest;
use Illuminate\Http\JsonResponse;
use App\Services\IncomeService;

class IncomeController extends Controller
{
    /**
     * Display a listing of the incomes.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user_id = auth()->id();
        $incomes = Income::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();

        return response()->json($incomes);
    }

    public function show($id)
    {
        $user_id = auth()->id();
        $income = Income::where('user_id', $user_id)->findOrFail($id);

        return response()->json($income);
    }

    /**
     * Store a newly created income in storage.
     *
     * @param  CreateIncomeRequest  $request
     * @param  IncomeService  $incomeService
     * @return JsonResponse
     */
    public function store(CreateIncomeRequest $request, IncomeService $incomeService)
    {
        $data = $request->toArray();
        $data['user_id'] = auth()->id();

        $income = $incomeService->createIncome($data);

        return response()->json($income, 201);
    }


    /**
     * Update the specified income.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, UpdateIncomeRequest $request, IncomeService $incomeService)
    {
        $income = Income::findOrFail($id);
        $data = $request->toArray();
        $data['user_id'] = auth()->id();

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
    public function destroy($id, IncomeService $incomeService)
    {
        $incomeService->deleteIncome($id);

        return response()->json(['message' => 'Income deleted successfully']);
    }
}
