<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Workspace') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('workspace.update', $workspace->workspace_id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Nama Workspace -->
                        <div class="mb-4">
                            <label for="nama_workspace" class="block text-gray-700 text-sm font-bold mb-2">Nama Workspace</label>
                            <input type="text" name="nama_workspace" id="nama_workspace" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   value="{{ old('nama_workspace', $workspace->nama_workspace) }}" required>
                            @error('nama_workspace')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label for="deskripsi" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="4"
                                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('deskripsi', $workspace->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Harga per Jam -->
                        <div class="mb-4">
                            <label for="harga_per_jam" class="block text-gray-700 text-sm font-bold mb-2">Harga per Jam (Rp)</label>
                            <input type="number" name="harga_per_jam" id="harga_per_jam" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   value="{{ old('harga_per_jam', $workspace->harga_per_jam) }}" required min="0">
                            @error('harga_per_jam')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kapasitas -->
                        <div class="mb-4">
                            <label for="kapasitas" class="block text-gray-700 text-sm font-bold mb-2">Kapasitas (orang)</label>
                            <input type="number" name="kapasitas" id="kapasitas" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   value="{{ old('kapasitas', $workspace->kapasitas) }}" required min="1">
                            @error('kapasitas')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="mb-4">
                            <label for="alamat" class="block text-gray-700 text-sm font-bold mb-2">Alamat</label>
                            <textarea name="alamat" id="alamat" rows="3"
                                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>{{ old('alamat', $workspace->alamat) }}</textarea>
                            @error('alamat')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gambar -->
                        <div class="mb-4">
                            <label for="gambar" class="block text-gray-700 text-sm font-bold mb-2">Gambar Workspace</label>
                            <div class="mb-2">
                                <img src="{{ $workspace->getGambarUrl() }}" alt="Current Workspace Image" class="w-48 h-48 object-cover rounded">
                            </div>
                            <input type="file" name="gambar" id="gambar" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   accept="image/*">
                            @error('gambar')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                            <select name="status" id="status" 
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="tersedia" {{ $workspace->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="tidak tersedia" {{ $workspace->status == 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex flex-col space-y-4 mt-6">
                            <button type="submit" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-4 rounded-lg text-lg text-center shadow-md">
                                Konfirmasi Perubahan
                            </button>
                            
                            <a href="{{ route('dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-4 rounded-lg text-lg text-center shadow-md">
                                Kembali ke Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 