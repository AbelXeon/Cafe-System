<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Extra;
use Illuminate\Http\Request;

class ApiMenuController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::with(['products' => function ($q) {
            $q->where('is_available', true);
        }])->get();

        $categoryNames = $categories->pluck('name')->values()->toArray();

        if (!in_array('All', $categoryNames)) {
            $middleIndex = (int) floor(count($categoryNames) / 2);
            array_splice($categoryNames, $middleIndex, 0, 'All');
        }

        $extras = Extra::where('is_available', true)->get();

        return response()->json([
            'categories' => $categoryNames,
            'products' => $categories->flatMap(function ($category) {
                return $category->products->map(function ($product) use ($category) {
                    return [
                        'id'          => $product->id,
                        'name'        => $product->name,
                        'description' => $product->description,
                        'price'       => (float) $product->price,
                        'image'       => asset('storage/' . $product->image),
                        'category'    => $category->name,
                    ];
                });
            })->values(),
            'extras' => $extras->map(function ($extra) {
                return [
                    'id'    => $extra->id,
                    'name'  => $extra->name,
                    'price' => (float) $extra->price,
                ];
            })->values(),
        ]);
    }
}