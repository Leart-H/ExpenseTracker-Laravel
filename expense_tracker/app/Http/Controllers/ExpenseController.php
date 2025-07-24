<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    
    public function index()
    {
        $expenses = Expense::with('category')->get();

        
        $categoryData = Expense::select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->get();

        $categoryNames = [];
        $categorySums = [];

        foreach ($categoryData as $data) {
            $category = Category::find($data->category_id);
            $categoryNames[] = $category ? $category->name : 'Other';
            $categorySums[] = $data->total;
        }

        return view('expenses.index', compact('expenses', 'categoryNames', 'categorySums'));
    }

    
    public function create()
    {
        $categories = Category::all();
        return view('expenses.create', compact('categories'));
    }

  
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date',
        ]);

        Expense::create($request->all());

        return redirect()->route('expenses.index')->with('success', 'Expense added successfully!');
    }

  
    public function edit(Expense $expense)
    {
        $categories = Category::all();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    
    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date',
        ]);

        $expense->update($request->all());

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully!');
    }
}
