<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Innovation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InnovationController extends Controller
{
    /**
     * Pastikan hanya Admin (id_role == 1) yang dapat mengelola data inovasi.
     */
    protected function authorizeAdmin(): void
    {
        if (!Auth::check() || Auth::user()->id_role != 1) {
            abort(403, 'Akses khusus Administrator.');
        }
    }

    /**
     * Simpan inovasi baru.
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'url'         => 'required|url|max:500',
            'description' => 'nullable|string|max:500',
            'icon'        => 'nullable|string|max:100',
            'order'       => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        $innovation = Innovation::create([
            'title'       => $validated['title'],
            'url'         => $validated['url'],
            'description' => $validated['description'] ?? null,
            'icon'        => !empty($validated['icon']) ? $validated['icon'] : 'fa-solid fa-lightbulb',
            'order'       => $validated['order'] ?? 0,
            'is_active'   => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Inovasi website berhasil ditambahkan.',
                'data'    => $innovation,
            ]);
        }

        return redirect()->back()->with('success', 'Inovasi website berhasil ditambahkan.');
    }

    /**
     * Update inovasi.
     */
    public function update(Request $request, $id)
    {
        $this->authorizeAdmin();

        $innovation = Innovation::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'url'         => 'required|url|max:500',
            'description' => 'nullable|string|max:500',
            'icon'        => 'nullable|string|max:100',
            'order'       => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        $innovation->update([
            'title'       => $validated['title'],
            'url'         => $validated['url'],
            'description' => $validated['description'] ?? null,
            'icon'        => !empty($validated['icon']) ? $validated['icon'] : 'fa-solid fa-lightbulb',
            'order'       => $validated['order'] ?? 0,
            'is_active'   => $request->has('is_active') ? (bool) $request->is_active : false,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Inovasi website berhasil diperbarui.',
                'data'    => $innovation,
            ]);
        }

        return redirect()->back()->with('success', 'Inovasi website berhasil diperbarui.');
    }

    /**
     * Hapus inovasi.
     */
    public function destroy(Request $request, $id)
    {
        $this->authorizeAdmin();

        $innovation = Innovation::findOrFail($id);
        $innovation->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Inovasi website berhasil dihapus.',
            ]);
        }

        return redirect()->back()->with('success', 'Inovasi website berhasil dihapus.');
    }

    /**
     * Toggle status aktif/nonaktif.
     */
    public function toggleActive(Request $request, $id)
    {
        $this->authorizeAdmin();

        $innovation = Innovation::findOrFail($id);
        $innovation->is_active = !$innovation->is_active;
        $innovation->save();

        return response()->json([
            'success'   => true,
            'is_active' => $innovation->is_active,
            'message'   => 'Status inovasi berhasil diubah ke ' . ($innovation->is_active ? 'Aktif' : 'Nonaktif') . '.',
        ]);
    }
}
