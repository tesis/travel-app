<?php
// phpunit tests/Feature/ToursListTest.php
namespace Tests\Feature;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToursListTest extends TestCase
{
    use RefreshDatabase;

    public function test_tours_list_sorts_by_price_correctly(): void
    {
        $travel = Travel::factory()->create();

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

        $response = $this->get('/api/v1/travels/'.$travel->slug.'/tours?sortBy=price&sortOrder=asc');

        $response
            ->assertStatus(200)
            ->assertJsonPath('data.0.id', $cheapEarlierTour->id)
            ->assertJsonPath('data.1.id', $cheapLaterTour->id)
            ->assertJsonPath('data.2.id', $expensiveTour->id);
    }

    public function test_tours_list_sorts_by_starting_date_correctly(): void
    {
        $travel = Travel::factory()->create();
        $laterTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'starting_date' => now()->addDays(2),
            'ending_date' => now()->addDays(3),
        ]);
        $earlierTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'starting_date' => now(),
            'ending_date' => now()->addDays(1),
        ]);

        $response = $this->get('/api/v1/travels/'.$travel->slug.'/tours');

        $response
            ->assertStatus(200)
            ->assertJsonPath('data.0.id', $earlierTour->id)
            ->assertJsonPath('data.1.id', $laterTour->id);
    }

    public function test_tours_list_sorts_by_price_correctly1(): void
    {
        $travel = Travel::factory()->create();
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
            'starting_date' => now(),
            'ending_date' => now()->addDays(1),
        ]);

        $response = $this->get('/api/v1/travels/'.$travel->slug.'/tours?sortBy=price&sortOrder=asc');

        $response
            ->assertStatus(200)
            ->assertJsonPath('data.0.id', $cheapEarlierTour->id)
            ->assertJsonPath('data.1.id', $cheapLaterTour->id)
            ->assertJsonPath('data.2.id', $expensiveTour->id);
    }

    public function test_tours_list_filters_by_price_correctly(): void
    {
        $travel = Travel::factory()->create();
        $expensiveTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 200,
        ]);
        $cheapTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 100,
        ]);

        $endpoint = '/api/v1/travels/'.$travel->slug.'/tours';

        $response = $this->get($endpoint.'?priceFrom=100');
        $response
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['id' => $cheapTour->id])
            ->assertJsonFragment(['id' => $expensiveTour->id]);

        $response = $this->get($endpoint.'?priceFrom=150');
        $response
            ->assertJsonCount(1, 'data')
            ->assertJsonMissing(['id' => $cheapTour->id])
            ->assertJsonFragment(['id' => $expensiveTour->id]);

        $response = $this->get($endpoint.'?priceFrom=250');
        $response->assertJsonCount(0, 'data');

        $response = $this->get($endpoint.'?priceTo=200');
        $response
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['id' => $cheapTour->id])
            ->assertJsonFragment(['id' => $expensiveTour->id]);

        $response = $this->get($endpoint.'?priceTo=150');
        $response
            ->assertJsonCount(1, 'data')
            ->assertJsonMissing(['id' => $expensiveTour->id])
            ->assertJsonFragment(['id' => $cheapTour->id]);

        $response = $this->get($endpoint.'?priceTo=50');
        $response->assertJsonCount(0, 'data');

        $response = $this->get($endpoint.'?priceFrom=150&priceTo=250');
        $response
            ->assertJsonCount(1, 'data')
            ->assertJsonMissing(['id' => $cheapTour->id])
            ->assertJsonFragment(['id' => $expensiveTour->id]);
    }

    public function test_tours_list_filters_by_starting_date_correctly(): void
    {
        $travel = Travel::factory()->create();
        $laterTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'starting_date' => now()->addDays(2),
            'ending_date' => now()->addDays(3),
        ]);
        $earlierTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'starting_date' => now(),
            'ending_date' => now()->addDays(1),
        ]);

        $endpoint = '/api/v1/travels/'.$travel->slug.'/tours';

        $response = $this->get($endpoint.'?dateFrom='.now());
        $response
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['id' => $earlierTour->id])
            ->assertJsonFragment(['id' => $laterTour->id]);

        $response = $this->get($endpoint.'?dateFrom='.now()->addDay());
        $response
            ->assertJsonCount(1, 'data')
            ->assertJsonMissing(['id' => $earlierTour->id])
            ->assertJsonFragment(['id' => $laterTour->id]);

        $response = $this->get($endpoint.'?dateFrom='.now()->addDays(5));
        $response->assertJsonCount(0, 'data');

        $response = $this->get($endpoint.'?dateTo='.now()->addDays(5));
        $response
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['id' => $earlierTour->id])
            ->assertJsonFragment(['id' => $laterTour->id]);

        $response = $this->get($endpoint.'?dateTo='.now()->addDay());
        $response
            ->assertJsonCount(1, 'data')
            ->assertJsonMissing(['id' => $laterTour->id])
            ->assertJsonFragment(['id' => $earlierTour->id]);

        $response = $this->get($endpoint.'?dateTo='.now()->subDay());
        $response->assertJsonCount(0, 'data');

        $response = $this->get($endpoint.'?dateFrom='.now()->addDay().'&dateTo='.now()->addDays(5));
        $response
            ->assertJsonCount(1, 'data')
            ->assertJsonMissing(['id' => $earlierTour->id])
            ->assertJsonFragment(['id' => $laterTour->id]);
    }

    public function test_tour_list_returns_validation_errors(): void
    {
        $travel = Travel::factory()->create();

        $response = $this->getJson('/api/v1/travels/'.$travel->slug.'/tours?dateFrom=abcde');
        $response->assertStatus(422);

        $response = $this->getJson('/api/v1/travels/'.$travel->slug.'/tours?priceFrom=abcde');
        $response->assertStatus(422);
    }
}
