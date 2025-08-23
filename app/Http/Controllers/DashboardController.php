<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $userId = auth()->id();
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        $expenses = Expense::select('category_id', DB::raw('SUM(amount) as total'))
            ->where('user_id', $userId)
            ->whereBetween('date', [$start, $end])
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $labels = [];
        $data = [];
        foreach ($expenses as $expense) {
            $labels[] = $expense->category->name ?? 'Others';
            $data[] = $expense->total / 100;
        }

        $chartData = [
            'labels' => $labels,
            'data' => $data,
        ];

        return view('dashboard', compact('chartData'));
    }
}
