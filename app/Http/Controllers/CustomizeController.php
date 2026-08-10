<?php

namespace App\Http\Controllers;

use App\Models\CustomizationOption;
use App\Models\CustomizationPreset;
use App\Models\SavedCustomization;
use Illuminate\Support\Facades\Auth;

class CustomizeController extends Controller
{
    public function index()
    {
        $flowers = CustomizationOption::query()->where('type', 'flower')->where('is_active', true)->orderBy('sort_order')
            ->with(['variants' => fn ($q) => $q->where('is_active', true)])->get();
        $colors = CustomizationOption::query()->where('type', 'color')->where('is_active', true)->orderBy('sort_order')->get();
        $styles = CustomizationOption::query()->where('type', 'style')->where('is_active', true)->orderBy('sort_order')->get();
        $fillers = CustomizationOption::query()->where('type', 'filler')->where('is_active', true)->orderBy('sort_order')->get();
        $addons = CustomizationOption::query()->where('type', 'addon')->where('is_active', true)->orderBy('sort_order')->get();
        $presets = CustomizationPreset::query()->where('is_active', true)->get();

        $savedDesigns = collect();
        if (Auth::guard('web')->check()) {
            $savedDesigns = SavedCustomization::query()
                ->where('customer_id', Auth::guard('web')->id())
                ->orderByDesc('created_at')
                ->get();
        }

        return view('customize.index', compact('flowers', 'colors', 'styles', 'fillers', 'addons', 'presets', 'savedDesigns'));
    }
}
