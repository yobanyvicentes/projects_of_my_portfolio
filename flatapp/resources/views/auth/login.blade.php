<x-guest-layout>
    <div class="flatapp-card space-y-6">

        <div>
            <h1 class="text-2xl font-semibold">Welcome back</h1>
            <p class="text-sm text-gray-400">Log in to continue to your flat</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label class="text-sm">Email</label>
                <input type="email" name="email" required class="flatapp-input w-full mt-1">
            </div>

            <div>
                <label class="text-sm">Password</label>
                <input type="password" name="password" required class="flatapp-input w-full mt-1">
            </div>

            <div class="flex items-center justify-between">
                <label class="inline-flex items-center gap-2 text-sm text-gray-400">
                    <input
                        type="checkbox"
                        name="remember"
                        class="rounded border-gray-700 bg-black text-cyan-400 focus:ring-cyan-400"
                    >
                    Remember me
                </label>

                @if (Route::has('password.request'))
                    <a
                        href="{{ route('password.request') }}"
                        class="text-sm font-medium text-cyan-400 hover:text-cyan-300"
                    >
                        Forgot password?
                    </a>
                @endif
            </div>

            <button
                type="submit"
                class="w-full rounded-xl bg-[#00D4FF] px-4 py-3 font-semibold text-black transition hover:bg-[#00A8FF] focus:outline-none focus:ring-2 focus:ring-[#00D4FF] focus:ring-offset-2 focus:ring-offset-black"
            >
                Log in
            </button>
        </form>

        <p class="text-sm text-gray-400 text-center">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-cyan-400">Create one</a>
        </p>

    </div>
</x-guest-layout>