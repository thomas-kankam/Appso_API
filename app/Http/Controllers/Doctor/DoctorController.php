<?php

namespace App\Http\Controllers\Doctor;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;

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
    public function show($id)
    {
        $doctor = Doctor::findOrFail($id);
        return response()->json($doctor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
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
