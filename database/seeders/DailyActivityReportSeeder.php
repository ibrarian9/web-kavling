<?php

namespace Database\Seeders;

use App\Models\DailyActivityReport;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;

class DailyActivityReportSeeder extends Seeder
{
    public function run(): void
    {
        $marketingUsers = User::whereIn('role', ['marketing', 'founder', 'supervisor'])->get();
        if ($marketingUsers->isEmpty()) {
            return;
        }

        $founder = User::where('role', 'founder')->first() ?? $marketingUsers->first();
        $projects = Project::where('status', 'aktif')->get();
        $units = Unit::all();

        $sampleReports = [
            [
                'client_name' => 'Bpk. Hendra Wijaya',
                'client_phone' => '081234567890',
                'lead_source' => 'facebook_ads',
                'interaction_type' => 'survey_lokasi',
                'lead_stage' => 'hot_deal',
                'payment_type' => 'dp_booking',
                'deal_amount' => 15000000,
                'notes' => 'Sangat tertarik unit posisi hook, janji transfer sisa DP minggu depan setelah terima gajian.',
                'follow_up_date' => now()->addDays(3)->toDateString(),
            ],
            [
                'client_name' => 'Ibu Ratna Pertiwi',
                'client_phone' => '081987654321',
                'lead_source' => 'instagram',
                'interaction_type' => 'chat_wa',
                'lead_stage' => 'warm',
                'payment_type' => 'tanpa_dp',
                'deal_amount' => 0,
                'notes' => 'Meminta brosur digital & daftar harga tipe 36. Rencana mau ajak suami survey lokasi akhir pekan.',
                'follow_up_date' => now()->addDays(5)->toDateString(),
            ],
            [
                'client_name' => 'Bpk. H. Ahmad Subagyo',
                'client_phone' => '081333444555',
                'lead_source' => 'walk_in',
                'interaction_type' => 'cash_lunas',
                'lead_stage' => 'cash_lunas',
                'payment_type' => 'cash_lunas',
                'deal_amount' => 185000000,
                'notes' => 'Pembelian cash keras 1 unit kavling proyek utama. Berkas SPP & kuitansi pelunasan sudah diterbitkan.',
                'follow_up_date' => null,
            ],
            [
                'client_name' => 'Ibu Maya Rosdiana',
                'client_phone' => '085211223344',
                'lead_source' => 'tiktok',
                'interaction_type' => 'telepon',
                'lead_stage' => 'cold',
                'payment_type' => 'tanpa_dp',
                'deal_amount' => 0,
                'notes' => 'Menanyakan skema angsuran syariah tanpa bank. Masih mempertimbangkan jarak dengan tempat kerja.',
                'follow_up_date' => now()->addDays(7)->toDateString(),
            ],
            [
                'client_name' => 'Bpk. Dedi Kurniawan',
                'client_phone' => '087788990011',
                'lead_source' => 'referral',
                'interaction_type' => 'booking_dp',
                'lead_stage' => 'booking',
                'payment_type' => 'dp_booking',
                'deal_amount' => 5000000,
                'notes' => 'Booking fee unit kavling atas rekomendasi Bpk. Budi. Struk booking fee sudah dicatat di sistem.',
                'follow_up_date' => now()->addDays(4)->toDateString(),
            ],
            [
                'client_name' => 'Bpk. Rahmat Hidayat',
                'client_phone' => '081299887766',
                'lead_source' => 'canvassing',
                'interaction_type' => 'presentasi',
                'lead_stage' => 'warm',
                'payment_type' => 'tanpa_dp',
                'deal_amount' => 0,
                'notes' => 'Follow up hasil sebar brosur di pameran. Tertarik investasi kavling untuk masa depan anak.',
                'follow_up_date' => now()->addDays(2)->toDateString(),
            ],
            [
                'client_name' => 'Ibu Hj. Dewi Lestari',
                'client_phone' => '081377665544',
                'lead_source' => 'banner_brosur',
                'interaction_type' => 'survey_lokasi',
                'lead_stage' => 'hot_deal',
                'payment_type' => 'cash_bertahap',
                'deal_amount' => 50000000,
                'notes' => 'Suka lokasi kavling dekat fasilitas masjid. Negosiasi skema cicilan bertahap 24 bulan.',
                'follow_up_date' => now()->addDays(1)->toDateString(),
            ],
            [
                'client_name' => 'Bpk. Tri Wahyudi',
                'client_phone' => '081255443322',
                'lead_source' => 'whatsapp',
                'interaction_type' => 'chat_wa',
                'lead_stage' => 'batal',
                'payment_type' => 'tanpa_dp',
                'deal_amount' => 0,
                'notes' => 'Batal melanjutkan karena mencari lokasi di daerah lain yang lebih dekat ke rumah orang tua.',
                'follow_up_date' => null,
            ],
        ];

        foreach ($sampleReports as $index => $data) {
            $user = $marketingUsers->get($index % $marketingUsers->count()) ?? $founder;
            $project = $projects->get($index % max(1, $projects->count()));
            $unit = $units->where('project_id', $project?->id ?? 0)->first() ?? $units->first();

            DailyActivityReport::create([
                'user_id' => $user->id,
                'project_id' => $project?->id,
                'unit_id' => $unit?->id,
                'report_date' => now()->subDays($index % 5)->toDateString(),
                'client_name' => $data['client_name'],
                'client_phone' => $data['client_phone'],
                'lead_source' => $data['lead_source'],
                'interaction_type' => $data['interaction_type'],
                'lead_stage' => $data['lead_stage'],
                'payment_type' => $data['payment_type'],
                'deal_amount' => $data['deal_amount'],
                'notes' => $data['notes'],
                'follow_up_date' => $data['follow_up_date'],
            ]);
        }
    }
}
