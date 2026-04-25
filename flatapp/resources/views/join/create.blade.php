<x-app-layout>
<div class="space-y-6">
<h1 class="text-xl font-bold">Join a flat</h1>
<form method="POST" action="{{ route('join.store') }}" class="space-y-4">
@csrf
<input name="code" placeholder="Enter code" class="flatapp-input w-full">
<button class="w-full bg-[#00D4FF] text-black rounded-xl py-2">Join</button>
</form>
</div>
</x-app-layout>