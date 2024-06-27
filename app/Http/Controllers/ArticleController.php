<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function deleteSelected(Request $request)
    {
        try {
            $ids = $request->input('ids');
            Article::whereIn('id', $ids)->delete();

            return response()->json(['message' => 'Deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showItems()
{
    $items = Article::latest()->get();
    return view('article.index', compact('items'));
}
}
