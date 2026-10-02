<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Alumni;
use App\Models\Dokumen;
use App\Models\Ijazah;
use App\Models\Pengembalian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecuritySqlInjectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create baseline admin
        Admin::create([
            'name' => 'Admin D3 Keperawatan',
            'username' => 'AdminD3JKP',
            'email' => 'admind3jkp@sijitu.local',
            'password' => bcrypt('D3JKP#123'),
        ]);

        // Create baseline alumni
        $alumni = Alumni::create([
            'nomor_registrasi' => 'ALM-2026-000001',
            'nama' => 'Ni Wayan Sari Dewi',
            'nim' => 'P07133020001',
            'nik' => '5171012345678901',
            'tempat_lahir' => 'Denpasar',
            'tanggal_lahir' => '2001-05-15',
            'jenis_kelamin' => 'Perempuan',
            'program_studi' => 'D3 Keperawatan',
            'jurusan' => 'Keperawatan',
            'tahun_masuk' => 2020,
            'tahun_lulus' => 2023,
            'email' => 'saridewi@test.com',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Sanitasi No. 1 Denpasar',
            'status_verifikasi' => 'Sudah Diverifikasi',
        ]);

        Dokumen::create([
            'alumni_id' => $alumni->id,
            'pas_foto' => 'pas_foto/test.jpg',
            'berkas_persyaratan' => 'berkas/persyaratan.pdf',
        ]);
        Pengembalian::create(['alumni_id' => $alumni->id]);
        Ijazah::create(['alumni_id' => $alumni->id, 'status' => 'Siap Diambil']);
    }

    /**
     * Test admin login against SQL Injection attempts.
     */
    public function test_admin_login_resists_sql_injection(): void
    {
        $payloads = [
            "' OR 1=1 --",
            "' OR '1'='1",
            "admin' --",
            "admin' #",
            "' OR ''='",
            "1' UNION SELECT 1, 'admin', 'pass' --",
            "AdminD3JKP' AND (SELECT 1 FROM (SELECT SLEEP(1))a)--",
        ];

        foreach ($payloads as $payload) {
            $response = $this->from(route('login'))->post(route('login.store'), [
                'username' => $payload,
                'password' => 'arbitrary_password',
            ]);

            // Must NOT log in, must redirect back with errors
            $response->assertRedirect(route('login'));
            $response->assertSessionHasErrors('username');
            $this->assertGuest();
        }
    }

    /**
     * Test public status checking against SQL injection in nomor_registrasi and nim.
     */
    public function test_public_status_search_resists_sql_injection(): void
    {
        $payloads = [
            "' OR '1'='1",
            "ALM-2026-000001' OR '1'='1",
            "'; DROP TABLE alumni; --",
            "' UNION SELECT * FROM admins --",
            "P07133020001' OR 1=1 --",
        ];

        foreach ($payloads as $payload) {
            // Search via nomor_registrasi
            $responseReg = $this->get(route('public.pengembalian', ['nomor_registrasi' => $payload]));
            $responseReg->assertStatus(200);

            // Search via nim & tanggal_lahir
            $responseNim = $this->get(route('public.pengembalian', [
                'nim' => $payload,
                'tanggal_lahir' => '2001-05-15',
            ]));
            $responseNim->assertStatus(200);
        }

        // Verify database integrity: alumni table and record still exist
        $this->assertDatabaseHas('alumni', [
            'nim' => 'P07133020001',
        ]);
    }

    /**
     * Test admin alumni list search & sort against SQL injection.
     */
    public function test_admin_alumni_search_and_sort_resists_sql_injection(): void
    {
        $admin = Admin::first();

        $injectionSorts = [
            "created_at; DROP TABLE alumni; --",
            "(SELECT 1 FROM (SELECT SLEEP(1))a)",
            "non_existent_column",
            "id DESC, (SELECT * FROM users)",
        ];

        foreach ($injectionSorts as $badSort) {
            $response = $this->actingAs($admin)->get(route('admin.alumni.index', [
                'sort' => $badSort,
                'order' => 'asc; --',
                'search' => "' OR '1'='1",
            ]));

            $response->assertStatus(200);
            $response->assertViewHas('alumniList');
        }

        // Table still intact
        $this->assertDatabaseCount('alumni', 1);
    }

    /**
     * Test admin ijazah list search & sort against SQL injection.
     */
    public function test_admin_ijazah_search_and_sort_resists_sql_injection(): void
    {
        $admin = Admin::first();

        $injectionSorts = [
            "created_at; DROP TABLE ijazah; --",
            "alumni.id) UNION SELECT 1--",
            "invalid_column_name",
        ];

        foreach ($injectionSorts as $badSort) {
            $response = $this->actingAs($admin)->get(route('admin.ijazah.index', [
                'sort' => $badSort,
                'order' => 'desc; --',
                'search' => "test' UNION SELECT 1,2,3--",
            ]));

            $response->assertStatus(200);
            $response->assertViewHas('ijazahList');
        }

        $this->assertDatabaseCount('ijazah', 1);
    }

    /**
     * Test registration validation prevents malformed SQL payload submissions.
     */
    public function test_registration_validation_blocks_sql_injection_in_restricted_fields(): void
    {
        $response = $this->post(route('public.register.store'), [
            'nama' => "Test Name' OR '1'='1",
            'nim' => "P07133020099' OR 1=1;--", // will fail or be treated as string
            'nik' => "5171' OR 1=1 --", // Must fail regex validation (16 digits)
            'tempat_lahir' => 'Denpasar',
            'tanggal_lahir' => "2001-01-01' OR 1=1 --", // Must fail date validation
            'jenis_kelamin' => 'Laki-laki',
            'program_studi' => 'D3 Keperawatan',
            'jurusan' => 'Keperawatan',
            'tahun_masuk' => 2020,
            'tahun_lulus' => 2023,
            'email' => "invalid-email' OR 1=1",
            'no_hp' => "081234' OR 1=1", // Must fail regex validation
            'alamat' => 'Alamat test',
        ]);

        $response->assertSessionHasErrors(['nik', 'tanggal_lahir', 'email', 'no_hp']);
        $this->assertDatabaseMissing('alumni', [
            'nim' => "P07133020099' OR 1=1;--",
        ]);
    }
}
