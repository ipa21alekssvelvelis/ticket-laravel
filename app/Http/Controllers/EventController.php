<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'type' => 'required|exists:event_types,id',
            'date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'place' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => 'Input missing', 'details' => $validator->errors()], 422);
        }
    
        $destination = 'public/images';
        $image = $request->file('image');
        $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
    
        $imagePath = $image->storeAs($destination, $imageName);
    
        $imageUrl = Storage::url($imageName);
    
        $event = Event::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'type' => $request->input('type'),
            'date' => $request->input('date'),
            'price' => $request->input('price'),
            'place' => $request->input('place'),
            'image_path' => $imageName,
        ]);
    
        return response()->json(['message' => 'Event created'], 200);
    }
    

    public function eventList()
    {
        $data = Event::all();

        return response()->json($data);
    }

    public function delete($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $event->delete();

        return response()->json(['message' => 'Event deleted'], 200);
    }

    public function edit(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'type' => 'required|exists:event_types,id',
            'date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'place' => 'required',
            'image' => 'image|mimes:jpg,jpeg,png,gif',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Input missing', 'details' => $validator->errors()], 422);
        }

        if ($request->hasFile('image')) {
            Storage::delete('public/images/' . $event->image_path);
        }

        if($image = $request->file('image')){
            $destination = 'public/images';
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs($destination, $imageName);
            $event->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'type' => $request->input('type'),
                'date' => $request->input('date'),
                'price' => $request->input('price'),
                'place' => $request->input('place'),
                'image_path' => $imageName,
            ]);
        }else{
            $event->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'type' => $request->input('type'),
                'date' => $request->input('date'),
                'price' => $request->input('price'),
                'place' => $request->input('place'),
            ]);
        }

        return response()->json(['message' => 'Event updated successfully'], 200);
    }

}
