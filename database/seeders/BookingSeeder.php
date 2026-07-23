<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $marketing1 = User::where('email', 'marketing@kavling.com')->first();
        $marketing2 = User::where('email', 'marketing2@kavling.com')->first();

        $mkt1Id = $marketing1 ? $marketing1->id : 4;
        $mkt2Id = $marketing2 ? $marketing2->id : 5;

        // Units with status 'booked' or relevant for booking context
        $unitA09 = Unit::where('code', 'A-09')->first();
        $unitB07 = Unit::where('code', 'B-07')->first();
        $unitC06 = Unit::where('code', 'C-06')->first();
        $unitE04 = Unit::where('code', 'E-04')->first();

        $bookings = [
            [
                'id' => 1,
                'project_id' => 1,
                'unit_id' => $unitA09?->id,
                'buyer_name' => 'Bpk. Irfan Prasetya',
                'buyer_phone' => '081299887766',
                'booking_type' => 'unit',
                'booking_amount' => 5000000.00,
                'dp_amount' => 30000000.00,
                'booking_date' => now()->subDays(12)->toDateString(),
                'expiry_date' => now()->addDays(16)->toDateString(),
                'status' => 'active',
                'notes' => 'Tanda jadi Rp 5jt, rencana DP 30jt dibayarkan akhir bulan ini.',
                'created_by' => $mkt1Id,
            ],
            [
                'id' => 2,
                'project_id' => 2,
                'unit_id' => $unitB07?->id,
                'buyer_name' => 'Ibu Ratna Pertiwi',
                'buyer_phone' => '081122334455',
                'booking_type' => 'unit',
                'booking_amount' => 3000000.00,
                'dp_amount' => 20000000.00,
                'booking_date' => me_date(-5),
                'expiry_date' => me_date(25),
                'status' => 'active',
                'notes' => 'Booking unit B-07 Kavling Cluster Permata Hijau.',
                'created_by' => $mkt2Id,
            ],
            [
                'id' => 3,
                'project_id' => 3,
                'unit_id' => $unitC06?->id,
                'buyer_name' => 'Bpk. Bambang Pamungkas',
                'buyer_phone' => '085712345678',
                'booking_type' => 'unit',
                'booking_amount' => 5000000.00,
                'dp_amount' => 35000000.00,
                'booking_date' => me_date(-8),
                'expiry_date' => me_date(14),
                'status' => 'active',
                'notes' => 'Booking Griya Asri Residence C-06.',
                'created_by' => $mkt1Id,
            ],
            [
                'id' => 4,
                'project_id' => 5,
                'unit_id' => $unitE04?->id,
                'buyer_name' => 'Dr. H. Ahmad Dahlan',
                'buyer_phone' => '081377665544',
                'booking_type' => 'unit',
                'booking_amount' => 10000000.00,
                'dp_amount' => 70000000.00,
                'booking_date' => me_date(-3),
                'expiry_date' => me_date(27),
                'status' => 'active',
                'notes' => 'Booking Prime Kavling Cluster Mutiara Malioboro.',
                'created_by' => $mkt2Id,
            ],
            [
                'id' => 5,
                'project_id' => 3,
                'unit_id' => null,
                'buyer_name' => 'Koperasi Pegawai Yogyakarta',
                'buyer_phone' => '082188776655',
                'booking_type' => 'project',
                'booking_amount' => 15000000.00,
                'dp_amount' => 100000000.00,
                'booking_date' => me_date(-20),
                'expiry_date' => me_date(10),
                'status' => 'active',
                'notes' => 'Booking kolektif pengembangan blok C Griya Asri.',
                'created_by' => $mkt1Id,
            ],
        ];

        foreach ($bookings as $b) {
            Booking::updateOrCreate(['id' => $b['id']], $b);
        }
    }
}

function me_date(int $addDays): string {
    return now()->addDays($addDays)->toDateString();
}
