<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventTypes;

class EventTypesController extends Controller
{
    public function fetchData()
    {
        $data = EventTypes::all();

        return response()->json($data);
    }
}
