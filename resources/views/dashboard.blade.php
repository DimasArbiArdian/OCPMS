<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('app.dashboard.title') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-8 sm:px-6 lg:px-8">
            <div class="grid gap-6 md:grid-cols-3">
                <div class="rounded-lg bg-white p-6 shadow">
                    <p class="text-sm font-medium text-gray-500">{{ __('app.dashboard.total_candidates') }}</p>
                    <p class="mt-2 text-4xl font-semibold text-indigo-600">{{ $totalCandidates }}</p>
                </div>
                <div class="rounded-lg bg-white p-6 shadow">
                    <p class="text-sm font-medium text-gray-500">{{ __('app.dashboard.pending_medical') }}</p>
                    <p class="mt-2 text-4xl font-semibold text-amber-500">{{ $medicalPending }}</p>
                </div>
                <div class="rounded-lg bg-white p-6 shadow">
                    <p class="text-sm font-medium text-gray-500">{{ __('app.dashboard.documents_uploaded') }}</p>
                    <p class="mt-2 text-4xl font-semibold text-emerald-600">{{ $documentsCount }}</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-lg bg-white p-6 shadow">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">{{ __('app.dashboard.visa_distribution') }}</h3>
                        <span class="text-sm text-gray-500">{{ __('app.dashboard.summary_label') }}</span>
                    </div>
                    <div class="mx-auto w-full">
                        <canvas id="visaChart" class="w-full"></canvas>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-6 shadow">
                    <h3 class="text-lg font-semibold text-gray-800">{{ __('app.dashboard.upcoming_departures') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('app.dashboard.upcoming_hint') }}</p>
                    <ul class="mt-4 space-y-4">
                        @forelse ($upcomingDepartures as $progress)
                            <li class="flex items-center justify-between rounded border border-gray-100 p-3">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $progress->candidate->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $progress->candidate->position }} •
                                        {{ strtoupper($progress->candidate->country) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-700">
                                        {{ optional($progress->departure_date)->format('d M Y') ?? 'TBD' }}
                                    </p>
                                    <a href="{{ route('candidates.show', $progress->candidate) }}"
                                        class="text-xs text-indigo-600 hover:underline">{{ __('app.buttons.view') }}</a>
                                </div>
                            </li>
                        @empty
                            <p class="text-sm text-gray-500">{{ __('app.dashboard.messages.no_upcoming') }}</p>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="rounded-lg bg-white p-6 shadow">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ __('app.dashboard.recent_candidates') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('app.dashboard.recent_hint') }}</p>
                    </div>
                    <a href="{{ route('candidates.index') }}"
                        class="text-sm font-medium text-indigo-600 hover:underline">{{ __('app.dashboard.go_to_list') }}</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">{{ __('app.dashboard.table.candidate') }}</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">{{ __('app.dashboard.table.passport') }}</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">{{ __('app.dashboard.table.visa_status') }}</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">{{ __('app.dashboard.table.updated') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($recentCandidates as $candidate)
                                <tr>
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-gray-900">{{ $candidate->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $candidate->position }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">{{ $candidate->passport_number }}</td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                                            {{ str($candidate->progress->visa_status ?? 'Pending')->headline() }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500">
                                        {{ $candidate->updated_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">{{ __('app.dashboard.messages.no_candidates') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const visaCtx = document.getElementById('visaChart');
            const visaLabels = @json($visaStatuses->keys()->map(fn ($status) => \Illuminate\Support\Str::headline($status)));
            const visaData = @json($visaStatuses->values());

            new Chart(visaCtx, {
                type: 'doughnut',
                data: {
                    labels: visaLabels,
                    datasets: [{
                        data: visaData,
                        borderWidth: 0,
                        backgroundColor: ['#4f46e5', '#22c55e', '#f97316'],
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
