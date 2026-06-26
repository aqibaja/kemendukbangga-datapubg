<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DashboardPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardPageController extends Controller
{
    /* =========================
        STORE (TAMBAH DATA)
    ========================= */
    public function store(Request $request)
    {
        $request->validate([
            'nama_dashboard' => 'required|string|max:255',
            'embed_link' => 'required|url',
            'thumbnail' => 'nullable|image|max:5120',
        ]);

        // SLUG UNIK
        $baseSlug = Str::slug($request->nama_dashboard);
        $slug = $baseSlug;
        $counter = 1;

        while (DashboardPage::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        // THUMBNAIL (HANYA JIKA UPLOAD)
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')
                ->store('thumbnails', 'public');
        }

        $platform = str_contains(strtolower($request->embed_link), 'tableau') ? 'tableau' : 'looker';

        DashboardPage::create([
            'nama_dashboard' => $request->nama_dashboard,
            'platform' => $platform,
            'slug' => $slug,
            'embed_link' => $request->embed_link,
            'thumbnail' => $thumbnailPath, // null jika tidak upload
            'dibuat_oleh' => Auth::id(),
        ]);

        return redirect('/datas')->with('success', 'Card berhasil ditambahkan');
    }

    /* =========================
        UPDATE
    ========================= */
    public function update(Request $request, $id)
    {
        $page = DashboardPage::findOrFail($id);

        $request->validate([
            'nama_dashboard' => 'required|string|max:255',
            'embed_link' => 'required|url',
            'thumbnail' => 'nullable|image|max:5120',
        ]);

        // Update slug jika nama berubah
        if ($page->nama_dashboard !== $request->nama_dashboard) {
            $baseSlug = Str::slug($request->nama_dashboard);
            $slug = $baseSlug;
            $counter = 1;

            while (
                DashboardPage::where('slug', $slug)
                ->where('id', '!=', $page->id)
                ->exists()
            ) {
                $slug = $baseSlug . '-' . $counter++;
            }

            $page->slug = $slug;
        }

        $page->nama_dashboard = $request->nama_dashboard;
        $page->embed_link = $request->embed_link;
        $page->platform = str_contains(strtolower($request->embed_link), 'tableau') ? 'tableau' : 'looker';

        // Jika upload thumbnail baru
        if ($request->hasFile('thumbnail')) {

            // Hapus thumbnail lama JIKA ADA & BUKAN DEFAULT
            if ($page->thumbnail && Storage::disk('public')->exists($page->thumbnail)) {
                Storage::disk('public')->delete($page->thumbnail);
            }

            $page->thumbnail = $request->file('thumbnail')
                ->store('thumbnails', 'public');
        }

        $page->save();

        return redirect()->back()->with('success', 'Halaman berhasil diperbarui');
    }

    /* =========================
        DELETE
    ========================= */
    public function destroy($id)
    {
        $page = DashboardPage::findOrFail($id);

        // Hapus file thumbnail JIKA ADA (UPLOAD USER)
        if ($page->thumbnail && Storage::disk('public')->exists($page->thumbnail)) {
            Storage::disk('public')->delete($page->thumbnail);
        }

        $page->delete();

        return response()->json(['success' => true]);
    }
    
    public function index()
    {
        $dashboards = DashboardPage::withCount('views')
            ->orderBy('views_count', 'desc')
            ->take(3)
            ->get();
    
        // TAMBAHKAN INI
        $activities = \App\Models\DashboardView::with(['user', 'dashboard'])
            ->latest()
            ->take(10)
            ->get();
    
        return view('dashboard', compact('dashboards', 'activities'));
    }
}