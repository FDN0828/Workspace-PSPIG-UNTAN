<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pembayaran
        </h2>
    </x-slot>

    <!-- Muat Snap.js Midtrans -->
    <script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" 
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Alert Success & Error -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Detail Pembayaran</h3>
                    
                    <!-- Tambahkan detail tambahan tentang pemesanan -->
                    @if(isset($pemesanan_id))
                        <input type="hidden" id="pemesanan_id" value="{{ $pemesanan_id }}">
                        <p class="text-gray-600 mb-2">ID Pemesanan: <span class="font-semibold">{{ $pemesanan_id }}</span></p>
                        
                        @php
                            $pemesanan = App\Models\Pemesanan::find($pemesanan_id);
                        @endphp
                        
                        @if($pemesanan)
                            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                <p class="text-gray-600 mb-1">
                                    <span class="font-semibold">Workspace:</span> 
                                    {{ $pemesanan->workspace->nama_workspace }}
                                </p>
                                <p class="text-gray-600 mb-1">
                                    <span class="font-semibold">Tanggal:</span> 
                                    {{ \Carbon\Carbon::parse($pemesanan->tanggal_mulai)->format('d M Y') }}
                                </p>
                                <p class="text-gray-600 mb-1">
                                    <span class="font-semibold">Waktu:</span> 
                                    {{ \Carbon\Carbon::parse($pemesanan->jam_mulai)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($pemesanan->jam_selesai)->format('H:i') }}
                                </p>
                                <p class="text-gray-600">
                                    <span class="font-semibold">Durasi:</span> 
                                    {{ \Carbon\Carbon::parse($pemesanan->jam_mulai)->diffInHours(\Carbon\Carbon::parse($pemesanan->jam_selesai)) }} jam
                                </p>
                            </div>
                        @endif
                    @endif
                    
                    <div class="bg-blue-50 p-4 rounded-lg flex justify-between items-center">
                        <p class="font-semibold text-gray-700">Total yang harus dibayar:</p> 
                        <p class="font-bold text-xl text-blue-600">Rp {{ number_format($amount, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">Pilih Metode Pembayaran</h3>
                    
                    <div class="space-y-4">
                        <!-- QRIS -->
                        <div class="payment-method border rounded-lg p-4 cursor-pointer hover:bg-gray-50 transition" data-method="qris">
                            <div class="flex items-center">
                                <input type="radio" name="payment_method" id="qris" value="qris" class="mr-3">
                                <label for="qris" class="flex items-center flex-grow cursor-pointer">
                                    <img src="{{ asset('images/payment/Qris.png') }}" alt="QRIS" class="h-10 mr-3">
                                    <div>
                                        <p class="font-medium">QRIS</p>
                                        <p class="text-sm text-gray-500">Bayar dengan QRIS di semua aplikasi e-wallet</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Bank Transfer -->
                        <div class="payment-method border rounded-lg p-4 cursor-pointer hover:bg-gray-50 transition" data-method="bank_transfer">
                            <div class="flex items-center">
                                <input type="radio" name="payment_method" id="bank_transfer" value="bank_transfer" class="mr-3">
                                <label for="bank_transfer" class="flex items-center flex-grow cursor-pointer">
                                    <img src="{{ asset('images/payment/bank.png') }}" alt="Bank Transfer" class="h-10 mr-3">
                                    <div>
                                        <p class="font-medium">Transfer Bank</p>
                                        <p class="text-sm text-gray-500">BCA, BNI, BRI, Mandiri, dan bank lainnya</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <!-- E-Wallet -->
                        <div class="payment-method border rounded-lg p-4 cursor-pointer hover:bg-gray-50 transition" data-method="ewallet">
                            <div class="flex items-center">
                                <input type="radio" name="payment_method" id="ewallet" value="ewallet" class="mr-3">
                                <label for="ewallet" class="flex items-center flex-grow cursor-pointer">
                                    <img src="{{ asset('images/payment/wallet.png') }}" alt="E-Wallet" class="h-10 mr-3">
                                    <div>
                                        <p class="font-medium">E-Wallet</p>
                                        <p class="text-sm text-gray-500">GoPay, ShopeePay</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Credit Card -->
                        <div class="payment-method border rounded-lg p-4 cursor-pointer hover:bg-gray-50 transition" data-method="credit_card">
                            <div class="flex items-center">
                                <input type="radio" name="payment_method" id="credit_card" value="credit_card" class="mr-3">
                                <label for="credit_card" class="flex items-center flex-grow cursor-pointer">
                                    <img src="{{ asset('images/payment/CreditCard.png') }}" alt="Credit Card" class="h-10 mr-3">
                                    <div>
                                        <p class="font-medium">Kartu Kredit</p>
                                        <p class="text-sm text-gray-500">Visa, Mastercard, JCB</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="button" id="payButton" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition" disabled>
                        Lanjutkan Pembayaran
                    </button>
                    <p id="payment-info" class="text-center text-sm text-gray-500 mt-2">Silakan pilih metode pembayaran terlebih dahulu</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-5 rounded-lg shadow-lg">
            <div class="flex items-center">
                <svg class="animate-spin h-5 w-5 mr-3 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memproses Pembayaran...</span>
            </div>
        </div>
    </div>

    <script>
        // Initialize selected payment method
        let selectedPaymentMethod = null;
        
        // Add event listeners to all payment method options
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                // Set the radio button to checked
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                
                // Update selected method
                selectedPaymentMethod = radio.value;
                
                // Highlight selected method
                document.querySelectorAll('.payment-method').forEach(m => {
                    m.classList.remove('border-blue-500', 'bg-blue-50');
                });
                this.classList.add('border-blue-500', 'bg-blue-50');
                
                // Enable the payment button
                document.getElementById('payButton').disabled = false;
                document.getElementById('payment-info').classList.add('hidden');
            });
        });
        
        function processPayment() {
            if (!selectedPaymentMethod) {
                alert('Silakan pilih metode pembayaran terlebih dahulu');
                return;
            }
            
            // Show loading overlay
            document.getElementById('loading-overlay').classList.remove('hidden');
            
            // Disable button to prevent double submission
            const payButton = document.getElementById('payButton');
            payButton.disabled = true;
            
            // Prepare payment data
            const paymentData = {
                amount: {{ $amount }},
                payment_method: selectedPaymentMethod,
                pemesanan_id: document.getElementById('pemesanan_id')?.value || null
            };
            
            console.log('Sending payment request with data:', paymentData);
            
            // Send to payment endpoint
            fetch('{{ route('payment.create') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(paymentData)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || `Status error: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Payment response data:', data);
                
                if (data.success && data.snap_token) {
                    // Gunakan Snap.js untuk menampilkan popup pembayaran Midtrans
                    try {
                        snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                console.log('Payment success:', result);
                                window.location.href = '{{ route('payment.success') }}?order_id=' + result.order_id;
                            },
                            onPending: function(result) {
                                console.log('Payment pending:', result);
                                window.location.href = '{{ route('payment.pending') }}?order_id=' + result.order_id;
                            },
                            onError: function(result) {
                                console.error('Payment error:', result);
                                window.location.href = '{{ route('payment.failure') }}?order_id=' + result.order_id;
                            },
                            onClose: function() {
                                // Jika user menutup popup tanpa menyelesaikan transaksi
                                console.log('Customer closed the popup without finishing payment');
                                document.getElementById('loading-overlay').classList.add('hidden');
                                payButton.disabled = false;
                                
                                // Tampilkan pesan
                                const errorElement = document.createElement('div');
                                errorElement.className = 'bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4';
                                errorElement.innerHTML = '<span class="block sm:inline">Pembayaran dibatalkan. Silakan coba lagi.</span>';
                                
                                const formElement = document.querySelector('.mb-8').parentNode;
                                formElement.insertBefore(errorElement, formElement.firstChild);
                                
                                // Hapus pesan setelah 5 detik
                                setTimeout(() => {
                                    errorElement.remove();
                                }, 5000);
                            }
                        });
                    } catch (snapError) {
                        console.error('Snap.js error:', snapError);
                        document.getElementById('loading-overlay').classList.add('hidden');
                        payButton.disabled = false;
                        alert('Terjadi kesalahan saat memuat Snap.js: ' + snapError.message);
                    }
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan saat memproses pembayaran');
                }
            })
            .catch(error => {
                console.error('Error detail:', error);
                document.getElementById('loading-overlay').classList.add('hidden');
                payButton.disabled = false;
                
                // Tambahkan pesan error ke halaman
                const errorElement = document.createElement('div');
                errorElement.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4';
                errorElement.innerHTML = '<span class="block sm:inline">Terjadi kesalahan: ' + error.message + '</span>';
                
                const formElement = document.querySelector('.mb-8').parentNode;
                formElement.insertBefore(errorElement, formElement.firstChild);
                
                // Hapus pesan error setelah 10 detik
                setTimeout(() => {
                    errorElement.remove();
                }, 10000);
            });
        }
        
        // Attach event listener to payment button
        document.getElementById('payButton').addEventListener('click', processPayment);
    </script>
</x-app-layout> 