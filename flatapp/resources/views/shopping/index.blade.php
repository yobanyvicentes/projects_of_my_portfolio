<x-app-layout>
    <div class="space-y-4">
    <h1 class="text-xl font-bold">Shopping list</h1>

    <form method="POST" action="{{ route('shopping.store') }}" class="space-y-3">
    @csrf
    <input name="name" placeholder="Item" class="flatapp-input w-full">
    <input name="quantity" placeholder="Qty" class="flatapp-input w-full">
    <button class="flatapp-btn-primary w-full">Add</button>
    </form>

    @foreach($items as $i)
    <div class="flatapp-card flex items-center justify-between gap-3">
    <div>
    <span class="{{ $i->status === 'bought' ? 'line-through text-gray-400' : '' }}">{{ $i->name }}</span>
    @if($i->quantity)
    <span class="text-xs text-gray-400">({{ $i->quantity }})</span>
    @endif
    <div>
    <span class="rounded-full px-2 py-0.5 text-xs {{ $i->status === 'bought' ? 'bg-green-500 text-black' : 'bg-yellow-400 text-black' }}">{{ $i->status }}</span>
    </div>
    </div>
    <form method="POST" action="{{ route('shopping.complete', $i) }}">
    @csrf
    <button class="rounded-xl bg-[#00D4FF] px-3 py-1 text-xs text-black">Bought</button>
    </form>
    </div>
    @endforeach

    </div>
</x-app-layout>