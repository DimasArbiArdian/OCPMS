@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm text-gray-500">
                <a href="{{ route('candidates.index') }}" class="text-indigo-600">{{ __('app.candidates.list_title') }}</a> /
                {{ __('app.candidates.profile.detail_breadcrumb') }}
            </p>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $candidate->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
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

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="rounded-lg bg-white p-6 shadow lg:col-span-1">
                    <h3 class="text-lg font-semibold text-gray-800">{{ __('app.candidates.profile.title') }}</h3>
                    <dl class="mt-4 space-y-3 text-sm text-gray-600">
                        <div>
                            <dt class="font-medium text-gray-900">{{ __('app.candidates.profile.position') }}</dt>
                            <dd>{{ $candidate->position }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-900">{{ __('app.candidates.profile.phone') }}</dt>
                            <dd>{{ $candidate->phone }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-900">{{ __('app.candidates.profile.passport') }}</dt>
                            <dd>{{ $candidate->passport_number }} |
                                {{ __('app.candidates.form.passport_expired') }}: {{ $candidate->passport_expired->format('d M Y') }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-900">{{ __('app.candidates.profile.country') }}</dt>
                            <dd>{{ $candidate->country }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-900">{{ __('app.candidates.profile.updated') }}</dt>
                            <dd>{{ $candidate->updated_at->diffForHumans() }}</dd>
                        </div>
                    </dl>
                    <div class="mt-6 space-y-2">
                        <p class="text-sm font-semibold text-gray-900">{{ __('app.candidates.profile.progress_snapshot') }}</p>
                        <div class="flex flex-col gap-2 text-xs">
                            <span class="rounded bg-indigo-50 px-2 py-1 text-indigo-700">Visa:
                                {{ str($candidate->progress->visa_status ?? 'process')->headline() }}</span>
                            <span class="rounded bg-emerald-50 px-2 py-1 text-emerald-700">Medical:
                                {{ str($candidate->progress->medical_status ?? 'not_yet')->headline() }}</span>
                            <span class="rounded bg-amber-50 px-2 py-1 text-amber-700">Ticket:
                                {{ str($candidate->progress->ticket_status ?? 'not_yet')->headline() }}</span>
                            <span class="rounded bg-slate-100 px-2 py-1 text-slate-700">DP:
                                {{ str($candidate->progress->dp_status ?? 'pending')->headline() }}</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-6 shadow lg:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800">{{ __('app.candidates.profile.update_form') }}</h3>
                    <form class="mt-6 grid gap-4 md:grid-cols-2" method="POST"
                        action="{{ route('candidates.update', $candidate) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $candidate->id }}">
                        <div>
                            <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.form.name') }}</label>
                            <input name="name" value="{{ old('name', $candidate->name) }}" required
                                class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.form.position') }}</label>
                            <input name="position" value="{{ old('position', $candidate->position) }}" required
                                class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.form.phone') }}</label>
                            <input name="phone" value="{{ old('phone', $candidate->phone) }}" required
                                class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.form.passport_number') }}</label>
                            <input name="passport_number" value="{{ old('passport_number', $candidate->passport_number) }}"
                                required
                                class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.form.passport_expired') }}</label>
                            <input type="date" name="passport_expired"
                                value="{{ old('passport_expired', $candidate->passport_expired->format('Y-m-d')) }}" required
                                class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.form.country') }}</label>
                            <input name="country" value="{{ old('country', $candidate->country) }}" required
                                class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.form.dp_status') }}</label>
                            <select name="dp_status"
                                class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach (['pending', 'done'] as $status)
                                    <option value="{{ $status }}"
                                        @selected(old('dp_status', $candidate->progress->dp_status ?? 'pending') === $status)>
                                        {{ str($status)->headline() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.form.medical_status') }}</label>
                            <select name="medical_status"
                                class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach (['not_yet', 'done'] as $status)
                                    <option value="{{ $status }}"
                                        @selected(old('medical_status', $candidate->progress->medical_status ?? 'not_yet') === $status)>
                                        {{ str($status)->headline() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.form.visa_status') }}</label>
                            <select name="visa_status"
                                class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach (['process', 'approved', 'rejected'] as $status)
                                    <option value="{{ $status }}"
                                        @selected(old('visa_status', $candidate->progress->visa_status ?? 'process') === $status)>
                                        {{ str($status)->headline() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.form.ticket_status') }}</label>
                            <select name="ticket_status"
                                class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach (['not_yet', 'booked'] as $status)
                                    <option value="{{ $status }}"
                                        @selected(old('ticket_status', $candidate->progress->ticket_status ?? 'not_yet') === $status)>
                                        {{ str($status)->headline() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.form.departure_date') }}</label>
                            <input type="date" name="departure_date"
                                value="{{ old('departure_date', optional($candidate->progress->departure_date)->format('Y-m-d')) }}"
                                class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-700">{{ __('app.candidates.form.remarks') }}</label>
                            <textarea name="remarks" rows="3"
                                class="mt-1 w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('remarks', optional($candidate->progress)->remarks) }}</textarea>
                        </div>
                        <div class="md:col-span-2 flex justify-end">
                            <button type="submit"
                                class="rounded bg-indigo-600 px-6 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                                {{ __('app.candidates.profile.update_button') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="rounded-lg bg-white p-6 shadow">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ __('app.candidates.profile.document_center') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('app.candidates.profile.document_hint') }}</p>
                    </div>
                    <form method="POST" action="{{ route('candidates.documents.store', $candidate) }}"
                        enctype="multipart/form-data" class="flex flex-wrap gap-2">
                        @csrf
                        <select name="type"
                            class="rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach (['passport', 'medical', 'visa', 'ticket'] as $type)
                                <option value="{{ $type }}">{{ str($type)->headline() }}</option>
                            @endforeach
                        </select>
                        <input type="file" name="file" required
                            class="rounded border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <button type="submit"
                            class="rounded bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500">
                            {{ __('app.candidates.profile.upload_button') }}
                        </button>
                    </form>
                </div>
                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">{{ __('app.candidates.profile.document_type') }}</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">{{ __('app.candidates.profile.document_uploaded_at') }}</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">{{ __('app.candidates.profile.document_file') }}</th>
                                <th class="px-4 py-2 text-right font-semibold text-gray-600">{{ __('app.candidates.profile.document_action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($candidate->documents as $document)
                                <tr>
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ str($document->type)->headline() }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $document->uploaded_at->format('d M Y, H:i') }}</td>
                                    <td class="px-4 py-3">
                                        <a href="{{ Storage::url($document->file_path) }}" target="_blank"
                                            class="text-indigo-600 hover:underline">{{ __('app.buttons.download') }}</a>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <form method="POST"
                                            action="{{ route('candidates.documents.destroy', [$candidate, $document]) }}"
                                            onsubmit="return confirm('{{ __('app.messages.delete_document') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-sm text-red-600 hover:underline">{{ __('app.buttons.delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">{{ __('app.candidates.profile.document_none') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
