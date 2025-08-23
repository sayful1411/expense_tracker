<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = Expense::where('user_id', auth()->id())->with(['category'])->latest()->paginate(10);

        return view('expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();

        return view('expenses.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|decimal:2|min:1.00|max:99999999.99',
            'date' => 'date',
        ]);

        $data['user_id'] = auth()->id();

        Expense::create($data);

        return redirect()->back();
    }

    /**
     * Display total expenses.
     */
    public function summary()
    {
        $userId = auth()->id();
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        $categories = Category::pluck('name', 'id');

        $expenses = Expense::select('category_id', DB::raw('SUM(amount) as total'))
            ->where('user_id', $userId)
            ->whereBetween('date', [$start, $end])
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $total = $expenses->sum('total');

        return view('expenses.summary', compact('expenses', 'categories', 'total'));
    }
}
