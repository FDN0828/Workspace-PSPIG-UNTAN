<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class WorkspaceController extends Controller
{
    public function index() {
        $workspaces = Workspace::all();
        return response()->json([
            'success' => true,
            'data' => $workspaces,
            'message' => 'List workspace berhasil diambil'
        ]);
    }
    
    public function list($id) {
        $workspace = Workspace::with('fasilitas')->find($id);
        if (!$workspace) {
            return response()->json([
                'success' => false,
                'message' => 'Workspace tidak ditemukan'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $workspace,
            'message' => 'Detail workspace berhasil diambil'
        ]);
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

    public function storeBooking(Request $request, Workspace $workspace)
    {
        $request->validate([
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required',
            'durasi' => 'required|integer|min:1|max:12',
        ]);

        try {
            $jam_mulai = \Carbon\Carbon::parse($request->tanggal . ' ' . $request->jam_mulai);
            $jam_selesai = $jam_mulai->copy()->addHours((int)$request->durasi);
            
            // Gunakan total_harga dari form jika ada, jika tidak hitung berdasarkan durasi
            $total_harga = $request->total_harga ?? ($workspace->harga_per_jam * (int)$request->durasi);

            $pemesanan = Pemesanan::create([
                'customer_id' => auth()->id(),
                'workspace_id' => $workspace->id,
                'tanggal_mulai' => $jam_mulai->format('Y-m-d'),
                'tanggal_selesai' => $jam_selesai->format('Y-m-d'),
                'jam_mulai' => $jam_mulai->format('H:i:s'),
                'jam_selesai' => $jam_selesai->format('H:i:s'),
                'total_harga' => $total_harga,
                'status_pemesanan' => 'PENDING'
            ]);

            // Redirect ke halaman pembayaran dengan menyertakan ID pemesanan
            return redirect()->route('payment.form', [
                'amount' => $total_harga, 
                'pemesanan_id' => $pemesanan->pemesanan_id
            ])->with('success', 'Pemesanan berhasil dibuat. Silakan lakukan pembayaran.');
        } catch (\Exception $e) {
            Log::error('Error creating booking: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membuat pemesanan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function myBookings()
    {
        $pemesanans = Pemesanan::where('customer_id', auth()->id())
            ->with(['workspace', 'transaction'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('booking.history', compact('pemesanans'));
    }

    public function bookingDetail(Pemesanan $pemesanan)
    {
        // Pastikan user hanya bisa melihat pemesanannya sendiri
        if ($pemesanan->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('booking.detail', compact('pemesanan'));
    }

    public function cancelBooking(Pemesanan $pemesanan)
    {
        // Pastikan user hanya bisa membatalkan pemesanannya sendiri
        if ($pemesanan->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Hanya bisa membatalkan pemesanan yang status-nya "PENDING" atau "MENUNGGU_PEMBAYARAN"
            if (in_array($pemesanan->status_pemesanan, ['PENDING', 'MENUNGGU_PEMBAYARAN'])) {
                $pemesanan->update([
                    'status_pemesanan' => 'BATAL'
                ]);
                return redirect()->route('booking.history')
                    ->with('success', 'Pemesanan berhasil dibatalkan.');
            } else {
                return back()->with('error', 'Pemesanan tidak dapat dibatalkan karena status tidak valid.');
            }
        } catch (\Exception $e) {
            Log::error('Error canceling booking: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membatalkan pemesanan.');
        }
    }
}