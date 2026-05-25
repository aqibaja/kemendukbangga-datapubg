<?php

namespace App\Http\Controllers;

use App\Models\LaporanCapaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanCapaianController extends Controller
{
    /**
     * Store a new laporan capaian.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipe'  => 'required|in:pengendalian_lapangan,capaian_program,elsimil',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:2099',
        ]);

        // Check if already exists
        $exists = LaporanCapaian::where('tipe', $request->tipe)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Data untuk tipe, bulan, dan tahun tersebut sudah ada. Silakan edit data yang sudah ada.');
        }

        $data = $this->assembleData($request);
        $judul = $this->generateJudul($request->tipe, $request->bulan, $request->tahun);

        LaporanCapaian::create([
            'tipe'       => $request->tipe,
            'judul'      => $judul,
            'bulan'      => $request->bulan,
            'tahun'      => $request->tahun,
            'data'       => $data,
            'dibuat_oleh' => Auth::id(),
        ]);

        return redirect('/user')->with('success', 'Laporan Capaian berhasil ditambahkan!');
    }

    /**
     * Update an existing laporan capaian.
     */
    public function update(Request $request, $id)
    {
        $laporan = LaporanCapaian::findOrFail($id);

        $request->validate([
            'tipe'  => 'required|in:pengendalian_lapangan,capaian_program,elsimil',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:2099',
        ]);

        $data = $this->assembleData($request);
        $judul = $this->generateJudul($request->tipe, $request->bulan, $request->tahun);

        $laporan->update([
            'tipe'  => $request->tipe,
            'judul' => $judul,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'data'  => $data,
        ]);

        return redirect('/user')->with('success', 'Laporan Capaian berhasil diperbarui!');
    }

    /**
     * Delete a laporan capaian.
     */
    public function destroy($id)
    {
        $laporan = LaporanCapaian::findOrFail($id);
        $laporan->delete();

        return response()->json(['success' => true, 'message' => 'Laporan berhasil dihapus']);
    }

    /**
     * Show edit form with existing data.
     */
    public function edit($id)
    {
        if (!Auth::check()) {
            abort(404);
        }

        $laporan = LaporanCapaian::findOrFail($id);

        return view('laporan-capaian-input', [
            'title'    => 'Edit Laporan Capaian',
            'laporan'  => $laporan,
            'editMode' => true,
        ]);
    }

    /**
     * Generate judul based on type and period.
     */
    private function generateJudul($tipe, $bulan, $tahun)
    {
        $namaBulan = LaporanCapaian::namaBulan($bulan);

        return match ($tipe) {
            'pengendalian_lapangan' => "Capaian Pengendalian Lapangan KemendukBangga BKKBN Prov Aceh Bulan {$namaBulan} Tahun {$tahun}",
            'capaian_program'      => "Laporan Capaian Program Kemendukbangga / BKKBN Prov Aceh {$namaBulan} Tahun {$tahun}",
            'elsimil'              => "Laporan Capaian ELSIMIL Kemendukbangga BKKBN Prov Aceh Hingga {$namaBulan} {$tahun}",
            default                => "Laporan Capaian {$namaBulan} {$tahun}",
        };
    }

    /**
     * Assemble the JSON data from request based on tipe.
     */
    private function assembleData(Request $request)
    {
        return match ($request->tipe) {
            'pengendalian_lapangan' => $this->assemblePengendalianLapangan($request),
            'capaian_program'      => $this->assembleCapaianProgram($request),
            'elsimil'              => $this->assembleElsimil($request),
            default                => [],
        };
    }

    private function assemblePengendalianLapangan(Request $request)
    {
        return [
            'bkb' => [
                'cakupan_laporan' => [
                    'ada'        => $this->num($request->bkb_cakupan_ada),
                    'lapor'      => $this->num($request->bkb_cakupan_lapor),
                    'persentase' => $this->dec($request->bkb_cakupan_persen),
                ],
                'anak_hadir_kka' => [
                    'hadir'            => $this->num($request->bkb_kka_hadir),
                    'menggunakan_kka'  => $this->num($request->bkb_kka_menggunakan),
                    'persentase'       => $this->dec($request->bkb_kka_persen),
                ],
                'keluarga_ikut_bkb' => [
                    'target'     => $this->num($request->bkb_keluarga_target),
                    'capaian'    => $this->num($request->bkb_keluarga_capaian),
                    'persentase' => $this->dec($request->bkb_keluarga_persen),
                ],
                'pembinaan_baduta' => [
                    'target'     => $this->num($request->bkb_baduta_target),
                    'capaian'    => $this->num($request->bkb_baduta_capaian),
                    'persentase' => $this->dec($request->bkb_baduta_persen),
                ],
            ],
            'bkr' => [
                'cakupan_laporan' => [
                    'ada'        => $this->num($request->bkr_cakupan_ada),
                    'lapor'      => $this->num($request->bkr_cakupan_lapor),
                    'persentase' => $this->dec($request->bkr_cakupan_persen),
                ],
                'anggota_hadir' => [
                    'jumlah'     => $this->num($request->bkr_anggota_jumlah),
                    'hadir'      => $this->num($request->bkr_anggota_hadir),
                    'persentase' => $this->dec($request->bkr_anggota_persen),
                ],
            ],
            'bkl' => [
                'cakupan_laporan' => [
                    'ada'        => $this->num($request->bkl_cakupan_ada),
                    'lapor'      => $this->num($request->bkl_cakupan_lapor),
                    'persentase' => $this->dec($request->bkl_cakupan_persen),
                ],
                'anggota_hadir' => [
                    'jumlah_keluarga' => $this->num($request->bkl_anggota_jumlah_keluarga),
                    'keluarga_hadir'  => $this->num($request->bkl_anggota_keluarga_hadir),
                    'lansia_hadir'    => $this->num($request->bkl_anggota_lansia_hadir),
                    'total_hadir'     => $this->num($request->bkl_anggota_total_hadir),
                    'persentase'      => $this->dec($request->bkl_anggota_persen),
                ],
            ],
            'pikr' => [
                'cakupan_laporan' => [
                    'ada'        => $this->num($request->pikr_cakupan_ada),
                    'lapor'      => $this->num($request->pikr_cakupan_lapor),
                    'persentase' => $this->dec($request->pikr_cakupan_persen),
                ],
                'anggota_hadir' => [
                    'jumlah_remaja' => $this->num($request->pikr_anggota_jumlah_remaja),
                    'target'        => $this->num($request->pikr_anggota_target),
                    'hadir'         => $this->num($request->pikr_anggota_hadir),
                    'persentase'    => $this->dec($request->pikr_anggota_persen),
                ],
            ],
            'uppka' => [
                'cakupan_laporan' => [
                    'ada'        => $this->num($request->uppka_cakupan_ada),
                    'lapor'      => $this->num($request->uppka_cakupan_lapor),
                    'persentase' => $this->dec($request->uppka_cakupan_persen),
                ],
                'anggota_hadir' => [
                    'jumlah_keluarga' => $this->num($request->uppka_anggota_jumlah),
                    'hadir'           => $this->num($request->uppka_anggota_hadir),
                    'persentase'      => $this->dec($request->uppka_anggota_persen),
                ],
            ],
            'ppks' => [
                'cakupan_laporan' => [
                    'ada'        => $this->num($request->ppks_cakupan_ada),
                    'lapor'      => $this->num($request->ppks_cakupan_lapor),
                    'persentase' => $this->dec($request->ppks_cakupan_persen),
                ],
            ],
        ];
    }

    private function assembleCapaianProgram(Request $request)
    {
        return [
            'cakupan_fasyankes' => [
                'pemerintah'  => ['ada' => $this->num($request->fasy_pemerintah_ada), 'lapor' => $this->num($request->fasy_pemerintah_lapor), 'persentase' => $this->dec($request->fasy_pemerintah_persen)],
                'jaringan'    => ['ada' => $this->num($request->fasy_jaringan_ada), 'lapor' => $this->num($request->fasy_jaringan_lapor), 'persentase' => $this->dec($request->fasy_jaringan_persen)],
                'swasta'      => ['ada' => $this->num($request->fasy_swasta_ada), 'lapor' => $this->num($request->fasy_swasta_lapor), 'persentase' => $this->dec($request->fasy_swasta_persen)],
                'pmb_setara'  => ['ada' => $this->num($request->fasy_pmb_setara_ada), 'lapor' => $this->num($request->fasy_pmb_setara_lapor), 'persentase' => $this->dec($request->fasy_pmb_setara_persen)],
                'pmb_jejaring' => ['ada' => $this->num($request->fasy_pmb_jejaring_ada), 'lapor' => $this->num($request->fasy_pmb_jejaring_lapor), 'persentase' => $this->dec($request->fasy_pmb_jejaring_persen)],
            ],
            'stock_opname' => [
                'gudang_provinsi'  => ['ada' => $this->num($request->stock_provinsi_ada), 'laporan' => $this->num($request->stock_provinsi_laporan), 'persentase' => $this->dec($request->stock_provinsi_persen)],
                'gudang_kabkota'   => ['ada' => $this->num($request->stock_kabkota_ada), 'laporan' => $this->num($request->stock_kabkota_laporan), 'persentase' => $this->dec($request->stock_kabkota_persen)],
                'gudang_fasyankes' => ['ada' => $this->num($request->stock_fasyankes_ada), 'laporan' => $this->num($request->stock_fasyankes_laporan), 'persentase' => $this->dec($request->stock_fasyankes_persen)],
            ],
            'kb_baru' => [
                'pb'                  => ['ppm' => $this->num($request->kb_pb_ppm), 'capaian' => $this->num($request->kb_pb_capaian), 'persentase' => $this->dec($request->kb_pb_persen)],
                'pb_pasca_persalinan' => ['ppm' => $this->num($request->kb_pasca_ppm), 'capaian' => $this->num($request->kb_pasca_capaian), 'persentase' => $this->dec($request->kb_pasca_persen)],
                'pb_mkjp'             => ['ppm' => $this->num($request->kb_mkjp_ppm), 'capaian' => $this->num($request->kb_mkjp_capaian), 'persentase' => $this->dec($request->kb_mkjp_persen)],
                'pb_non_mkjp'         => ['ppm' => $this->num($request->kb_nonmkjp_ppm), 'capaian' => $this->num($request->kb_nonmkjp_capaian), 'persentase' => $this->dec($request->kb_nonmkjp_persen)],
            ],
            'kb_aktif' => [
                'pa_mkjp'         => ['ppm' => $this->num($request->pa_mkjp_ppm), 'capaian' => $this->num($request->pa_mkjp_capaian), 'persentase' => $this->dec($request->pa_mkjp_persen)],
                'pa_non_mkjp'     => ['ppm' => $this->num($request->pa_nonmkjp_ppm), 'capaian' => $this->num($request->pa_nonmkjp_capaian), 'persentase' => $this->dec($request->pa_nonmkjp_persen)],
                'pa_modern'       => ['ppm' => $this->num($request->pa_modern_ppm), 'capaian' => $this->num($request->pa_modern_capaian), 'persentase' => $this->dec($request->pa_modern_persen)],
                'pa_tradisional'  => $this->num($request->pa_tradisional),
                'pa_keseluruhan'  => $this->num($request->pa_keseluruhan),
            ],
            'mcpr_unmet' => [
                'mcpr'       => ['pus' => $this->num($request->mcpr_pus), 'pa_modern' => $this->num($request->mcpr_pa_modern), 'persentase' => $this->dec($request->mcpr_persen)],
                'unmet_need' => ['pus' => $this->num($request->unmet_pus), 'un' => $this->num($request->unmet_un), 'persentase' => $this->dec($request->unmet_persen)],
            ],
        ];
    }

    private function assembleElsimil(Request $request)
    {
        $catin = [];
        $bumil = [];

        for ($i = 1; $i <= 12; $i++) {
            $catinVal = $request->input("elsimil_catin_{$i}");
            $bumilVal = $request->input("elsimil_bumil_{$i}");

            if ($catinVal !== null && $catinVal !== '') {
                $catin[(string)$i] = $this->num($catinVal);
            }
            if ($bumilVal !== null && $bumilVal !== '') {
                $bumil[(string)$i] = $this->num($bumilVal);
            }
        }

        return [
            'catin' => $catin,
            'bumil' => $bumil,
        ];
    }

    private function num($val)
    {
        if ($val === null || $val === '') return 0;
        return (int) str_replace(['.', ','], ['', ''], $val);
    }

    private function dec($val)
    {
        if ($val === null || $val === '') return 0;
        return (float) str_replace(',', '.', $val);
    }
}
