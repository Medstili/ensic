<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
  

            $query = Patient::query();

            // Filter by search query on patient or coach name
            if ($request->filled('q')) {
                $search = $request->q;
                $query->where(function($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%");
                    });
                
            }
        
            // Filter by speciality
            if ($request->filled('gender')) {
                $query->where('gender', $request->gender);
            }
        
            // Optionally, order by date or other column
            $patients = $query->get();
        
            return view('patients', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        // return view('create-patient');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        
        $patient = new Patient();
        $patient->full_name = $request->input('full_name');
        $patient->gender = $request->input('gender');
        $patient->age = $request->input('age');
        $patient->phone = $request->input('phone_number');
        $patient->priorities= $request->input('Priorities');
        $patient->save();
        return redirect()->route('patient.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
        $patient = Patient::findOrFail($id);
        $specialities= \App\Models\Speciality::all();
        
        return view('patient_profile', compact('patient', 'specialities'));
        // return view('patient_profile', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();
        return redirect()->route('patient.index');

    }
}
