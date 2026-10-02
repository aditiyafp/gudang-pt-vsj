<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class BackupController extends Controller
{
    public function index()
    {
        $backupDir = storage_path('app/backups');
        $backups = [];

        if (File::exists($backupDir)) {
            $files = File::files($backupDir);
            foreach ($files as $file) {
                $backups[] = [
                    'filename' => $file->getFilename(),
                    'size' => number_format($file->getSize() / 1024, 2) . ' KB',
                    'date' => Carbon::createFromTimestamp($file->getMTime())->format('d/m/Y H:i:s'),
                    'path' => $file->getRealPath(),
                ];
            }
        }

        // Sort latest first
        usort($backups, function ($a, $b) {
            return strcmp($b['filename'], $a['filename']);
        });

        return view('backup.index', compact('backups'));
    }

    public function create()
    {
        Artisan::call('db:backup');
        return redirect()->route('backup.index')->with('success', 'Backup database berhasil dibuat!');
    }

    public function download($filename)
    {
        $filePath = storage_path('app/backups/' . basename($filename));

        if (!File::exists($filePath)) {
            return back()->with('error', 'File backup tidak ditemukan.');
        }

        return response()->download($filePath);
    }

    public function destroy($filename)
    {
        $filePath = storage_path('app/backups/' . basename($filename));

        if (File::exists($filePath)) {
            File::delete($filePath);
            return redirect()->route('backup.index')->with('success', 'File backup berhasil dihapus.');
        }

        return back()->with('error', 'File backup tidak ditemukan.');
    }
}
