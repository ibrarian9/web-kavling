<?php

namespace App\Http\Controllers;

use App\Models\OfficialDocument;

class DocumentVerificationController extends Controller
{
    public function verify($id)
    {
        $doc = OfficialDocument::with(['unit.project', 'proposal.approvals.approver', 'issuer'])->findOrFail($id);

        return view('documents.verify_public', [
            'doc' => $doc,
            'unit' => $doc->unit,
            'project' => $doc->unit->project,
            'proposal' => $doc->proposal,
        ]);
    }
}
