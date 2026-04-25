<x-app-layout>
<div class="space-y-4">
<h1 class="text-xl font-bold">Chores</h1>

<form method="POST" action="{{ route('chores.store') }}" class="space-y-3">
@csrf
<input name="title" placeholder="Task" class="flatapp-input w-full">

<select name="assigned_to" class="flatapp-input w-full">
<option value="">Assign to</option>
@foreach($members as $m)
<option value="{{ $m->user->id }}">{{ $m->user->name }}</option>
@endforeach
</select>

<button class="flatapp-btn-primary w-full">Add</button>
</form>

@foreach($chores as $c)
<div class="flatapp-card flex items-center justify-between gap-3">
<div class="flex flex-col gap-1">
<span class="{{ $c->status === 'done' ? 'line-through text-gray-400' : '' }}">{{ $c->title }}</span>
<div class="flex items-center gap-2 text-xs">
<span class="rounded-full px-2 py-0.5 {{ $c->status === 'done' ? 'bg-green-500 text-black' : 'bg-yellow-400 text-black' }}">{{ $c->status }}</span>
@if($c->assignee)
<span class="text-gray-400">→ {{ $c->assignee->name }}</span>
@endif
</div>
</div>

<div class="flex gap-2 items-center">
<a href="{{ route('chores.edit', $c) }}" class="text-xs underline">Edit</a>

<form method="POST" action="{{ route('chores.delete', $c) }}">
@csrf
<button class="text-xs text-red-400">Delete</button>
</form>

<form method="POST" action="{{ route('chores.complete', $c) }}">
@csrf
<button class="rounded-xl px-3 py-1 text-xs {{ $c->status === 'done' ? 'bg-gray-700 text-white' : 'bg-[#00D4FF] text-black' }}">{{ $c->status === 'done' ? 'Done' : 'Mark done' }}</button>
</form>
</div>

</div>
@endforeach

</div>
</x-app-layout>