<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\TourResource;
use App\Models\Travel;

class TourController extends Controller
{
    /** Requirements: Users can filter (search) the results by priceFrom, priceTo, dateFrom (from that startingDate) and dateTo (until that startingDate).User can sort the list by price asc and desc. They will always be sorted, after every additional user-provided filter, by startingDate asc. */
    // /api/v1/travels/{slug}/tours?priceFrom=123&priceTo=456&dateFrom=2023-06-01&dateTo=2023-07-01
    public function index(Travel $travel, Request $request)
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
            ->orderBy('starting_date')
            ->paginate();

        return TourResource::collection($tours);
    }


}
