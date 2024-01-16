<?php

namespace App\Http\Controllers\Patient;

use App\Models\Patient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PatientResource::collection(Patient::all());
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
        $patient = Patient::findOrFail($id);
        return response()->json($patient);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        try {
            $patient->delete();
            return response()->json(['message' => 'Patient deleted successfully']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Patient not deleted']);
        }
    }
}
