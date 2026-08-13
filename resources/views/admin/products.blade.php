@extends('layouts.admin')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    
    <!-- Form: Add Product -->
    <div class="bg-white p-8 rounded-2xl shadow-sm">
        <h2 class="text-xl font-bold mb-6">Add New Food/Drink</h2>
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <select name="category_id" class="w-full p-3 border rounded-xl bg-gray-50">
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->type }})</option>
                @endforeach
            </select>
            <input type="text" name="name" placeholder="Product Name" class="w-full p-3 border rounded-xl bg-gray-50">
            <textarea name="description" placeholder="Description" class="w-full p-3 border rounded-xl bg-gray-50"></textarea>
            <input type="number" name="price" placeholder="Price" class="w-full p-3 border rounded-xl bg-gray-50">
            <input type="file" name="image" class="w-full">
            <button class="w-full bg-orange-600 text-white font-bold py-3 rounded-xl">Save Product</button>
        </form>
    </div>

    <!-- Form: Add Extras -->
    <div class="bg-white p-8 rounded-2xl shadow-sm">
        <h2 class="text-xl font-bold mb-6">Add Extras (Ketchup, Fries, etc.)</h2>
        <form action="{{ route('admin.extras.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="text" name="name" placeholder="Extra Name" class="w-full p-3 border rounded-xl bg-gray-50">
            <input type="number" name="price" placeholder="Price" class="w-full p-3 border rounded-xl bg-gray-50">
            <button class="w-full bg-slate-800 text-white font-bold py-3 rounded-xl">Save Extra</button>
        </form>
    </div>

</div>

<!-- List of Products -->
<div class="mt-10 bg-white p-8 rounded-2xl shadow-sm">
    <h2 class="text-xl font-bold mb-6">Current Menu</h2>
    <table class="w-full text-left">
        <thead>
            <tr class="border-b text-gray-400">
                <th class="py-3">Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr class="border-b">
                <td class="py-3">
                    <img src="{{ asset('storage/'.$product->image) }}" class="w-12 h-12 rounded-lg object-cover">
                </td>
                <td class="font-bold">{{ $product->name }}</td>
                <td>{{ $product->category->name }}</td>
                <td class="text-orange-600 font-bold">${{ $product->price }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection