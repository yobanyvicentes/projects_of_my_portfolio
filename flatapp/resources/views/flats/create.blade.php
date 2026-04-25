<x-app-layout>
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold">Create a flat</h1>
            <p class="text-sm text-[#9CA3AF]">Start a new shared space</p>
        </div>

        <form method="POST" action="{{ route('flats.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="text-sm">Flat name</label>
                <input name="name" required class="flatapp-input w-full mt-1">
            </div>

            <button class="w-full rounded-xl bg-[#00D4FF] px-4 py-3 font-semibold text-black">
                Create flat
            </button>
        </form>
    </div>
</x-app-layout>
