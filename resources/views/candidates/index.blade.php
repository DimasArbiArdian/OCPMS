<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('app.candidates.management') }}
        </h2>
    </x-slot>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <div class="py-12" x-data="{ showCreate: false }">
        <div class="mx-auto max-w-7xl space-y-8 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-900">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-900">
                    <p class="font-semibold">{{ __('app.alerts.fix_errors') }}</p>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="rounded-lg bg-white p-6 shadow">
                <form method="GET" class="grid gap-4 md:grid-cols-5">
                    <div>
                        <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.filters.search') }}</label>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Name or phone"
                            class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.filters.passport') }}</label>
                        <input type="text" name="passport" value="{{ $filters['passport'] ?? '' }}"
                            class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.filters.position') }}</label>
                        <select name="position"
                            class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All</option>
                            @foreach ($positions as $position)
                                <option value="{{ $position }}" @selected(($filters['position'] ?? '') === $position)>
                                    {{ $position }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.filters.visa') }}</label>
                        <select name="visa_status"
                            class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All</option>
                            @foreach (['process', 'approved', 'rejected'] as $status)
                                <option value="{{ $status }}" @selected(($filters['visa_status'] ?? '') === $status)>
                                    {{ str($status)->headline() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.filters.medical') }}</label>
                        <select name="medical_status"
                            class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All</option>
                            @foreach (['done', 'not_yet'] as $status)
                                <option value="{{ $status }}" @selected(($filters['medical_status'] ?? '') === $status)>
                                    {{ str($status)->headline() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-5 flex justify-end gap-2">
                        <a href="{{ route('candidates.index') }}"
                            class="rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">{{ __('app.buttons.reset') }}</a>
                        <button type="submit"
                            class="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                            {{ __('app.buttons.apply') }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="rounded-lg bg-white p-6 shadow">
                <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ __('app.candidates.list_title') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('app.candidates.list_hint', ['total' => $candidates->total()]) }}</p>
                    </div>
                    <button type="button" @click="showCreate = true"
                        class="rounded bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                        + {{ __('app.candidates.register_button') }}
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">{{ __('app.candidates.table.candidate') }}</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">{{ __('app.candidates.table.passport') }}</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">{{ __('app.candidates.table.progress') }}</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">{{ __('app.candidates.table.departure') }}</th>
                                <th class="px-4 py-2 text-right font-semibold text-gray-600">{{ __('app.candidates.table.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($candidates as $candidate)
                                <tr>
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-gray-900">{{ $candidate->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $candidate->position }} • {{ $candidate->country }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $candidate->passport_number }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-1 text-xs">
                                            <span class="rounded-full bg-indigo-50 px-2 py-1 text-indigo-700">
                                                Visa: {{ str($candidate->progress->visa_status ?? 'process')->headline() }}
                                            </span>
                                            <span class="rounded-full bg-emerald-50 px-2 py-1 text-emerald-700">
                                                Medical: {{ str($candidate->progress->medical_status ?? 'not_yet')->headline() }}
                                            </span>
                                            <span class="rounded-full bg-amber-50 px-2 py-1 text-amber-700">
                                                Ticket: {{ str($candidate->progress->ticket_status ?? 'not_yet')->headline() }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ optional($candidate->progress?->departure_date)->format('d M Y') ?? 'TBD' }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('candidates.show', $candidate) }}"
                                            class="text-indigo-600 hover:underline">{{ __('app.buttons.view') }}</a>
                                        <form method="POST" action="{{ route('candidates.destroy', $candidate) }}"
                                            class="inline-block"
                                            onsubmit="return confirm('{{ __('app.messages.delete_confirm', ['name' => $candidate->name]) }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="ml-3 text-sm text-red-600 hover:underline">{{ __('app.buttons.delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                        {{ __('app.candidates.messages.not_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $candidates->links() }}
                </div>
            </div>
        </div>

        <div x-cloak x-show="showCreate" x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4 py-8"
            @keydown.escape.window="showCreate = false" @click.self="showCreate = false">
            <div class="w-full max-w-5xl rounded-lg bg-white shadow-2xl">
                <div class="flex items-center justify-between border-b px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-800">{{ __('app.candidates.form.title') }}</h3>
                    <button type="button" class="text-2xl font-semibold text-gray-400 hover:text-gray-600"
                        @click="showCreate = false">
                        &times;
                    </button>
                </div>
                <form method="POST" action="{{ route('candidates.store') }}" class="grid gap-4 border-t px-6 py-6 md:grid-cols-3">
                    @csrf
                    <div>
                        <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.form.name') }}</label>
                        <input name="name" required
                            class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.form.position') }}</label>
                        <input name="position" required
                            class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.form.phone') }}</label>
                        <input name="phone" required
                            class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.form.passport_number') }}</label>
                        <input name="passport_number" required
                            class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.form.passport_expired') }}</label>
                        <input type="date" name="passport_expired" required
                            class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.form.country') }}</label>
                        <input name="country" required
                            class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.form.dp_status') }}</label>
                        <select name="dp_status"
                            class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="pending">Pending</option>
                            <option value="done">Done</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.form.medical_status') }}</label>
                        <select name="medical_status"
                            class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="not_yet">Not Yet</option>
                            <option value="done">Done</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.form.visa_status') }}</label>
                        <select name="visa_status"
                            class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="process">On Process</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.form.ticket_status') }}</label>
                        <select name="ticket_status"
                            class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="not_yet">Not Yet</option>
                            <option value="booked">Booked</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.form.departure_date') }}</label>
                        <input type="date" name="departure_date"
                            class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="md:col-span-3">
                        <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.form.remarks') }}</label>
                        <textarea name="remarks" rows="2"
                            class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                    <div class="md:col-span-3 flex justify-end gap-3">
                        <button type="button" @click="showCreate = false"
                            class="rounded border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">{{ __('app.buttons.cancel') }}</button>
                        <button type="submit"
                            class="rounded bg-indigo-600 px-6 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                            {{ __('app.buttons.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
