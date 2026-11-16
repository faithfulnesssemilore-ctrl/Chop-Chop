<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Newsletter;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_valid_form_submissions_are_persisted(): void
    {
        $this->assertDatabaseCount('contacts', 0);
        $this->assertDatabaseCount('reservations', 0);
        $this->assertDatabaseCount('newsletters', 0);

        $this->post(route('reservations.store'), [
            'name' => 'Ada Okafor',
            'phone' => '+2348000000000',
            'email' => 'ada@example.com',
            'guests' => 2,
            'date' => now()->addDay()->toDateString(),
            'time' => '19:00',
            'special_requests' => 'Window seat, please.',
        ]);

        $this->post(route('contact.store'), [
            'name' => 'Ada Okafor',
            'email' => 'ada@example.com',
            'phone' => '+2348000000000',
            'subject' => 'general_inquiry',
            'message' => 'Please share your catering options.',
        ]);

        $this->post(route('newsletter.store'), [
            'email' => 'ada@example.com',
        ]);

        $this->assertDatabaseHas('reservations', [
            'email' => 'ada@example.com',
            'name' => 'Ada Okafor',
            'guests' => 2,
        ]);

        $this->assertDatabaseHas('contacts', [
            'email' => 'ada@example.com',
            'name' => 'Ada Okafor',
            'subject' => 'general_inquiry',
        ]);

        $this->assertDatabaseHas('newsletters', [
            'email' => 'ada@example.com',
        ]);

        $this->assertSame(1, Contact::query()->count());
        $this->assertSame(1, Reservation::query()->count());
        $this->assertSame(1, Newsletter::query()->count());
    }

    public function test_duplicate_newsletter_signup_does_not_create_duplicate_rows(): void
    {
        $this->post(route('newsletter.store'), [
            'email' => 'ADA@example.com',
        ])->assertRedirect()->assertSessionHas('success');

        $this->post(route('newsletter.store'), [
            'email' => ' ada@example.com ',
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseCount('newsletters', 1);
        $this->assertDatabaseHas('newsletters', ['email' => 'ada@example.com']);
    }
}
