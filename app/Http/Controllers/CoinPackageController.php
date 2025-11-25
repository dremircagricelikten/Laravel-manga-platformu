<?php

namespace App\Http\Controllers;

use App\Models\CoinPackage;
use Illuminate\Http\Request;

class CoinPackageController extends Controller
{
    public function index()
    {
        $packages = CoinPackage::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        return view('coin-packages.index', compact('packages'));
    }

    public function purchase(Request $request, $id)
    {
        // TODO: Implement payment gateway (PayTR, Iyzico, etc.)
        
        $package = CoinPackage::findOrFail($id);
        
        return response()->json([
            'success' => false,
            'message' => 'Ödeme sistemi entegrasyonu yapılacak'
        ]);
    }
}
