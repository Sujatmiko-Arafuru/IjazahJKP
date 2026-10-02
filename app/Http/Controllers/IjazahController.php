<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateIjazahRequest;
use App\Models\Alumni;
use App\Models\Ijazah;
use Illuminate\Http\Request;

class IjazahController extends Controller
{
    /**
     * Show public certificate check page.
     */
    public function showSearch(Request $request)
    {
        $alumni = null;
        $searched = false;

        if ($request->filled('nim') && $request->filled('tanggal_lahir')) {
            $searched = true;
            $alumni = Alumni::with('ijazah')
                ->where('nim', $request->nim)
                ->where('tanggal_lahir', $request->tanggal_lahir)
                ->first();
        }

        return view('public.cek_ijazah', compact('alumni', 'searched'));
    }

    /**
     * Admin: List certificate records with sorting, search, filter, and pagination.
     */
    public function adminIndex(Request $request)
    {
        $query = Ijazah::with('alumni');

        // Search realtime (via alumni name/nim with escaped wildcards)
        if ($request->filled('search')) {
            $search = addcslashes(trim($request->search), '%_\\');
            $query->whereHas('alumni', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        // Filter status ijazah
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting with strict whitelist protection
        $sortField = $request->get('sort', 'created_at');
        $sortOrder = strtolower($request->get('order', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedAlumniSorts = ['nama', 'nim'];
        $allowedIjazahSorts = ['status', 'tanggal_siap', 'tanggal_diambil', 'created_at'];

        if (in_array($sortField, $allowedAlumniSorts, true)) {
            $query->join('alumni', 'ijazah.alumni_id', '=', 'alumni.id')
                ->select('ijazah.*')
                ->orderBy('alumni.' . $sortField, $sortOrder);
        } elseif (in_array($sortField, $allowedIjazahSorts, true)) {
            $query->orderBy('ijazah.' . $sortField, $sortOrder);
        } else {
            $query->orderBy('ijazah.created_at', 'desc');
        }

        $ijazahList = $query->paginate(10)->withQueryString();

        return view('admin.ijazah.index', compact('ijazahList'));
    }

    /**
     * Admin: Show form to edit certificate status.
     */
    public function adminEdit(Ijazah $ijazah)
    {
        $ijazah->load('alumni');
        return view('admin.ijazah.edit', compact('ijazah'));
    }

    /**
     * Admin: Update certificate status.
     */
    public function adminUpdate(UpdateIjazahRequest $request, Ijazah $ijazah)
    {
        $data = $request->validated();
        
        // If status is not 'Sudah Diambil', we can clear 'tanggal_diambil' or keep it.
        // If status is 'Siap Diambil' and we didn't specify a location, we could set a default
        $ijazah->update($data);

        return redirect()->route('admin.ijazah.index')
            ->with('toast_success', 'Status pengambilan ijazah berhasil diperbarui.');
    }
}
