<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Admin;
use App\Http\Requests\TravelRequest;
use App\Models\Travel;
use App\Http\Resources\TravelResource;

class TravelController extends Controller
{
    public function store(TravelRequest $request)
    {
        $travel = Travel::create($request->validated());

        return new TravelResource($travel);
    }
}
