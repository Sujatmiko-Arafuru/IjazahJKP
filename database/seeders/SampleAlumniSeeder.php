<?php

namespace Database\Seeders;

use App\Models\Alumni;
use App\Models\Dokumen;
use App\Models\Ijazah;
use App\Models\Pengembalian;
use Illuminate\Database\Seeder;

class SampleAlumniSeeder extends Seeder
{
    public function run(): void
    {
        $sampleData = [
            [
                'nama' => 'I Putu Gede Artawan',
                'nim' => 'P07124219001',
                'nik' => '5103011205990001',
                'tempat_lahir' => 'Denpasar',
                'tanggal_lahir' => '1999-05-12',
                'jenis_kelamin' => 'Laki-laki',
                'program_studi' => 'D4 Kebidanan',
                'jurusan' => 'Kebidanan',
                'tahun_masuk' => 2019,
                'tahun_lulus' => 2023,
                'email' => 'putuartawan@gmail.com',
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Sudirman No. 12 Denpasar, Bali',
                'status_verifikasi' => 'Sudah Diverifikasi',
            ],
            [
                'nama' => 'Ni Made Rai Saraswati',
                'nim' => 'P07120219015',
                'nik' => '5104024810000002',
                'tempat_lahir' => 'Gianyar',
                'tanggal_lahir' => '2000-10-18',
                'jenis_kelamin' => 'Perempuan',
                'program_studi' => 'D3 Keperawatan',
                'jurusan' => 'Keperawatan',
                'tahun_masuk' => 2019,
                'tahun_lulus' => 2022,
                'email' => 'saraswati.made@gmail.com',
                'no_hp' => '082145678901',
                'alamat' => 'Jl. Raya Ubud No. 45 Gianyar, Bali',
                'status_verifikasi' => 'Sudah Diverifikasi',
            ]
        ];

        foreach ($sampleData as $data) {
            $alumni = Alumni::updateOrCreate(['nim' => $data['nim']], $data);

            Dokumen::firstOrCreate(['alumni_id' => $alumni->id], [
                'pas_foto' => 'pas_foto/sample.jpg',
                'surat_bebas_pustaka' => 'surat_bebas_pustaka/sample.pdf',
                'tracer_kemenkes' => 'tracer_kemenkes/sample.pdf',
                'tracer_poltekkes' => 'tracer_poltekkes/sample.pdf',
            ]);

            Ijazah::firstOrCreate(['alumni_id' => $alumni->id], [
                'status' => 'Siap Diambil',
                'tanggal_siap' => now(),
                'lokasi_pengambilan' => 'Gedung Direktorat Poltekkes Denpasar',
                'jam_operasional' => '08:00 - 15:00 WITA',
            ]);

            Pengembalian::firstOrCreate(['alumni_id' => $alumni->id]);
        }
    }
}
