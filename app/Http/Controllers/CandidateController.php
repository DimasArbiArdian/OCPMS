<?php

namespace App\Http\Controllers;

use App\Http\Requests\CandidateRequest;
use App\Models\Candidate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class CandidateController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        if ($request->user()?->role === 'candidate') {
            $candidateProfile = $request->user()->candidate;

            if (! $candidateProfile) {
                abort(403, 'Candidate profile not found.');
            }

            return redirect()->route('candidates.show', $candidateProfile);
        }

        Gate::authorize('manage-candidates');

        $filters = $request->only(['search', 'passport', 'position', 'visa_status', 'medical_status']);

        $candidates = Candidate::query()
            ->with('progress')
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($builder) use ($search) {
                    $builder
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($filters['passport'] ?? null, function ($query, $passport) {
                $query->where('passport_number', 'like', "%{$passport}%");
            })
            ->when($filters['position'] ?? null, function ($query, $position) {
                $query->where('position', $position);
            })
            ->when($filters['visa_status'] ?? null, function ($query, $status) {
                $query->whereHas('progress', fn ($progress) => $progress->where('visa_status', $status));
            })
            ->when($filters['medical_status'] ?? null, function ($query, $status) {
                $query->whereHas('progress', fn ($progress) => $progress->where('medical_status', $status));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $positions = Candidate::query()
            ->select('position')
            ->distinct()
            ->orderBy('position')
            ->pluck('position');

        return view('candidates.index', [
            'candidates' => $candidates,
            'filters' => $filters,
            'positions' => $positions,
        ]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('candidates.index');
    }

    public function store(CandidateRequest $request): RedirectResponse
    {
        Gate::authorize('manage-candidates');

        $data = $request->validated();

        $candidate = Candidate::create($this->candidatePayload($data));
        $candidate->progress()->create($this->progressPayload($data));

        return redirect()
            ->route('candidates.index')
            ->with('status', 'Candidate saved successfully.');
    }

    public function show(Candidate $candidate): View
    {
        Gate::authorize('view-candidate', $candidate);

        $candidate->load(['progress', 'documents']);

        return view('candidates.show', [
            'candidate' => $candidate,
        ]);
    }

    public function edit(Candidate $candidate): RedirectResponse
    {
        return redirect()->route('candidates.show', $candidate);
    }

    public function update(CandidateRequest $request, Candidate $candidate): RedirectResponse
    {
        Gate::authorize('manage-candidates');

        $data = $request->validated();

        $candidate->update($this->candidatePayload($data));
        $candidate->progress()->updateOrCreate(
            ['candidate_id' => $candidate->id],
            $this->progressPayload($data)
        );

        return redirect()
            ->route('candidates.show', $candidate)
            ->with('status', 'Candidate updated successfully.');
    }

    public function destroy(Candidate $candidate): RedirectResponse
    {
        Gate::authorize('manage-candidates');

        $candidate->documents->each->delete();
        $candidate->progress?->delete();
        $candidate->delete();

        return redirect()
            ->route('candidates.index')
            ->with('status', 'Candidate removed.');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function candidatePayload(array $data): array
    {
        return Arr::only($data, [
            'name',
            'position',
            'phone',
            'passport_number',
            'passport_expired',
            'country',
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function progressPayload(array $data): array
    {
        return Arr::only($data, [
            'dp_status',
            'medical_status',
            'visa_status',
            'ticket_status',
            'departure_date',
            'remarks',
        ]);
    }
}
