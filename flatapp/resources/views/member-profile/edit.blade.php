<x-app-layout>
<div class="space-y-4">
<h1 class="text-xl font-bold">My profile</h1>
<form method="POST" action="{{ route('member-profile.update') }}" class="space-y-3">
@csrf
<input name="phone" placeholder="Phone" value="{{ $membership->profile->phone ?? '' }}" class="flatapp-input w-full">
<input name="bank_account_number" placeholder="Bank account" value="{{ $membership->profile->bank_account_number ?? '' }}" class="flatapp-input w-full">
<button class="flatapp-btn-primary w-full">Save</button>
</form>
</div>
</x-app-layout>