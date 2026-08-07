<?php

namespace App\Http\Controllers;

use App\Models\SavedCustomization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedCustomizationController extends Controller
{
    public function store(Request $request)
    {
        if (! Auth::guard('web')->check()) {
            return response()->json(['success' => false, 'message' => 'Please login to save designs.'], 401);
        }

        $data = $request->validate([
            'design_name' => ['required', 'max:100'],
            'design_data' => ['nullable'],
            'total_price' => ['numeric', 'min:0'],
        ]);

        SavedCustomization::query()->create([
            'customer_id' => Auth::guard('web')->id(),
            'design_name' => $data['design_name'],
            'design_data' => is_array($data['design_data'] ?? null) ? json_encode($data['design_data']) : ($data['design_data'] ?? null),
            'total_price' => $data['total_price'] ?? 0,
        ]);

        return response()->json(['success' => true, 'message' => 'Design saved!']);
    }

    public function destroy(Request $request)
    {
        $id = (int) $request->input('id', 0);

        SavedCustomization::query()
            ->where('id', $id)
            ->where('customer_id', Auth::guard('web')->id())
            ->delete();

        return response()->json(['success' => true]);
    }
}
