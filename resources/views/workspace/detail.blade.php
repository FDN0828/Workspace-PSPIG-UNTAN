<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Workspace
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4">{{ $workspace->nama_workspace }}</h1>
                <div class="relative h-48">
                    <img src="{{ $workspace->getGambarUrl() }}"
                        alt="{{ $workspace->nama_workspace }}"
                        class="w-full h-full object-cover workspace-imgdetail">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold mb-2">Deskripsi</h3>
                            <p class="text-gray-600">{{ $workspace->deskripsi }}</p>
                        </div>

                        <div class="mb-4">
                            <h3 class="text-lg font-semibold mb-2">Informasi</h3>
                            <p class="text-gray-600">Harga: Rp {{ number_format($workspace->harga_per_jam, 0, ',', '.') }}/jam</p>
                            <p class="text-gray-600">Kapasitas: {{ $workspace->kapasitas }} orang</p>
                            <p class="text-gray-600">Status: {{ $workspace->status }}</p>
                            <p class="text-gray-600">Alamat: {{ $workspace->alamat }}</p>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('workspace.booking', $workspace->workspace_id) }}"
                                class="bg-blue-600 text-blue px-6 py-2 rounded hover:bg-blue-700">
                                Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>