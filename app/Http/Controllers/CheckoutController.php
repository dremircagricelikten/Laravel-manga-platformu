<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentSetting;
use App\Services\PayTRService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['paytrCallback']);
    }

    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty!');
        }

        $total = Cart::getTotalAmount(Auth::id());
        $bankSettings = PaymentSetting::getByGroup('bank_transfer');

        return view('checkout.index', compact('cartItems', 'total', 'bankSettings'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:paytr,bank_transfer',
        ]);

        $cartItems = Cart::where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty!');
        }

        // Create order
        $order = DB::transaction(function () use ($cartItems, $request) {
            $total = Cart::getTotalAmount(Auth::id());

            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'discount_amount' => 0,
                'final_amount' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'coin_package_id' => $cartItem->coin_package_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->coinPackage->price,
                    'total' => $cartItem->subtotal,
                ]);
            }

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            return $order;
        });

        if ($request->payment_method === 'paytr') {
            // PayTR payment
            $paytrService = new PayTRService();
            $paymentData = $paytrService->createPayment($order);

            $order->update(['paytr_token' => $paymentData['paytr_token']]);

            return view('checkout.paytr', compact('paymentData'));
        } else {
            // Bank transfer
            return redirect()->route('checkout.bank-transfer', $order);
        }
    }

    public function bankTransfer(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $bankSettings = PaymentSetting::getByGroup('bank_transfer');

        return view('checkout.bank-transfer', compact('order', 'bankSettings'));
    }

    public function bankTransferSubmit(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'bank_receipt' => 'required|image|max:2048',
            'transfer_date' => 'required|date',
        ]);

        if ($request->hasFile('bank_receipt')) {
            $path = $request->file('bank_receipt')->store('receipts', 'public');
            
            $order->update([
                'bank_receipt' => $path,
                'bank_transfer_date' => $request->transfer_date,
                'payment_status' => 'processing',
            ]);
        }

        return redirect()->route('profile')->with('success', 'Receipt uploaded! Your order is being reviewed.');
    }

    public function paytrCallback(Request $request)
    {
        $data = $request->all();
        
        $paytrService = new PayTRService();

        if (!$paytrService->verifyCallback($data)) {
            return response('FAIL', 400);
        }

        $order = Order::where('order_number', $data['merchant_oid'])->first();

        if (!$order) {
            return response('FAIL', 404);
        }

        if ($data['status'] === 'success') {
            $order->update([
                'payment_status' => 'paid',
                'paytr_response' => json_encode($data),
                'approved_at' => now(),
            ]);

            $order->markAsPaid();

            return response('OK', 200);
        }

        $order->update([
            'payment_status' => 'failed',
            'paytr_response' => json_encode($data),
        ]);

        return response('OK', 200);
    }

    public function success()
    {
        return view('checkout.success');
    }

    public function failed()
    {
        return view('checkout.failed');
    }
}
