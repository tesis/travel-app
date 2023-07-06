<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Travel;
use App\Http\Resources\TravelResource;

class TravelController extends Controller
{
    public function index()
    {
        // $travels = Travel::where('is_public', true)->get();
        $travels = Travel::where('is_public', true)->paginate();

        return TravelResource::collection($travels);
    }
}
