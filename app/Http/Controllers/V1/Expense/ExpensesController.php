<?php

namespace Crater\Http\Controllers\V1\Expense;

use Auth;
use Crater\Http\Controllers\Controller;
use Crater\Http\Requests\DeleteExpensesRequest;
use Crater\Http\Requests\ExpenseRequest;
use Crater\Models\Expense;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $limit = $request->has('limit') ? $request->limit : 10;

        $expenses = Expense::with('category', 'creator', 'fields')
            ->leftJoin('users', 'users.id', '=', 'expenses.user_id')
            ->join('expense_categories', 'expense_categories.id', '=', 'expenses.expense_category_id')
            ->applyFilters($request->only([
                'expense_category_id',
                'user_id',
                'expense_id',
                'search',
                'from_date',
                'to_date',
                'orderByField',
                'orderBy',
            ]))
            ->whereCompany($request->header('company'));

        if ($user->isCustomer()) {
            $expenses = $expenses->where('expenses.user_id', $user->id);
        }

        $expenses = $expenses->select('expenses.*', 'expense_categories.name', 'users.name as user_name')
            ->paginateData($limit);

        return response()->json([
            'expenses' => $expenses,
            'expenseTotalCount' => $expenses->count(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ExpenseRequest $request)
    {
        $user = Auth::user();

        if ($user->isCustomer()) {
            return response('Unauthorized.', 401);
        }

        $expense = Expense::createExpense($request);

        return response()->json([
            'expense' => $expense,
            'success' => true,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Crater\Models\Expense $expense
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Expense $expense)
    {
        $user = Auth::user();

        if ($user->isCustomer() && $expense->user_id !== $user->id) {
            return response('Unauthorized.', 401);
        }

        $expense->load('creator', 'fields.customField');

        return response()->json([
            'expense' => $expense,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Crater\Models\Expense $expense
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ExpenseRequest $request, Expense $expense)
    {
        $user = Auth::user();

        if ($user->isCustomer()) {
            return response('Unauthorized.', 401);
        }

        $expense->updateExpense($request);

        return response()->json([
            'expense' => $expense,
            'success' => true,
        ]);
    }

    public function delete(DeleteExpensesRequest $request, Expense $expense)
    {
        $user = Auth::user();

        if ($user->isCustomer()) {
            return response('Unauthorized.', 401);
        }

        $expense->deleteExpenses($request->ids);

        return response()->json([
            'success' => true,
        ]);
    }
}