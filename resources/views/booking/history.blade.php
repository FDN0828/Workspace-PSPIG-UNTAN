<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat Pemesanan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Daftar Pemesanan Anda</h3>
                </div>

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

                @if($pemesanans->isEmpty())
                    <div class="bg-gray-50 p-10 text-center rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-500 mb-1">Tidak ada pemesanan</h3>
                        <p class="text-gray-500 mb-4">Anda belum memiliki riwayat pemesanan workspace</p>
                        <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            Jelajahi Workspace
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Workspace
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal & Waktu
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Harga
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($pemesanans as $pemesanan)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ $pemesanan->workspace->gambar_url ?? asset('images/workspace-placeholder.jpg') }}" alt="{{ $pemesanan->workspace->nama_workspace }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $pemesanan->workspace->nama_workspace }}
                                                </div>
                                                <div class="text-sm text-gray-500 truncate max-w-xs">
                                                    {{ $pemesanan->workspace->alamat }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($pemesanan->tanggal_mulai)->format('d M Y') }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($pemesanan->jam_mulai)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($pemesanan->jam_selesai)->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-medium">
                                            Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $pemesanan->status_pemesanan == 'DIBAYAR' ? 'bg-green-100 text-green-800' : 
                                               ($pemesanan->status_pemesanan == 'BATAL' ? 'bg-red-100 text-red-800' : 
                                                'bg-yellow-100 text-yellow-800') }}">
                                            {{ $pemesanan->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('booking.detail', $pemesanan) }}" class="text-indigo-600 hover:text-indigo-900">
                                                Detail
                                            </a>

                                            @if(in_array($pemesanan->status_pemesanan, ['PENDING', 'MENUNGGU_PEMBAYARAN']))
                                                @if($pemesanan->transaction && $pemesanan->transaction->payment_status == 'PENDING')
                                                    <a href="{{ $pemesanan->transaction->midtrans_payment_url }}" target="_blank" class="text-blue-600 hover:text-blue-900">
                                                        Bayar
                                                    </a>
                                                @else
                                                    <a href="{{ route('payment.form', ['amount' => $pemesanan->total_harga, 'pemesanan_id' => $pemesanan->pemesanan_id]) }}" class="text-blue-600 hover:text-blue-900">
                                                        Bayar
                                                    </a>
                                                @endif

                                                <form action="{{ route('booking.cancel', $pemesanan) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin membatalkan pemesanan ini?')">
                                                        Batalkan
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout> 