<x-app-layout>
<div class="space-y-4">
<h1 class="text-xl font-bold">Edit chore</h1>

<form method="POST" action="{{ route('chores.update', $chore) }}" class="space-y-3">
@csrf
<input name="title" value="{{ $chore->title }}" class="flatapp-input w-full">
<input name="due_date" type="date" value="{{ $chore->due_date }}" class="flatapp-input w-full">
<button class="flatapp-btn-primary w-full">Save</button>
</form>

</div>
</x-app-layout>