<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with('uploader')->latest();

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->byCategory($request->category);
        }

        $reports = $query->paginate(12);
        $categories = Report::distinct()->pluck('category')->filter();

        return view('reports.index', compact('reports', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'file' => 'required|file|mimes:pdf|max:10240', // Max 10MB
        ]);

        try {
            $file = $request->file('file');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.pdf';
            $filePath = $file->storeAs('reports', $fileName, 'public');

            $report = Report::create([
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'uploaded_by' => auth()->id(),
            ]);

            return redirect()->route('reports.index')
                ->with('success', 'Report uploaded successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to upload report: ' . $e->getMessage());
        }
    }

    public function show(Report $report)
    {
        return view('reports.show', compact('report'));
    }

    public function download(Report $report)
    {
        if (!Storage::disk('public')->exists($report->file_path)) {
            abort(404, 'File not found');
        }

        $report->incrementDownloadCount();

        return Storage::disk('public')->download(
            $report->file_path,
            $report->file_name
        );
    }

    public function preview(Report $report)
    {
        if (!Storage::disk('public')->exists($report->file_path)) {
            abort(404, 'File not found');
        }

        $path = Storage::disk('public')->path($report->file_path);
        
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $report->file_name . '"'
        ]);
    }

    public function destroy(Report $report)
    {
        try {
            // Delete file from storage
            if (Storage::disk('public')->exists($report->file_path)) {
                Storage::disk('public')->delete($report->file_path);
            }

            $report->delete();

            return redirect()->route('reports.index')
                ->with('success', 'Report deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete report: ' . $e->getMessage());
        }
    }
}
