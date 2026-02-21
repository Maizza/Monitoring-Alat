<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Maintenance;
use App\Exports\RepairExport;
use App\Exports\MaintenanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB; // Tambahkan ini buat transaksi aman

class MaintenanceController extends Controller
{
    /**
     * List Laporan Repair (Operator)
     */
    public function repairIndex()
    {
        $repairs = Comment::with(['alat', 'user', 'maintenances'])->latest()->get();
        return view('repair.index', compact('repairs'));
    }

    /**
     * Menghapus Data Repair (Laporan Utama)
     * Menghapus laporan beserta semua histori maintenancenya.
     */
    public function repairDestroy($id)
    {
        return DB::transaction(function () use ($id) {
            $repair = Comment::findOrFail($id);
            
            // Hapus semua histori maintenance yang terkait dulu
            $repair->maintenances()->delete();
            
            // Baru hapus laporan utamanya
            $repair->delete();

            return back()->with('success', 'Laporan repair dan seluruh historinya berhasil dihapus!');
        });
    }

    /**
     * List Data Maintenance (Mekanik - DONE Only)
     */
    public function maintenanceIndex()
    {
        $maintenances = Maintenance::with('comment.alat')
            ->where('status_kerja', 'DONE') 
            ->latest()
            ->get();

        return view('maintenance.index', compact('maintenances'));
    }

    /**
     * Menghapus Data Maintenance (Histori Mekanik)
     */
    public function maintenanceDestroy($id)
    {
        $maintenance = Maintenance::findOrFail($id);
        $maintenance->delete();

        return back()->with('success', 'Histori maintenance berhasil dihapus!');
    }

    /**
     * Export Excel Sections
     */
    public function repairExport()
    {
        return Excel::download(new RepairExport, 'laporan-repair-' . date('d-m-Y') . '.xlsx');
    }

    public function reportExport()
    {
        return Excel::download(new MaintenanceExport, 'histori-maintenance-' . date('d-m-Y') . '.xlsx');
    }
    
}