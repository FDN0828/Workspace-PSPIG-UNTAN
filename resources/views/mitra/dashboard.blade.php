<link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css" />
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Mitra') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Workspace Saya</h3>
                    </div>

                    @if($workspaces->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($workspaces as $workspace)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="relative h-48">
                                <img src="{{ $workspace->getGambarUrl() }}"
                                    alt="{{ $workspace->nama_workspace }}"
                                    class="w-full h-full object-cover workspace-img">
                            </div>
                            <div class="p-4">
                                <h3 class="text-lg font-semibold">{{ $workspace->nama_workspace }}</h3>
                                <div class="mt-2">
                                    <p class="text-red-600 font-semibold">Rp {{ number_format($workspace->harga_per_jam, 0, ',', '.') }}/jam</p>
                                    <p class="text-sm text-gray-500">Kapasitas: {{ $workspace->kapasitas }} orang</p>
                                    <p class="text-sm text-gray-500">{{ $workspace->alamat }}</p>
                                    <p class="text-sm {{ $workspace->status == 'tersedia' ? 'text-green-500' : 'text-red-500' }}">
                                        Status: {{ ucfirst($workspace->status) }}
                                    </p>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('workspace.edit', $workspace->id) }}"
                                        class="text-blue-600 hover:text-blue-800">
                                        Edit Workspace
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <p class="text-gray-600">Anda belum memiliki workspace.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>