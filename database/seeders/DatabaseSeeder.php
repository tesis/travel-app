<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Travel;
use App\Models\Tour;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        /*$travels = Travel::factory(10)->create();
        foreach ($travels as $travel) {
            Tour::factory()->create([
                'travel_id' => $travel->id,
            ]);
        }*/

        $travel = Travel::factory()->create([
            'id' => "999a6b3b-e092-4439-b5c1-4e5691baf424"
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
