<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsletterRequest;
use Illuminate\Http\RedirectResponse;

final class NewsletterController extends Controller
{
    public function store(StoreNewsletterRequest $request): RedirectResponse
    {
        // TODO: Implement backend persistence/notification logic here
        return back()->with('success', 'You are subscribed to our newsletter.');
    }
}