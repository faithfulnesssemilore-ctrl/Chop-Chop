<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use Illuminate\Http\RedirectResponse;

final class ReservationController extends Controller
{
    public function store(StoreReservationRequest $request): RedirectResponse
    {
        // TODO: Implement backend persistence/notification logic here
        return back()->with('success', 'Your reservation request has been received.');
    }
}