<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * @OA\Get(
     *     path="/expense-report/{startDate}/{endDate}/{period}",
     *     summary="Generate expense report",
     *     @OA\Parameter(
     *         name="startDate",
     *         in="path",
     *         description="Start date of the report (YYYY-MM-DD)",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="endDate",
     *         in="path",
     *         description="End date of the report (YYYY-MM-DD)",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="period",
     *         in="path",
     *         description="Report period (monthly, weekly, daily, etc.)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Expense report generated successfully")
     * )
     */
    public function expenseReport($startDate, $endDate, $period = 'monthly')
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $expenseReport = ReportService::generateExpenseReport($startDate, $endDate, $period);

        return response()->json([
            'expenseReport' => $expenseReport
        ]);
    }

    /**
     * @OA\Get(
     *     path="/income-report/{startDate}/{endDate}/{period}",
     *     summary="Generate income report",
     *     @OA\Parameter(
     *         name="startDate",
     *         in="path",
     *         description="Start date of the report (YYYY-MM-DD)",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="endDate",
     *         in="path",
     *         description="End date of the report (YYYY-MM-DD)",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="period",
     *         in="path",
     *         description="Report period (monthly, weekly, daily, etc.)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Income report generated successfully")
     * )
     */
    public function incomeReport($startDate, $endDate, $period = 'monthly')
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $incomeReport = ReportService::generateIncomeReport($startDate, $endDate, $period);

        return response()->json([
            'incomeReport' => $incomeReport
        ]);
    }

    /**
     * @OA\Get(
     *     path="/money-left-report/{startDate}/{endDate}/{period}",
     *     summary="Generate money left report",
     *     @OA\Parameter(
     *         name="startDate",
     *         in="path",
     *         description="Start date of the report (YYYY-MM-DD)",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="endDate",
     *         in="path",
     *         description="End date of the report (YYYY-MM-DD)",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="period",
     *         in="path",
     *         description="Report period (monthly, weekly, daily, etc.)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Money left report generated successfully")
     * )
     */
    public function moneyLeftReport($startDate, $endDate, $period = 'monthly')
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $moneyLeftReport = ReportService::generateMoneyLeftReport($startDate, $endDate, $period);

        return response()->json([
            'moneyLeftReport' => $moneyLeftReport
        ]);
    }

    /**
     * @OA\Post(
     *     path="/search-expenses",
     *     summary="Search expenses",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="startDate", type="string", example="2023-01-01"),
     *             @OA\Property(property="endDate", type="string", example="2023-12-31"),
     *             @OA\Property(property="amount", type="integer", example=100),
     *             @OA\Property(property="amountMax", type="integer", example=500),
     *             @OA\Property(property="expenseCategory", type="string", example="food"),
     *             @OA\Property(property="expenseDescription", type="string", example="restaurant")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Expenses search results")
     * )
     */
    public function searchExpenses(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $amountMin = $request->input('amount', 0);
        $amountMax = $request->input('amountMax', PHP_INT_MAX);
        $expenseCategory = $request->input('expenseCategory', 'all');
        $expenseDescription = $request->input('expenseDescription', '');

        $results = ReportService::searchExpenses($startDate, $endDate, $amountMin, $amountMax, $expenseCategory, $expenseDescription);

        return response()->json($results);
    }

    /**
     * @OA\Post(
     *     path="/search-incomes",
     *     summary="Search incomes",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="startDate", type="string", example="2023-01-01"),
     *             @OA\Property(property="endDate", type="string", example="2023-12-31"),
     *             @OA\Property(property="amount", type="integer", example=100),
     *             @OA\Property(property="amountMax", type="integer", example=500),
     *             @OA\Property(property="incomeType", type="string", example="salary"),
     *             @OA\Property(property="incomeDescription", type="string", example="bonus")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Incomes search results")
     * )
     */
    public function searchIncomes(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $amountMin = $request->input('amount', 0);
        $amountMax = $request->input('amountMax', PHP_INT_MAX);
        $incomeType = $request->input('incomeType', 'all');
        $incomeDescription = $request->input('incomeDescription', '');

        $results = ReportService::searchIncomes($startDate, $endDate, $amountMin, $amountMax, $incomeType, $incomeDescription);

        return response()->json($results);
    }
}
