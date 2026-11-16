<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;

final class ContactController extends Controller
{
    public function store(StoreContactRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $validated['email'] = strtolower(trim((string) ($validated['email'] ?? '')));
        $validated['name'] = trim((string) ($validated['name'] ?? ''));
        $validated['phone'] = trim((string) ($validated['phone'] ?? ''));
        $validated['message'] = trim((string) ($validated['message'] ?? ''));

        Contact::query()->create($validated);

        return back()->with('success', 'Your message has been received.');
    }
}