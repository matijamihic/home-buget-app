<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::middleware('auth')->group(function () {
    Route::group(['prefix' => 'incomes'], function () {
        Route::get('/', [IncomeController::class, 'index']);
        Route::post('/', [IncomeController::class, 'store']);
        Route::get('/{income}', [IncomeController::class, 'show']);
        Route::put('/{income}', [IncomeController::class, 'update']);
        Route::delete('/{income}', [IncomeController::class, 'destroy']);
    });

    Route::prefix('expense-categories')->group(function () {
        Route::get('/', [ExpenseCategoryController::class, 'index']);
        Route::get('/{id}', [ExpenseCategoryController::class, 'show']);
        Route::post('/', [ExpenseCategoryController::class, 'store']);
        Route::put('/{id}', [ExpenseCategoryController::class, 'update']);
        Route::delete('/{id}', [ExpenseCategoryController::class, 'destroy']);
    });

    Route::prefix('expenses')->group(function () {
        Route::get('/', [ExpenseController::class, 'index']);
        Route::get('/{id}', [ExpenseController::class, 'show']);
        Route::post('/', [ExpenseController::class, 'store']);
        Route::put('/{id}', [ExpenseController::class, 'update']);
        Route::delete('/{id}', [ExpenseController::class, 'destroy']);
    });

    Route::prefix('reports')->group(function () {
        Route::get('/expenses/{startDate}/{endDate}/{period?}', [ReportController::class, 'expenseReport']);
        Route::get('/incomes/{startDate}/{endDate}/{period?}', [ReportController::class, 'incomeReport']);
        Route::get('/money-left/{startDate}/{endDate}/{period?}', [ReportController::class, 'moneyLeftReport']);
        Route::get('/expenses/search', [ReportController::class, 'searchExpenses']);
        Route::get('/incomes/search', [ReportController::class, 'searchIncomes']);
    });

    Route::get('/wallet', [WalletController::class, 'show']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found'], 404);
});
