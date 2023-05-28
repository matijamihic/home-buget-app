<?php

namespace App\Services;

use App\Models\ExpenseCategory;

class ExpenseCategoryService extends WalletService
{
    /**
     * Create a new expense category
     *
     * @param  array  $data
     * @return \App\Models\Income
     */

    public function createExpenseCategory(array $data)
    {
        $data['name'] = strtolower($data['name']);        

        $expenseCategory = ExpenseCategory::firstOrCreate([
            'user_id' => auth()->id(),
            'name' => $data['name'],
        ], [
            'user_id' => null,
            'name' => $data['name'],
        ]);

        if ($expenseCategory) {
            return "Expense category already exists.";
        }

        return "Expense category created successfully.";
    }

    /**
     * Delete an expense category
     *
     * @param  array  $data
     * @return \App\Models\Income
     */
    public function deleteExpenseCategory($id)
    {
        $expenseCategory = ExpenseCategory::find($id);

        if (!$expenseCategory) {

            return "Expense category does not exist.";
        }

        // delete all expenses in this category
        $expenses = $expenseCategory->expenses;

        foreach ($expenses as $expense) {
            $expense->delete();
        }

        // delete the category
        $expenseCategory->delete();

        return "Expense category deleted successfully.";

    }
}
