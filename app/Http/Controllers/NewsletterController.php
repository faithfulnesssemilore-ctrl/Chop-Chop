<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsletterRequest;
use App\Models\Newsletter;
use Illuminate\Http\RedirectResponse;

final class NewsletterController extends Controller
{
    public function store(StoreNewsletterRequest $request): RedirectResponse
    {
        $email = strtolower(trim((string) $request->input('email')));

        Newsletter::query()->firstOrCreate([
            'email' => $email,
        ]);

        return back()->with('success', 'You are subscribed to our newsletter.');
    }
}