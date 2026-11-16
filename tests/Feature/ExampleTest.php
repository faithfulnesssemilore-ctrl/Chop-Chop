<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_homepage_renders_forms_and_security_headers(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertSee('Chop Chop Naija', false)
            ->assertSee('name="csrf-token"', false)
            ->assertSee(route('reservations.store'), false)
            ->assertSee(route('contact.store'), false)
            ->assertSee(route('newsletter.store'), false)
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_reservation_validation_redirects_with_errors_and_old_input(): void
    {
        $this->from(route('home'))
            ->post(route('reservations.store'), ['name' => 'Ada', 'email' => 'invalid'])
            ->assertRedirect(route('home'))
            ->assertSessionHasErrors(['email', 'phone', 'guests', 'date', 'time'])
            ->assertSessionHas('_old_input.name', 'Ada');
    }

    public function test_valid_reservation_contact_and_newsletter_requests_flash_success(): void
    {
        $this->post(route('reservations.store'), [
            'name' => 'Ada Okafor',
            'phone' => '+2348000000000',
            'email' => 'ada@example.com',
            'guests' => 2,
            'date' => now()->addDay()->toDateString(),
            'time' => '19:00',
            'special_requests' => 'Window seat, please.',
        ])->assertRedirect()->assertSessionHas('success');

        $this->post(route('contact.store'), [
            'name' => 'Ada Okafor',
            'email' => 'ada@example.com',
            'phone' => '+2348000000000',
            'subject' => 'general_inquiry',
            'message' => 'Please share your catering options.',
        ])->assertRedirect()->assertSessionHas('success');

        $this->post(route('newsletter.store'), [
            'email' => 'ada@example.com',
        ])->assertRedirect()->assertSessionHas('success');
    }
}
