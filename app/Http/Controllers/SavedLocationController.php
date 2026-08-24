<?php

namespace App\Http\Controllers;

use App\Models\SavedLocation;
use Illuminate\Http\Request;

class SavedLocationController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'address'   => 'nullable|string|max:500',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $location = SavedLocation::create([
            'user_id'   => $request->user()->id,
            'name'      => $data['name'],
            'address'   => $data['address'] ?? null,
            'latitude'  => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
        ]);

        return response()->json(['success' => true, 'location' => $location]);
    }

    public function destroy(Request $request, SavedLocation $savedLocation)
    {
        if ($savedLocation->user_id !== $request->user()->id) {
            abort(403);
        }

        $savedLocation->delete();

        return response()->json(['success' => true]);
    }
}
