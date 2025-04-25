<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class WorkspaceController extends Controller
{

// Mendapatkan semua workspace
public function index()
{
    try {
        $workspaces = Workspace::all();
        return response()->json([
            'success' => true,
            'data' => $workspaces
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve workspaces',
            'error' => $e->getMessage()
        ], 500);
    }
}

// Mendapatkan detail workspace berdasarkan ID
public function getWorkspace($id)
{
    try {
        $workspace = Workspace::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $workspace
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Workspace not found',
            'error' => $e->getMessage()
        ], 404);
    }
}
    public function show(Workspace $workspace): View
    {
        return view('workspace.detail', compact('workspace'));
    }

    public function booking(Workspace $workspace): View
    {
        return view('workspace.booking', compact('workspace'));
    }

    public function edit(Workspace $workspace): View
    {
        return view('mitra.edit-workspace', compact('workspace'));
    }

    public function update(Request $request, Workspace $workspace)
    {
        try {
            $request->validate([
                'nama_workspace' => 'required|string|max:100',
                'deskripsi' => 'nullable|string',
                'harga_per_jam' => 'required|numeric|min:0',
                'kapasitas' => 'required|integer|min:1',
                'alamat' => 'required|string',
                'status' => 'required|in:tersedia,tidak tersedia',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $workspace->nama_workspace = $request->nama_workspace;
            $workspace->deskripsi = $request->deskripsi;
            $workspace->harga_per_jam = $request->harga_per_jam;
            $workspace->kapasitas = $request->kapasitas;
            $workspace->alamat = $request->alamat;
            $workspace->status = $request->status;

            if ($request->hasFile('gambar')) {
                $workspace->uploadGambar($request->file('gambar'));
            }

            $workspace->save();

            return redirect()->route('dashboard')->with('success', 'Workspace berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error updating workspace: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui workspace: ' . $e->getMessage())->withInput();
        }
    }
}