<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\CandidateProgress;
use App\Models\Document;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        if ($request->user()?->role === 'candidate' && $request->user()?->candidate) {
            return redirect()->route('candidates.show', $request->user()->candidate);
        }

        Gate::authorize('access-dashboard');

        $totalCandidates = Candidate::count();
        $documentsCount = Document::count();
        $medicalPending = CandidateProgress::where('medical_status', 'not_yet')->count();
        $visaStatuses = CandidateProgress::selectRaw('visa_status, COUNT(*) as total')
            ->groupBy('visa_status')
            ->pluck('total', 'visa_status');

        $recentCandidates = Candidate::with('progress')
            ->latest()
            ->limit(5)
            ->get();

        $upcomingDepartures = CandidateProgress::with('candidate')
            ->whereDate('departure_date', '>=', now()->startOfDay())
            ->orderBy('departure_date')
            ->limit(5)
            ->get();

        return view('dashboard', [
            'totalCandidates' => $totalCandidates,
            'documentsCount' => $documentsCount,
            'medicalPending' => $medicalPending,
            'visaStatuses' => $visaStatuses,
            'recentCandidates' => $recentCandidates,
            'upcomingDepartures' => $upcomingDepartures,
        ]);
    }
}
