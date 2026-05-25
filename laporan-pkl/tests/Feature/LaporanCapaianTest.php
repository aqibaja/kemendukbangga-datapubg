<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\LaporanCapaian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanCapaianTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Role $adminRole;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        $this->adminRole = Role::create([
            'id' => 1,
            'nama_role' => 'admin_utama'
        ]);

        Role::create([
            'id' => 2,
            'nama_role' => 'user'
        ]);

        // Create admin user
        $this->admin = User::create([
            'nama' => 'Admin Test',
            'username' => 'admintest',
            'password' => bcrypt('password'),
            'id_role' => 1,
        ]);
    }

    /**
     * Test public can access laporan capaian page.
     */
    public function test_public_can_view_laporan_capaian_page(): void
    {
        $response = $this->get('/laporan-capaian');
        $response->assertStatus(200);
        $response->assertSee('LAPORAN CAPAIAN PROGRAM');
    }

    /**
     * Test guest cannot access input page.
     */
    public function test_guest_cannot_access_input_page(): void
    {
        $response = $this->get('/laporan-capaian/input');
        $response->assertRedirect('/login');
    }

    /**
     * Test admin can access input page.
     */
    public function test_admin_can_access_input_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/laporan-capaian/input');
        $response->assertStatus(200);
        $response->assertSee('Tambah Laporan Capaian');
    }

    /**
     * Test admin can store new laporan capaian.
     */
    public function test_admin_can_store_laporan_capaian(): void
    {
        $postData = [
            'tipe' => 'pengendalian_lapangan',
            'bulan' => 4,
            'tahun' => 2026,
            'bkb_cakupan_ada' => '5.682',
            'bkb_cakupan_lapor' => '5.580',
            'bkb_cakupan_persen' => '98,20',
            'bkb_kka_hadir' => '172.580',
            'bkb_kka_menggunakan' => '168.963',
            'bkb_kka_persen' => '97,90',
            'bkb_keluarga_target' => '188.201',
            'bkb_keluarga_capaian' => '147.653',
            'bkb_keluarga_persen' => '78,45',
            'bkb_baduta_target' => '225.307',
            'bkb_baduta_capaian' => '117.404',
            'bkb_baduta_persen' => '42,11',
            'bkr_cakupan_ada' => '3.814',
            'bkr_cakupan_lapor' => '3.736',
            'bkr_cakupan_persen' => '97,95',
            'bkr_anggota_jumlah' => '145.495',
            'bkr_anggota_hadir' => '127.276',
            'bkr_anggota_persen' => '87,48',
            'bkl_cakupan_ada' => '3.882',
            'bkl_cakupan_lapor' => '3.806',
            'bkl_cakupan_persen' => '98,04',
            'bkl_anggota_jumlah_keluarga' => '81.787',
            'bkl_anggota_keluarga_hadir' => '72.953',
            'bkl_anggota_lansia_hadir' => '37.813',
            'bkl_anggota_total_hadir' => '110.766',
            'bkl_anggota_persen' => '135,43',
            'pikr_cakupan_ada' => '2.025',
            'pikr_cakupan_lapor' => '1.936',
            'pikr_cakupan_persen' => '95,60',
            'pikr_anggota_jumlah_remaja' => '137.780',
            'pikr_anggota_target' => '13.778',
            'pikr_anggota_hadir' => '11.000',
            'pikr_anggota_persen' => '79,84',
            'uppka_cakupan_ada' => '1.000',
            'uppka_cakupan_lapor' => '950',
            'uppka_cakupan_persen' => '95,00',
            'uppka_anggota_jumlah' => '10.000',
            'uppka_anggota_hadir' => '8.500',
            'uppka_anggota_persen' => '85,00',
            'ppks_cakupan_ada' => '500',
            'ppks_cakupan_lapor' => '480',
            'ppks_cakupan_persen' => '96,00',
        ];

        $response = $this->actingAs($this->admin)->post('/laporan-capaian/store', $postData);

        $response->assertRedirect('/user');
        $this->assertDatabaseHas('laporan_capaian', [
            'tipe' => 'pengendalian_lapangan',
            'bulan' => 4,
            'tahun' => 2026,
            'dibuat_oleh' => $this->admin->id,
        ]);

        $laporan = LaporanCapaian::first();
        $this->assertNotNull($laporan);
        $this->assertEquals(5682, $laporan->data['bkb']['cakupan_laporan']['ada']);
        $this->assertEquals(5580, $laporan->data['bkb']['cakupan_laporan']['lapor']);
        $this->assertEquals(98.20, $laporan->data['bkb']['cakupan_laporan']['persentase']);
    }

    /**
     * Test admin can update reports.
     */
    public function test_admin_can_update_laporan_capaian(): void
    {
        $laporan = LaporanCapaian::create([
            'tipe' => 'elsimil',
            'judul' => 'Laporan Capaian ELSIMIL',
            'bulan' => 5,
            'tahun' => 2026,
            'data' => [
                'catin' => ['5' => 100],
                'bumil' => ['5' => 200]
            ],
            'dibuat_oleh' => $this->admin->id,
        ]);

        $updateData = [
            'tipe' => 'elsimil',
            'bulan' => 5,
            'tahun' => 2026,
            'elsimil_catin_5' => '150',
            'elsimil_bumil_5' => '250',
        ];

        $response = $this->actingAs($this->admin)->post("/laporan-capaian/{$laporan->id}/update", $updateData);

        $response->assertRedirect('/user');

        $laporan->refresh();
        $this->assertEquals(150, $laporan->data['catin']['5']);
        $this->assertEquals(250, $laporan->data['bumil']['5']);
    }

    /**
     * Test admin can delete reports.
     */
    public function test_admin_can_delete_laporan_capaian(): void
    {
        $laporan = LaporanCapaian::create([
            'tipe' => 'elsimil',
            'judul' => 'Laporan Capaian ELSIMIL',
            'bulan' => 5,
            'tahun' => 2026,
            'data' => [],
            'dibuat_oleh' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->delete("/laporan-capaian/{$laporan->id}");

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('laporan_capaian', [
            'id' => $laporan->id
        ]);
    }
}
