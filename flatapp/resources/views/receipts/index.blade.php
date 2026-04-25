<x-app-layout>
<div class="space-y-4">
<h1 class="text-xl font-bold">Receipts</h1>

<form method="POST" action="{{ route('receipts.store') }}" enctype="multipart/form-data" class="space-y-3">
@csrf
<input name="title" placeholder="Title" class="flatapp-input w-full">
<input name="amount" type="number" step="0.01" placeholder="Amount" class="flatapp-input w-full">
<input type="file" name="file" class="flatapp-input w-full">
<button class="flatapp-btn-primary w-full">Upload</button>
</form>

@foreach($receipts as $r)
<div class="flatapp-card flex justify-between">
<div>
<p>{{ $r->title }}</p>
@if($r->amount)
<p class="text-sm text-gray-400">${{ $r->amount }}</p>
@endif
</div>
<a href="{{ asset('storage/'.$r->file_path) }}" target="_blank" class="text-[#00D4FF] text-sm">View</a>
</div>
@endforeach

</div>
</x-app-layout>