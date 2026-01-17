<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentRequest;
use App\Models\Candidate;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class DocumentController extends Controller
{
    public function store(DocumentRequest $request, Candidate $candidate): RedirectResponse
    {
        Gate::authorize('manage-candidates');

        $path = $request->file('file')->store("documents/{$candidate->id}", 'public');

        $candidate->documents()->create([
            'type' => $request->input('type'),
            'file_path' => $path,
            'uploaded_at' => now(),
        ]);

        return redirect()
            ->route('candidates.show', $candidate)
            ->with('status', 'Document uploaded successfully.');
    }

    public function destroy(Candidate $candidate, Document $document): RedirectResponse
    {
        Gate::authorize('manage-candidates');

        abort_unless($document->candidate_id === $candidate->id, 404);

        $document->delete();

        return redirect()
            ->route('candidates.show', $candidate)
            ->with('status', 'Document deleted.');
    }
}
