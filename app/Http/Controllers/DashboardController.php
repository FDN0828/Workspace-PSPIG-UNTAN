<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(): View
    {
        try {
            if (auth()->user()->role === 'admin') {
                return view('admin.dashboard');
            } elseif (auth()->user()->role === 'mitra') {
                // Ambil workspace yang dimiliki oleh mitra yang sedang login
                $workspaces = Workspace::where('user_id', Auth::id())->get();
                return view('mitra.dashboard', ['workspaces' => $workspaces]);
            }

            // Untuk user biasa, tampilkan workspace yang tersedia
            $workspaces = Workspace::where('status', 'tersedia')->get();
            return view('dashboard', ['workspaces' => $workspaces]);
            
        } catch (\Exception $e) {
            Log::error('Error in DashboardController: ' . $e->getMessage());
            $workspaces = collect(); // Empty collection if error occurs
            return view('dashboard', ['workspaces' => $workspaces]);
        }
    }
}