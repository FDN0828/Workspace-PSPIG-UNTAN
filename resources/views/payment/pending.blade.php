<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pembayaran Tertunda
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                <div class="mb-6">
                    <div class="flex justify-center mb-4">
                        <div class="bg-yellow-100 rounded-full p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Pembayaran Sedang Diproses</h3>
                    <p class="text-gray-600 mb-6">Pembayaran Anda sedang diproses. Mohon tunggu beberapa saat sampai kami menerima konfirmasi dari bank/payment gateway.</p>
                    
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('booking.history') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                            Lihat Riwayat Pemesanan
                        </a>
                        <a href="{{ route('dashboard') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                            Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 