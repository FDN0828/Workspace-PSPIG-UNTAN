<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Booking Workspace
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4">Booking {{ $workspace->nama_workspace }}</h1>
                
                <div class="mb-4">
                    <h3 class="text-lg font-semibold mb-2">Informasi Workspace</h3>
                    <p class="text-gray-600">Harga: Rp {{ number_format($workspace->harga_per_jam, 0, ',', '.') }}/jam</p>
                    <p class="text-gray-600">Kapasitas: {{ $workspace->kapasitas }} orang</p>
                    <p class="text-gray-600">Alamat: {{ $workspace->alamat }}</p>
                </div>

                <div class="mt-6">
                    <p class="text-gray-600">Form pemesanan akan ditambahkan di sini.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 