<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Alumni;
use App\Models\Dokumen;
use App\Models\Ijazah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AlumniTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test public welcome page load.
     */
    public function test_welcome_page_is_accessible()
    {
        $response = $this->get(route('public.home'));
        $response->assertStatus(200);
        $response->assertSee('Portal Layanan Alumni');
    }

    /**
     * Test public registration multi-step submission is verified and stored.
     */
    public function test_alumni_registration_workflow()
    {
        Storage::fake('public');

        $pasFoto = UploadedFile::fake()->image('pasfoto.jpg');
        $bebasPustaka = UploadedFile::fake()->create('pustaka.pdf', 500, 'application/pdf');
        $tracerKemenkes = UploadedFile::fake()->create('tracer_kemenkes.pdf', 500, 'application/pdf');
        $tracerPoltekkes = UploadedFile::fake()->create('tracer_poltekkes.pdf', 500, 'application/pdf');

        $data = [
            'nama' => 'Nyoman Alumni Test',
            'nim' => 'P07133020001',
            'nik' => '5171012345678901',
            'tempat_lahir' => 'Denpasar',
            'tanggal_lahir' => '2001-05-12',
            'jenis_kelamin' => 'Laki-laki',
            'program_studi' => 'D3 Kesehatan Lingkungan (Sanitasi)',
            'jurusan' => 'Kesehatan Lingkungan',
            'tahun_masuk' => 2020,
            'tahun_lulus' => 2023,
            'email' => 'nyoman@test.com',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Sanitasi No.1, Sidakarya, Denpasar',
            'pas_foto' => $pasFoto,
            'surat_bebas_pustaka' => $bebasPustaka,
            'tracer_kemenkes' => $tracerKemenkes,
            'tracer_poltekkes' => $tracerPoltekkes,
        ];

        $response = $this->post(route('public.register.store'), $data);

        // Verify database records
        $this->assertDatabaseHas('alumni', [
            'nama' => 'Nyoman Alumni Test',
            'nim' => 'P07133020001',
            'nik' => '5171012345678901',
            'status_verifikasi' => 'Belum Diverifikasi',
        ]);

        $alumni = Alumni::where('nim', 'P07133020001')->first();
        $this->assertNotNull($alumni);

        // Confirm automatic nomor_registrasi generation
        $this->assertStringStartsWith('ALM-', $alumni->nomor_registrasi);

        // Check document path database relation
        $this->assertDatabaseHas('dokumen', [
            'alumni_id' => $alumni->id,
        ]);

        // Check ijazah defaults
        $this->assertDatabaseHas('ijazah', [
            'alumni_id' => $alumni->id,
            'status' => 'Belum Siap',
        ]);

        // Redirect check
        $response->assertRedirect(route('public.register.sukses', $alumni->id));
    }

    /**
     * Test unique NIM validation.
     */
    public function test_nim_must_be_unique()
    {
        $alumni1 = Alumni::create([
            'nomor_registrasi' => 'ALM-2026-000001',
            'nama' => 'Alumni Pertama',
            'nim' => 'P07133020099',
            'nik' => '5171012345678999',
            'tempat_lahir' => 'Denpasar',
            'tanggal_lahir' => '2000-01-01',
            'jenis_kelamin' => 'Perempuan',
            'program_studi' => 'D3 Gizi',
            'jurusan' => 'Gizi',
            'tahun_masuk' => 2020,
            'tahun_lulus' => 2023,
            'email' => 'first@test.com',
            'no_hp' => '081234567899',
            'alamat' => 'Denpasar',
        ]);

        Storage::fake('public');
        $pasFoto = UploadedFile::fake()->image('pasfoto.jpg');
        $bebasPustaka = UploadedFile::fake()->create('pustaka.pdf', 500, 'application/pdf');
        $tracerKemenkes = UploadedFile::fake()->create('tracer_kemenkes.pdf', 500, 'application/pdf');
        $tracerPoltekkes = UploadedFile::fake()->create('tracer_poltekkes.pdf', 500, 'application/pdf');

        $data = [
            'nama' => 'Alumni Kedua',
            'nim' => 'P07133020099', // Duplicate NIM
            'nik' => '5171012345678123',
            'tempat_lahir' => 'Denpasar',
            'tanggal_lahir' => '2000-02-02',
            'jenis_kelamin' => 'Perempuan',
            'program_studi' => 'D3 Gizi',
            'jurusan' => 'Gizi',
            'tahun_masuk' => 2020,
            'tahun_lulus' => 2023,
            'email' => 'second@test.com',
            'no_hp' => '081234567811',
            'alamat' => 'Denpasar',
            'pas_foto' => $pasFoto,
            'surat_bebas_pustaka' => $bebasPustaka,
            'tracer_kemenkes' => $tracerKemenkes,
            'tracer_poltekkes' => $tracerPoltekkes,
        ];

        $response = $this->from(route('public.register'))->post(route('public.register.store'), $data);
        $response->assertSessionHasErrors('nim');
    }

    /**
     * Test admin authentication guard.
     */
    public function test_unauthenticated_cannot_access_dashboard()
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test admin login and verification flow.
     */
    public function test_admin_login_and_verify_alumni()
    {
        $admin = Admin::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
        ]);

        $alumni = Alumni::create([
            'nomor_registrasi' => 'ALM-2026-000002',
            'nama' => 'Alumni Unverified',
            'nim' => 'P07133020050',
            'nik' => '5171012345678888',
            'tempat_lahir' => 'Denpasar',
            'tanggal_lahir' => '2000-05-05',
            'jenis_kelamin' => 'Laki-laki',
            'program_studi' => 'D3 Gizi',
            'jurusan' => 'Gizi',
            'tahun_masuk' => 2020,
            'tahun_lulus' => 2023,
            'email' => 'unverified@test.com',
            'no_hp' => '081234567888',
            'alamat' => 'Denpasar',
        ]);

        // Login Admin
        $response = $this->post(route('login.store'), [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);
        $response->assertRedirect(route('admin.dashboard'));

        // Verify Alumnus
        $responseVerify = $this->actingAs($admin)->post(route('admin.alumni.verify', $alumni->id), [
            'status_verifikasi' => 'Sudah Diverifikasi',
            'catatan_admin' => 'Berkas lengkap dan sesuai.',
        ]);

        $responseVerify->assertRedirect(route('admin.alumni.show', $alumni->id));
        $this->assertDatabaseHas('alumni', [
            'id' => $alumni->id,
            'status_verifikasi' => 'Sudah Diverifikasi',
            'catatan_admin' => 'Berkas lengkap dan sesuai.',
        ]);
    }
}
