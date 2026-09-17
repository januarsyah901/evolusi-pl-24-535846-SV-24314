<?php

namespace Tests\Feature;

use App\Models\KasusHukum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasusHukumTest extends TestCase
{
    use RefreshDatabase;

    public function test_dapat_mengakses_endpoint_root(): void
    {
        $response = $this->get('/');

        $response->assertStatus(500) // Sengaja digagalkan untuk membuktikan pipeline berhenti merah (Slide 17)
            ->assertJson([
                'status' => 'active',
            ]);
    }

    public function test_dapat_menampilkan_daftar_kasus_hukum(): void
    {
        KasusHukum::create([
            'nomor_kasus' => 'KASUS-001',
            'judul' => 'Dugaan Tipikor Pengadaan Server',
            'kategori' => 'Korupsi',
            'status' => 'Penyidikan',
            'keterangan' => 'Penyidikan oleh Kejaksaan Negeri',
        ]);

        $response = $this->getJson('/api/kasus');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'nomor_kasus',
                        'judul',
                        'kategori',
                        'status',
                        'keterangan',
                    ],
                ],
            ]);
    }

    public function test_dapat_menambahkan_kasus_hukum_baru(): void
    {
        $payload = [
            'nomor_kasus' => 'KASUS-002',
            'judul' => 'Sengketa Lahan Kawasan Hutan Lindung',
            'kategori' => 'Agraria & Lingkungan',
            'status' => 'Persidangan',
            'keterangan' => 'Gugatan perdata di Pengadilan Negeri',
        ];

        $response = $this->postJson('/api/kasus', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Kasus hukum berhasil ditambahkan',
                'data' => [
                    'nomor_kasus' => 'KASUS-002',
                    'judul' => 'Sengketa Lahan Kawasan Hutan Lindung',
                ],
            ]);

        $this->assertDatabaseHas('kasus_hukums', [
            'nomor_kasus' => 'KASUS-002',
        ]);
    }

    public function test_validasi_menolak_kasus_tanpa_judul(): void
    {
        $payload = [
            'nomor_kasus' => 'KASUS-003',
            'kategori' => 'Pidana Umum',
        ];

        $response = $this->postJson('/api/kasus', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['judul']);
    }

    public function test_dapat_melihat_detail_kasus_hukum(): void
    {
        $kasus = KasusHukum::create([
            'nomor_kasus' => 'KASUS-004',
            'judul' => 'Kasus Penipuan Investasi Bodong',
            'kategori' => 'Finansial',
            'status' => 'Penuntutan',
        ]);

        $response = $this->getJson("/api/kasus/{$kasus->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'nomor_kasus' => 'KASUS-004',
                ],
            ]);
    }

    public function test_dapat_memperbarui_kasus_hukum(): void
    {
        $kasus = KasusHukum::create([
            'nomor_kasus' => 'KASUS-005',
            'judul' => 'Kasus Peretasan Data Nasabah',
            'kategori' => 'Cyber Crime',
            'status' => 'Penyidikan',
        ]);

        $response = $this->putJson("/api/kasus/{$kasus->id}", [
            'status' => 'Putusan',
            'keterangan' => 'Vonis 4 tahun penjara',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Kasus hukum berhasil diperbarui',
            ]);

        $this->assertDatabaseHas('kasus_hukums', [
            'id' => $kasus->id,
            'status' => 'Putusan',
        ]);
    }

    public function test_dapat_menghapus_kasus_hukum(): void
    {
        $kasus = KasusHukum::create([
            'nomor_kasus' => 'KASUS-006',
            'judul' => 'Kasus Pencemaran Limbah Pabrik',
            'kategori' => 'Lingkungan Hidup',
        ]);

        $response = $this->deleteJson("/api/kasus/{$kasus->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Kasus hukum berhasil dihapus',
            ]);

        $this->assertDatabaseMissing('kasus_hukums', [
            'id' => $kasus->id,
        ]);
    }
}
