<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use Illuminate\Http\RedirectResponse;

final class ContactController extends Controller
{
    public function store(StoreContactRequest $request): RedirectResponse
    {
        // TODO: Implement backend persistence/notification logic here
        return back()->with('success', 'Your message has been received.');
    }
}