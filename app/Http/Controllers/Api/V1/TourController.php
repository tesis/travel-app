<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TourResource;
use App\Http\Requests\ToursListRequest;
use App\Models\Travel;

class TourController extends Controller
{
    /** Requirements: Users can filter (search) the results by priceFrom, priceTo, dateFrom (from that startingDate) and dateTo (until that startingDate).User can sort the list by price asc and desc. They will always be sorted, after every additional user-provided filter, by startingDate asc. */
    // /api/v1/travels/{slug}/tours?priceFrom=123&priceTo=456&dateFrom=2023-06-01&dateTo=2023-07-01
    public function index(Travel $travel, ToursListRequest $request)
    {
        $tours = $travel->tours()
            ->when($request->priceFrom, function ($query) use ($request) {
                $query->where('price', '>=', $request->priceFrom * 100);
            })
            ->when($request->priceTo, function ($query) use ($request) {
                $query->where('price', '<=', $request->priceTo * 100);
            })
            ->when($request->dateFrom, function ($query) use ($request) {
                $query->where('starting_date', '>=', $request->dateFrom);
            })
            ->when($request->dateTo, function ($query) use ($request) {
                $query->where('starting_date', '<=', $request->dateTo);
            })
            ->when($request->sortBy, function ($query) use ($request) {
                if (!in_array($request->sortBy, ['price'])
                    || (!in_array($request->sortOrder, ['asc', 'desc']))) {
                    return;
                }

                $query->orderBy($request->sortBy, $request->sortOrder);
            })
            ->orderBy('starting_date')
            ->paginate();

        return TourResource::collection($tours);
    }


}
