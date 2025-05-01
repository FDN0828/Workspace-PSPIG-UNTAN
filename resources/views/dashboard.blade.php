<link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css" />
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Workspace Tersedia</h2>

                @if(isset($workspaces) && $workspaces->count() > 0)
                    <div class="grid grid-cols-3 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($workspaces as $workspace)
                            <a href="{{ route('workspace.detail', $workspace->id) }}" class="workspace-card">
                                <div class="bg-white rounded-lg overflow-hidden">
                                    <div class="workspace-image-container">
                                        <img src="{{ $workspace->getGambarUrl() }}" 
                                             alt="{{ $workspace->nama_workspace }}" 
                                             class="workspace-thumbnail">
                                    </div>
                                    <div class="workspace-info">
                                        <div class="workspace-name">{{ $workspace->nama_workspace }}</div>
                                        <div class="workspace-price">Rp {{ number_format($workspace->harga_per_jam, 0, ',', '.') }}/jam</div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-gray-600">Tidak ada workspace yang tersedia saat ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
