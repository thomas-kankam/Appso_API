<?php

namespace App\Http\Controllers\Doctor;

use App\Models\Doctor;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\UpdateDoctorProfileRequest;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\DoctorResource;
use App\Models\Reply;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return DoctorResource::collection(Doctor::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        return new DoctorResource($doctor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDoctorProfileRequest $request, Doctor $doctor)
    {
        $doctor->update($request->validated());
        return new DoctorResource($doctor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        try {
            $doctor->delete();
            return response()->json(['message' => 'Doctor deleted successfully']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Doctor not deleted']);
        }
    }
}
