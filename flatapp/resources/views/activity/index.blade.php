<x-app-layout>
<div>
<h1>Activity</h1>
@foreach($activities as $a)
<div>{{ $a->action }} - {{ $a->description }}</div>
@endforeach
</div>
</x-app-layout>