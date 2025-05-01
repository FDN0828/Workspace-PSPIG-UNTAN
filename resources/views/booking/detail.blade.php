<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Pemesanan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-6 flex justify-between items-center">
                    <h3 class="text-lg font-semibold">Detail Pemesanan #{{ $pemesanan->pemesanan_id }}</h3>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full 
                        {{ $pemesanan->status_pemesanan == 'DIBAYAR' ? 'bg-green-100 text-green-800' : 
                           ($pemesanan->status_pemesanan == 'BATAL' ? 'bg-red-100 text-red-800' : 
                           'bg-yellow-100 text-yellow-800') }}">
                        {{ $pemesanan->status_label }}
                    </span>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm text-gray-500 mb-1">Workspace</h4>
                            <p class="font-medium">{{ $pemesanan->workspace->nama_workspace }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm text-gray-500 mb-1">Lokasi</h4>
                            <p class="font-medium">{{ $pemesanan->workspace->alamat }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm text-gray-500 mb-1">Tanggal</h4>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($pemesanan->tanggal_mulai)->format('d M Y') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm text-gray-500 mb-1">Waktu</h4>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($pemesanan->jam_mulai)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($pemesanan->jam_selesai)->format('H:i') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm text-gray-500 mb-1">Total Harga</h4>
                            <p class="font-medium text-blue-600">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm text-gray-500 mb-1">Tanggal Pemesanan</h4>
                            <p class="font-medium">{{ $pemesanan->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                @if($pemesanan->transaction)
                <div class="border-t pt-6 mt-6">
                    <h3 class="text-lg font-semibold mb-4">Detail Pembayaran</h3>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="text-sm text-gray-500 mb-1">ID Transaksi</h4>
                                <p class="font-medium">{{ $pemesanan->transaction->order_id }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm text-gray-500 mb-1">Metode Pembayaran</h4>
                                <p class="font-medium">{{ $pemesanan->transaction->payment_method_label }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm text-gray-500 mb-1">Status Pembayaran</h4>
                                <p class="font-medium">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $pemesanan->transaction->payment_status === 'PAID' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $pemesanan->transaction->status_label }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <h4 class="text-sm text-gray-500 mb-1">Waktu Pembayaran</h4>
                                <p class="font-medium">
                                    {{ $pemesanan->transaction->paid_at ? $pemesanan->transaction->paid_at->format('d M Y H:i') : 'Belum dibayar' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="mt-8 flex justify-between">
                    <a href="{{ route('booking.history') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                        Kembali ke Riwayat
                    </a>

                    @if(in_array($pemesanan->status_pemesanan, ['PENDING', 'MENUNGGU_PEMBAYARAN']))
                        <div class="flex space-x-3">
                            @if($pemesanan->transaction && $pemesanan->transaction->payment_status == 'PENDING')
                                <a href="{{ $pemesanan->transaction->midtrans_payment_url }}" target="_blank" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                    Lanjutkan Pembayaran
                                </a>
                            @else
                                <a href="{{ route('payment.form', ['amount' => $pemesanan->total_harga, 'pemesanan_id' => $pemesanan->pemesanan_id]) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                    Bayar Sekarang
                                </a>
                            @endif

                            <form action="{{ route('booking.cancel', $pemesanan) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition" onclick="return confirm('Apakah Anda yakin ingin membatalkan pemesanan ini?')">
                                    Batalkan Pemesanan
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 