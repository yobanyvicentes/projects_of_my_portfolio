<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Edit Scenario</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Update the scenario values before running a new simulation.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-red-800 dark:border-red-900/60 dark:bg-red-950/30 dark:text-red-200" role="alert" aria-live="polite">
                    <h3 class="text-sm font-semibold">Please review the highlighted fields.</h3>
                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Update Scenario</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Keep the values aligned with the scenario you want to test.</p>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('scenarios.update', $scenario) }}" class="space-y-8">
                        @csrf
                        @method('PATCH')
                        @include('scenario.form', ['scenario' => $scenario])
                        <div class="flex justify-end border-t border-gray-200 pt-6 dark:border-gray-700">
                            <button type="submit" class="inline-flex items-center rounded-lg bg-slate-700 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-slate-600 dark:hover:bg-slate-500 dark:focus:ring-offset-gray-800">Update Scenario</button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
