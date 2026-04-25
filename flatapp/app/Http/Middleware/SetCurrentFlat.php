<?php

namespace App\Http\Middleware;

use App\Models\Flat;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentFlat
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return $next($request);
        }

        $flatId = $request->session()->get('current_flat_id');

        $currentFlat = $flatId
            ? $request->user()
                ->memberships()
                ->where('status', 'active')
                ->where('flat_id', $flatId)
                ->with('flat')
                ->first()?->flat
            : null;

        if (! $currentFlat) {
            $currentFlat = $request->user()
                ->memberships()
                ->where('status', 'active')
                ->with('flat')
                ->latest()
                ->first()?->flat;

            if ($currentFlat instanceof Flat) {
                $request->session()->put('current_flat_id', $currentFlat->id);
            }
        }

        view()->share('currentFlat', $currentFlat);

        return $next($request);
    }
}
