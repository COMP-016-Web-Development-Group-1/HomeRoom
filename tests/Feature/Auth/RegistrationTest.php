<?php

use App\Models\Room;
use Illuminate\Http\UploadedFile;

beforeEach(function () {
    Storage::fake('public');
});

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new tenants can register', function () {
    $room = Room::factory()->create();

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@gmail.com',
        'profile_picture' => UploadedFile::fake()->image('my-profile.jpg'),
        'password' => 'password',
        'password_confirmation' => 'password',
        'code' => $room->code,
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
    Storage::disk('public')->assertExists(auth()->user()->profile_picture);
});
