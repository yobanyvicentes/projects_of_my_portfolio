<x-guest-layout>
    <div class="flatapp-card space-y-6">

        <div>
            <h1 class="text-2xl font-semibold">Create your account</h1>
            <p class="text-sm text-gray-400">Start managing your flat</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label class="text-sm">Name</label>
                <input type="text" name="name" required class="flatapp-input w-full mt-1">
            </div>

            <div>
                <label class="text-sm">Email</label>
                <input type="email" name="email" required class="flatapp-input w-full mt-1">
            </div>

            <div>
                <label class="text-sm">Password</label>
                <input type="password" name="password" required class="flatapp-input w-full mt-1">
            </div>

            <div>
                <label class="text-sm">Confirm Password</label>
                <input type="password" name="password_confirmation" required class="flatapp-input w-full mt-1">
            </div>

            <button
                type="submit"
                class="w-full rounded-xl bg-[#00D4FF] px-4 py-3 font-semibold text-black transition hover:bg-[#00A8FF] focus:outline-none focus:ring-2 focus:ring-[#00D4FF] focus:ring-offset-2 focus:ring-offset-black"
            >
                Create account
            </button>
        </form>

        <p class="text-sm text-gray-400 text-center">
            Already have an account?
            <a href="{{ route('login') }}" class="text-cyan-400">Log in</a>
        </p>

    </div>
</x-guest-layout>