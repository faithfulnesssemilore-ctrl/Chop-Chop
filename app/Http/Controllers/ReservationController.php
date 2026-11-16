<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;

final class ReservationController extends Controller
{
    public function store(StoreReservationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $validated['email'] = strtolower(trim((string) ($validated['email'] ?? '')));
        $validated['name'] = trim((string) ($validated['name'] ?? ''));
        $validated['phone'] = trim((string) ($validated['phone'] ?? ''));
        $validated['special_requests'] = trim((string) ($validated['special_requests'] ?? ''));

        Reservation::query()->create($validated);

        return back()->with('success', 'Your reservation request has been received.');
    }
}