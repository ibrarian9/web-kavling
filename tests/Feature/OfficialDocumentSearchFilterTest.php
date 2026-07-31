<?php

namespace Tests\Feature;

use App\Livewire\Documents\Index;
use App\Models\OfficialDocument;
use App\Models\PriceProposal;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class OfficialDocumentSearchFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_document_index_filters_by_project_and_search_keyword()
    {
        $user = User::factory()->create(['role' => 'marketing']);

        $projectA = Project::create([
            'name' => 'Kavling Merapi Asri',
            'location' => 'Sleman',
            'land_purchase_price' => 100000000,
            'standard_land_area' => 100,
            'excess_price_per_sqm' => 500000,
            'base_price' => 120000000,
            'created_by' => $user->id,
        ]);

        $projectB = Project::create([
            'name' => 'Perumahan Sinar Indah',
            'location' => 'Bantul',
            'land_purchase_price' => 150000000,
            'standard_land_area' => 96,
            'excess_price_per_sqm' => 600000,
            'base_price' => 180000000,
            'created_by' => $user->id,
        ]);

        $unitA = Unit::create([
            'project_id' => $projectA->id,
            'code' => 'KAVL-A01',
            'category' => 'kavling',
            'status' => 'terjual',
            'land_length' => 10,
            'land_width' => 10,
            'land_area' => 100,
            'hpp' => 100000000,
            'final_selling_price' => 120000000,
            'created_by' => $user->id,
        ]);

        $unitB = Unit::create([
            'project_id' => $projectB->id,
            'code' => 'RUM-B05',
            'category' => 'rumah',
            'status' => 'terjual',
            'land_length' => 12,
            'land_width' => 8,
            'land_area' => 96,
            'hpp' => 200000000,
            'final_selling_price' => 250000000,
            'created_by' => $user->id,
        ]);

        $proposalA = PriceProposal::create([
            'unit_id' => $unitA->id,
            'hpp_price' => 100000000,
            'proposed_price' => 120000000,
            'margin' => 20000000,
            'proposed_by' => $user->id,
            'status' => 'disetujui',
        ]);

        $proposalB = PriceProposal::create([
            'unit_id' => $unitB->id,
            'hpp_price' => 200000000,
            'proposed_price' => 250000000,
            'margin' => 50000000,
            'proposed_by' => $user->id,
            'status' => 'disetujui',
        ]);

        $docA = OfficialDocument::create([
            'unit_id' => $unitA->id,
            'price_proposal_id' => $proposalA->id,
            'document_number' => 'SPP/MERAPI/2026/001',
            'buyer_name' => 'Budi Santoso',
            'buyer_contact' => '08123456789',
            'buyer_address' => 'Yogyakarta',
            'issued_by' => $user->id,
            'issued_at' => now(),
        ]);

        $docB = OfficialDocument::create([
            'unit_id' => $unitB->id,
            'price_proposal_id' => $proposalB->id,
            'document_number' => 'SPP/SINAR/2026/002',
            'buyer_name' => 'Siti Rahma',
            'buyer_contact' => '08987654321',
            'buyer_address' => 'Bantul',
            'issued_by' => $user->id,
            'issued_at' => now(),
        ]);

        Livewire::actingAs($user)
            ->test(Index::class)
            ->assertSee('SPP/MERAPI/2026/001')
            ->assertSee('SPP/SINAR/2026/002')
            ->set('project_id', $projectA->id)
            ->assertSee('SPP/MERAPI/2026/001')
            ->assertDontSee('SPP/SINAR/2026/002')
            ->set('project_id', null)
            ->set('search', 'Siti Rahma')
            ->assertDontSee('SPP/MERAPI/2026/001')
            ->assertSee('SPP/SINAR/2026/002');
    }
}
