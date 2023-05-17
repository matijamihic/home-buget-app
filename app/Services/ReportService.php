<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Expense;
use App\Models\Income;

class ReportService
{
    public static function generateExpenseReport($startDate, $endDate, $period = 'monthly')
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $expenses = Expense::where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->where('user_id', auth()->id())
            ->get();

        $expenseReport = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $startOfMonth = $currentDate->copy()->startOfMonth();
            $endOfMonth = $currentDate->copy()->endOfMonth();

            $periodExpenses = $expenses->where('date', '>=', $startOfMonth)
                ->where('date', '<=', $endOfMonth)
                ->sum('amount');    

            $expenseReport[$currentDate->format('Y-m')] = $periodExpenses;

            if ($period === 'quarterly') {
                $currentDate->addMonths(3);
            } else {
                $currentDate->addMonth();
            }
        }

        return $expenseReport;
    }

    public static function generateIncomeReport($startDate, $endDate, $period = 'monthly')
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $incomes = Income::where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->where('user_id', auth()->id())
            ->get();

        $incomeReport = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $startOfMonth = $currentDate->copy()->startOfMonth();
            $endOfMonth = $currentDate->copy()->endOfMonth();

            $periodIncomes = $incomes->where('date', '>=', $startOfMonth)
                ->where('date', '<=', $endOfMonth)
                ->sum('amount');

            $incomeReport[$currentDate->format('Y-m')] = $periodIncomes;

            if ($period === 'quarterly') {
                $currentDate->addMonths(3);
            } else {
                $currentDate->addMonth();
            }
        }

        return $incomeReport;
    }

    public static function generateMoneyLeftReport($startDate, $endDate, $period = 'monthly')
    {
        $expenseReport = self::generateExpenseReport($startDate, $endDate, $period);
        $incomeReport = self::generateIncomeReport($startDate, $endDate, $period);

        $moneyLeftReport = [];

        foreach ($expenseReport as $date => $expenses) {
            $income = $incomeReport[$date] ?? 0;
            $moneyLeft = $income - $expenses;
            $moneyLeftReport[$date] = $moneyLeft;
        }

        return $moneyLeftReport;
    }

    public static function searchIncomes($startDate, $endDate, $amountMin = 0, $amountMax = PHP_INT_MAX, $incomeType = 'all', $incomeDescription = '')
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $incomes = Income::where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->where('amount', '>=', $amountMin)
            ->where('amount', '<=', $amountMax)
            ->where('user_id', auth()->id());

        if ($incomeType !== 'all') {
            $incomes->where('type', $incomeType);
        }

        if (!empty($incomeDescription)) {
            $incomes->where('description', 'like', "%{$incomeDescription}%");
        }

        $filteredIncomes = $incomes->get();
        $incomesSum = $filteredIncomes->sum('amount');

        return [
            'filteredIncomes' => $filteredIncomes,
            'incomesSum' => $incomesSum,
        ];
    }

    public static function searchExpenses($startDate, $endDate, $amountMin = 0, $amountMax = PHP_INT_MAX, $expenseCategory = 'all', $expenseDescription = '')
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $expenses = Expense::where('date', '>=', $startDate)
            ->where('user_id', auth()->id())
            ->where('amount', '>=', $amountMin)
            ->where('amount', '<=', $amountMax)
            ->where('date', '<=', $endDate);

        if ($expenseCategory !== 'all') {
            $expenses->where('expense_category_id', $expenseCategory);
        }

        if (!empty($expenseDescription)) {
            $expenses->where('description', 'like', "%{$expenseDescription}%");
        }

        $filteredExpenses = $expenses->get();
        $expensesSum = $filteredExpenses->sum('amount');

        return [
            'filteredExpenses' => $filteredExpenses,
            'expensesSum' => $expensesSum,
        ];
    }
}
