<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function expenseReport($startDate, $endDate, $period = 'monthly')
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $expenseReport = ReportService::generateExpenseReport($startDate, $endDate, $period);

        return response()->json([
            'expenseReport' => $expenseReport
        ]);
    }

    public function incomeReport($startDate, $endDate, $period = 'monthly')
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $incomeReport = ReportService::generateIncomeReport($startDate, $endDate, $period);

        return response()->json([
            'incomeReport' => $incomeReport
        ]);
    }

    public function moneyLeftReport($startDate, $endDate, $period = 'monthly')
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $moneyLeftReport = ReportService::generateMoneyLeftReport($startDate, $endDate, $period);

        return response()->json([
            'moneyLeftReport' => $moneyLeftReport
        ]);
    }

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
