<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Travel;
use App\Models\Tour;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        // User can be created with php artisan user:create
        $user = User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@admin.admin',
            'password' => '11111111',
        ]);

        $user->roles()->attach(Role::where('name', 'admin')->value('id'));

        $travel = Travel::factory()->create([
            'id' => "999a6b3b-e092-4439-b5c1-4e5691baf424",
            'name' => 'Best Travel',
            'slug' => 'best-travel'
        ]);

        $expensiveTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 200,
        ]);
        $cheapLaterTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 100,
            'starting_date' => now()->addDays(2),
            'ending_date' => now()->addDays(3),
        ]);
        $cheapEarlierTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 100,
            'starting_date' => now()->toDateTimeString(),
            'ending_date' => now()->addDays(1)->toDateTimeString(),
        ]);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
