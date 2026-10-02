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
        $response->assertSee('SIJITU');
        $response->assertSee('Sistem Ijazah Terpadu Jurusan Keperawatan');
    }

    /**
     * Test public registration multi-step submission is verified and stored.
     */
    public function test_alumni_registration_workflow()
    {
        Storage::fake('public');

        $pasFoto = UploadedFile::fake()->image('pasfoto.jpg')->size(500); // 500 KB (< 1 MB)
        $berkasPersyaratan = UploadedFile::fake()->create('berkas_persyaratan.pdf', 800, 'application/pdf'); // 800 KB (< 1 MB)

        $data = [
            'nama' => 'Nyoman Alumni Test',
            'nim' => 'P07133020001',
            'nik' => '5171012345678901',
            'tempat_lahir' => 'Denpasar',
            'tanggal_lahir' => '2001-05-12',
            'jenis_kelamin' => 'Laki-laki',
            'program_studi' => 'D3 Keperawatan',
            'jurusan' => 'Keperawatan',
            'tahun_masuk' => 2020,
            'tahun_lulus' => 2023,
            'email' => 'nyoman@test.com',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Sanitasi No.1, Sidakarya, Denpasar',
            'pas_foto' => $pasFoto,
            'berkas_persyaratan' => $berkasPersyaratan,
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
            'status' => 'Belum Diambil',
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
        $pasFoto = UploadedFile::fake()->image('pasfoto.jpg')->size(500);
        $berkasPersyaratan = UploadedFile::fake()->create('berkas_persyaratan.pdf', 800, 'application/pdf');

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
            'berkas_persyaratan' => $berkasPersyaratan,
        ];

        $response = $this->from(route('public.register'))->post(route('public.register.store'), $data);
        $response->assertSessionHasErrors('nim');
    }

    /**
     * Test max 1 MB size validation for pas_foto and berkas_persyaratan PDF.
     */
    public function test_files_exceeding_1mb_are_rejected()
    {
        Storage::fake('public');

        // pas_foto oversized (1.5 MB = 1536 KB)
        $oversizedPhoto = UploadedFile::fake()->image('pasfoto_big.jpg')->size(1536);
        // berkas_persyaratan oversized (2 MB = 2048 KB)
        $oversizedPdf = UploadedFile::fake()->create('berkas_big.pdf', 2048, 'application/pdf');

        $data = [
            'nama' => 'Testing Size',
            'nim' => 'P07133020999',
            'nik' => '5171012345678000',
            'tempat_lahir' => 'Denpasar',
            'tanggal_lahir' => '2001-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'program_studi' => 'D3 Keperawatan',
            'jurusan' => 'Keperawatan',
            'tahun_masuk' => 2020,
            'tahun_lulus' => 2023,
            'email' => 'size@test.com',
            'no_hp' => '081234567899',
            'alamat' => 'Denpasar',
            'pas_foto' => $oversizedPhoto,
            'berkas_persyaratan' => $oversizedPdf,
        ];

        $response = $this->from(route('public.register'))->post(route('public.register.store'), $data);
        $response->assertSessionHasErrors(['pas_foto', 'berkas_persyaratan']);
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
            'username' => 'AdminTest',
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
            'username' => 'AdminTest',
            'password' => 'password123',
        ]);
        $response->assertRedirect(route('admin.dashboard'));

        // Verify Alumnus
        $responseVerify = $this->actingAs($admin)
            ->from(route('admin.alumni.show', $alumni->id))
            ->post(route('admin.alumni.verify', $alumni->id), [
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

    /**
     * Test 3 permanent admins login with username and password.
     */
    public function test_three_permanent_admins_can_login()
    {
        $this->seed(\Database\Seeders\AdminSeeder::class);

        $accounts = [
            ['username' => 'AdminD3JKP', 'password' => 'D3JKP#123'],
            ['username' => 'AdminStrJKP', 'password' => 'StrJKP#123'],
            ['username' => 'AdminNersJKP', 'password' => 'NersJKP#123'],
        ];

        foreach ($accounts as $acc) {
            $response = $this->post(route('login.store'), [
                'username' => $acc['username'],
                'password' => $acc['password'],
            ]);
            $response->assertRedirect(route('admin.dashboard'));
            $this->assertAuthenticated();

            $this->post(route('admin.logout'));
            $this->assertGuest();
        }

        // Test invalid login
        $responseFailed = $this->from(route('login'))->post(route('login.store'), [
            'username' => 'AdminD3JKP',
            'password' => 'WrongPassword',
        ]);
        $responseFailed->assertRedirect(route('login'));
        $responseFailed->assertSessionHasErrors('username');
    }
}
