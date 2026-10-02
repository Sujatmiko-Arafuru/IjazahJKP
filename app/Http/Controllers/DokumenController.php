<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    /**
     * Stream the requested document inline for browser previewing.
     */
    public function viewFile($alumniId, $type)
    {
        $alumni = Alumni::with(['dokumen', 'pengembalian'])->findOrFail($alumniId);
        $path = null;

        if ($type === 'pas_foto' && $alumni->dokumen) {
            $path = $alumni->dokumen->pas_foto;
        } elseif ($alumni->dokumen && isset($alumni->dokumen->$type)) {
            $path = $alumni->dokumen->$type;
        } elseif ($alumni->pengembalian) {
            $fileField = $type . '_file';
            if (isset($alumni->pengembalian->$fileField)) {
                $path = $alumni->pengembalian->$fileField;
            }
        }

        if (!$path || !Storage::disk('public')->exists($path)) {
            abort(404, 'File belum diunggah atau tidak ditemukan.');
        }

        $fileContent = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);

        return response($fileContent, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . basename($path) . '"');
    }
}
