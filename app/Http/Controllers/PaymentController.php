<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function showPaymentForm(Request $request)
    {
        $amount = $request->query('amount');
        $pemesanan_id = $request->query('pemesanan_id');

        // Log request untuk debugging
        Log::info('Payment form loaded', [
            'amount' => $amount,
            'pemesanan_id' => $pemesanan_id,
            'user' => auth()->id()
        ]);

        return view('payment.form', compact('amount', 'pemesanan_id'));
    }

    public function createPayment(Request $request)
    {
        try {
            // Validasi request
            $validator = \Validator::make($request->all(), [
                'amount' => 'required|numeric|min:10000',
                'payment_method' => 'required|string|in:qris,bank_transfer,ewallet,credit_card',
                'pemesanan_id' => 'nullable|exists:pemesanan,pemesanan_id'
            ]);

            if ($validator->fails()) {
                Log::error('Payment validation error', [
                    'errors' => $validator->errors()->toArray()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . $validator->errors()->first()
                ], 422);
            }

            // Format order ID - buat tanpa spasi dan karakter khusus
            $orderId = 'ORDER' . time() . Str::random(5);
            $amount = (int)$request->amount; // Pastikan amount adalah integer
            $paymentMethod = $request->payment_method;
            $pemesananId = $request->pemesanan_id;

            // Log request untuk debugging
            Log::info('Creating payment', [
                'order_id' => $orderId,
                'amount' => $amount,
                'payment_method' => $paymentMethod,
                'pemesanan_id' => $pemesananId
            ]);
            
            // Periksa apakah konfigurasi Midtrans diatur dengan benar
            if (empty(config('services.midtrans.server_key'))) {
                Log::error('Midtrans server key not set');
                return response()->json([
                    'success' => false,
                    'message' => 'Konfigurasi Midtrans tidak lengkap. Silakan hubungi administrator.'
                ], 500);
            }

            // Konstruksi parameter dasar
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $amount,
                ],
                'customer_details' => [
                    'first_name' => auth()->user()->name ?? 'Customer',
                    'email' => auth()->user()->email ?? 'customer@example.com',
                ],
                'callbacks' => [
                    'finish' => route('payment.success'), 
                    'error' => route('payment.failure'),
                    'pending' => route('payment.pending')
                ]
            ];

            // Tambahkan parameter berdasarkan metode pembayaran
            switch ($paymentMethod) {
                case 'qris':
                    $params['enabled_payments'] = ['qris'];
                    break;
                case 'bank_transfer':
                    $params['enabled_payments'] = ['bca_va', 'bni_va', 'bri_va', 'mandiri_va', 'permata_va'];
                    break;
                case 'ewallet':
                    $params['enabled_payments'] = ['gopay', 'shopeepay'];
                    break;
                case 'credit_card':
                    $params['enabled_payments'] = ['credit_card'];
                    $params['credit_card'] = [
                        'secure' => true,
                    ];
                    break;
            }

            // Log params untuk debugging
            Log::info('Midtrans params', ['params' => $params]);

            try {
                // Dapatkan token dari Midtrans
                $snapToken = Snap::getSnapToken($params);
                
                // Simpan data transaksi
                $transaction = Transaction::create([
                    'user_id' => auth()->id(),
                    'order_id' => $orderId,
                    'amount' => $amount,
                    'payment_method' => $paymentMethod,
                    'payment_status' => 'PENDING',
                    'midtrans_payment_id' => $snapToken,
                    'midtrans_payment_url' => null, // Kita tidak lagi menggunakan URL karena pakai snap.js
                ]);

                // Jika ada ID pemesanan, update status pemesanan
                if ($pemesananId) {
                    $pemesanan = Pemesanan::find($pemesananId);
                    if ($pemesanan) {
                        // Hanya update status_pemesanan tanpa transaction_id
                        $pemesanan->update([
                            'status_pemesanan' => 'MENUNGGU_PEMBAYARAN'
                        ]);
                        
                        // Mencatat relasi di log untuk referensi
                        Log::info('Pemesanan terkait dengan transaksi', [
                            'pemesanan_id' => $pemesananId,
                            'transaction_id' => $transaction->id
                        ]);
                    }
                }

                // Log sukses
                Log::info('Payment created successfully', [
                    'transaction_id' => $transaction->id,
                    'snap_token' => $snapToken
                ]);

                return response()->json([
                    'success' => true,
                    'snap_token' => $snapToken,
                    'transaction_id' => $transaction->id,
                ]);
            } catch (\Exception $e) {
                Log::error('Midtrans API error', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal terhubung ke Midtrans: ' . $e->getMessage()
                ], 500);
            }
        } catch (\Exception $e) {
            // Log error
            Log::error('Payment creation error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function paymentHistory()
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('payment.history', compact('transactions'));
    }

    public function paymentSuccess(Request $request)
    {
        Log::info('Payment success callback', ['data' => $request->all()]);
        
        // Jika ada ID transaksi dalam request, update status
        if ($request->has('order_id')) {
            $transaction = Transaction::where('order_id', $request->order_id)->first();
            if ($transaction) {
                $transaction->update([
                    'payment_status' => 'PAID',
                    'paid_at' => now()
                ]);
                
                // Update status pemesanan jika ada
                $pemesanan = Pemesanan::where('transaction_id', $transaction->id)->first();
                if ($pemesanan) {
                    $pemesanan->update([
                        'status_pemesanan' => 'DIBAYAR'
                    ]);
                }
            }
        }
        
        return view('payment.success');
    }

    public function paymentFailure(Request $request)
    {
        Log::info('Payment failure callback', ['data' => $request->all()]);
        
        // Simpan status transaksi gagal jika ada order_id
        if ($request->has('order_id')) {
            $transaction = Transaction::where('order_id', $request->order_id)->first();
            if ($transaction) {
                $transaction->update([
                    'payment_status' => 'FAILED'
                ]);
            }
        }
        
        return view('payment.failure');
    }
    
    public function paymentPending(Request $request)
    {
        Log::info('Payment pending callback', ['data' => $request->all()]);
        return redirect()->route('booking.history')
            ->with('info', 'Pembayaran Anda sedang diproses. Silakan cek status pembayaran secara berkala.');
    }

    public function webhook(Request $request)
    {
        $payload = $request->all();
        
        // Log webhook untuk debugging
        Log::info('Midtrans webhook received', ['payload' => $payload]);
        
        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        
        if (!$orderId || !$transactionStatus) {
            return response()->json(['success' => false, 'message' => 'Invalid payload'], 400);
        }
        
        $transaction = Transaction::where('order_id', $orderId)->first();
        if (!$transaction) {
            Log::warning('Transaction not found for webhook', ['order_id' => $orderId]);
            return response()->json(['success' => false, 'message' => 'Transaction not found'], 404);
        }
        
        Log::info('Processing webhook for transaction', [
            'transaction_id' => $transaction->id,
            'status' => $transactionStatus
        ]);
        
        // Proses status transaksi
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            if ($fraudStatus == 'accept' || $fraudStatus === null) {
                $transaction->update([
                    'payment_status' => 'PAID',
                    'paid_at' => now()
                ]);

                // Cari pemesanan terkait dan update statusnya
                // Karena tidak ada relasi transaction_id, cari berdasarkan order_id dari log
                $orderId = $transaction->order_id;
                Log::info('Mencari pemesanan untuk order_id', ['order_id' => $orderId]);
                
                // Asumsi: Anda mungkin memiliki informasi tentang pemesanan yang terkait di log atau cache
                // Sebagai solusi sementara, Anda dapat mencari pemesanan dengan status 'MENUNGGU_PEMBAYARAN'
                // yang dibuat dalam 1 jam terakhir
                $recentPemesanan = Pemesanan::where('status_pemesanan', 'MENUNGGU_PEMBAYARAN')
                    ->where('created_at', '>=', now()->subHour())
                    ->orderBy('created_at', 'desc')
                    ->first();
                    
                if ($recentPemesanan) {
                    $recentPemesanan->update([
                        'status_pemesanan' => 'DIBAYAR'
                    ]);
                    Log::info('Pemesanan diupdate ke DIBAYAR', ['pemesanan_id' => $recentPemesanan->pemesanan_id]);
                } else {
                    Log::warning('Tidak dapat menemukan pemesanan yang terkait dengan transaksi', ['transaction_id' => $transaction->id]);
                }
                
                Log::info('Payment completed', ['transaction_id' => $transaction->id]);
            }
        } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $transaction->update([
                'payment_status' => 'FAILED'
            ]);
            
            Log::info('Payment failed', ['transaction_id' => $transaction->id, 'status' => $transactionStatus]);
        } elseif ($transactionStatus == 'pending') {
            $transaction->update([
                'payment_status' => 'PENDING'
            ]);
            
            Log::info('Payment pending', ['transaction_id' => $transaction->id]);
        }

        return response()->json(['success' => true]);
    }
}
