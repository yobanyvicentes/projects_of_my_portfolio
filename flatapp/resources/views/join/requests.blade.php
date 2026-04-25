<x-app-layout>
<div>
<h1>Join requests</h1>
@foreach($requests as $r)
<div>
{{ $r->user->name }}
<form method="POST" action="{{ route('join.accept', $r) }}">@csrf<button>Accept</button></form>
<form method="POST" action="{{ route('join.decline', $r) }}">@csrf<button>Reject</button></form>
</div>
@endforeach
</div>
</x-app-layout>