<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Booking Workspace
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Kolom Kiri: Info Workspace & Fasilitas -->
                    <div class="md:col-span-2">
                        <h1 class="text-2xl font-bold mb-4">Booking {{ $workspace->nama_workspace }}</h1>
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold mb-2">Informasi Workspace</h3>
                            <p class="text-gray-600">Harga: <span class="font-semibold text-blue-700">Rp {{ number_format($workspace->harga_per_jam, 0, ',', '.') }}/jam</span></p>
                            <p class="text-gray-600">Kapasitas: {{ $workspace->kapasitas }} orang</p>
                            <p class="text-gray-600">Alamat: {{ $workspace->alamat }}</p>
                        </div>
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold mb-2">Fasilitas yang Ditawarkan</h3>
                            <div>
                                @foreach($workspace->fasilitas as $fasilitas)
                                <div class="flex items-center space-x-2 py-2">
                                    <span class="w-6 flex justify-left"><i class="fas {{ $fasilitas->ikon }} text-lg"></i></span>
                                    <span>{{ $fasilitas->nama }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!-- Kolom Kanan: Form Pemesanan -->
                    <div class="bg-gray-50 rounded-lg shadow-md p-6 h-fit">
                        <h3 class="text-lg font-semibold mb-4">Form Pemesanan</h3>
                        
                        @if(session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        <form action="{{ route('workspace.booking.store', $workspace) }}" method="POST" class="space-y-4" id="bookingForm">
                            @csrf
                            <div>
                                <label class="block text-gray-700 mb-1">Nama Pemesan</label>
                                <input type="text" name="nama" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" value="{{ Auth::user()->username }}" readonly required>
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-1">Tanggal</label>
                                <input type="date" name="tanggal" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" 
                                       min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="flex space-x-2">
                                <div class="w-1/2">
                                    <label class="block text-gray-700 mb-1">Jam Mulai</label>
                                    <input type="time" name="jam_mulai" id="jam_mulai" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                                </div>
                                <div class="w-1/2">
                                    <label class="block text-gray-700 mb-1">Durasi (jam)</label>
                                    <input type="number" name="durasi" id="durasi" min="1" max="12" value="1" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                                </div>
                            </div>
                            
                            <!-- Total Harga Dinamis -->
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700">Harga per jam:</span>
                                    <span>Rp {{ number_format($workspace->harga_per_jam, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-gray-700">Durasi:</span>
                                    <span id="durasiDisplay">1 jam</span>
                                </div>
                                <div class="flex justify-between items-center font-bold text-blue-700 mt-2 pt-2 border-t border-blue-200">
                                    <span>Total:</span>
                                    <span id="totalPrice">Rp {{ number_format($workspace->harga_per_jam, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            
                            <input type="hidden" name="total_harga" id="totalHargaInput" value="{{ $workspace->harga_per_jam }}">
                            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 rounded hover:bg-blue-700 transition">Pesan Sekarang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Font Awesome CDN untuk ikon fasilitas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
    <script>
        // Harga per jam dari workspace
        const hargaPerJam = {{ $workspace->harga_per_jam }};
        
        // Menghitung total harga berdasarkan durasi
        function hitungTotal() {
            const durasi = document.getElementById('durasi').value;
            const total = hargaPerJam * durasi;
            
            // Update tampilan durasi
            document.getElementById('durasiDisplay').textContent = durasi + ' jam';
            
            // Format total harga dengan pemisah ribuan
            const formattedTotal = new Intl.NumberFormat('id-ID').format(total);
            document.getElementById('totalPrice').textContent = 'Rp ' + formattedTotal;
            
            // Update nilai hidden input untuk dikirim ke server
            document.getElementById('totalHargaInput').value = total;
        }
        
        // Jalankan perhitungan saat halaman dimuat
        document.addEventListener('DOMContentLoaded', hitungTotal);
        
        // Tambahkan event listener untuk perubahan durasi
        document.getElementById('durasi').addEventListener('input', hitungTotal);
    </script>
</x-app-layout> 