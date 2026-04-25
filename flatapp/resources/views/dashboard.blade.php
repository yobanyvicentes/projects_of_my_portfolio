<x-app-layout>
<div class="space-y-6">

<h1 class="text-2xl font-bold">Dashboard</h1>

<div class="grid grid-cols-2 gap-4">

<div class="flatapp-card">
<p class="text-xs text-gray-400">Members</p>
<p class="text-2xl font-bold">{{ $membersCount }}</p>
</div>

<div class="flatapp-card">
<p class="text-xs text-gray-400">Pending requests</p>
<p class="text-2xl font-bold">{{ $pendingRequestsCount }}</p>
</div>

<div class="flatapp-card">
<p class="text-xs text-gray-400">Pending chores</p>
<p class="text-2xl font-bold">{{ $pendingChoresCount }}</p>
</div>

<div class="flatapp-card">
<p class="text-xs text-gray-400">Shopping items</p>
<p class="text-2xl font-bold">{{ $shoppingItemsCount }}</p>
</div>

<div class="flatapp-card col-span-2">
<p class="text-xs text-gray-400">Receipts</p>
<p class="text-2xl font-bold">{{ $receiptsCount }}</p>
</div>

</div>

<div>
<h2 class="text-lg font-semibold mb-2">Recent activity</h2>

@if($recentActivities->isEmpty())
<p class="text-sm text-gray-400">No activity yet</p>
@endif

@foreach($recentActivities as $a)
<div class="flatapp-card text-sm">
{{ $a->action }}
</div>
@endforeach
</div>

<div class="grid grid-cols-2 gap-3">
<a href="/chores" class="flatapp-btn-primary">Chores</a>
<a href="/shopping" class="flatapp-btn-primary">Shopping</a>
<a href="/receipts" class="flatapp-btn-primary">Receipts</a>
<a href="/activity" class="flatapp-btn-primary">Activity</a>
</div>

</div>
</x-app-layout>
