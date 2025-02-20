<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Speciality;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /*
     * Display a listing of the resource.
     */

    // public function getCoachesBySpecialty($specialty)
    // {
    //     $coaches = User::select('id', 'full_name')
    //                     ->where('speciality', $specialty)
    //                     ->get();
    //     return response()->json($coaches);
    // }

    public function index(Request $request)
    {
        
        $specialities = Speciality::all();
        
        $query = User::query();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%");
            });
        }
    
        if ($request->filled('availability')) {
            $query->where('is_available', $request->availability);
        }
        if ($request->filled('specialities')) {
            $specialityId = $request->specialities; // This is a string (or number) from the select
            $query->where('speciality', $specialityId);
        }
        
        
    
        $users = $query->get();
    
        return view('coaches', compact('users', 'specialities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        $specialities = Speciality::select('name');
        return view('add_user', compact('specialities'));

    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    public function store(Request $request) {
            $request->validate([
                'full_name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'tel' => 'required',
                'specialist' => 'required',
                'is_available' => 'required',
                
            ]);
        
            $user = new User();
            $user->full_name = $request->input('full_name');
            $user->email = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            $user->tel = $request->input('tel');
            $user->speciality = $request->input('specialist');
            $user->is_available = (int) $request->input('is_available');
        

        
            $user->save();
            // dd($user);
            return redirect()->route('user.index')->with('success', 'Coach added successfully!');
    }
        
    //     // dd($request->all());
    //     $request->validate([
    //         'fullname' => 'required',
    //         'email' => 'required',
    //         'password' => 'required',
        
    //         'isAvailable' => 'required',
    //         'speciality' => 'required',
    //         'tel' => 'required',
    
           
    //         ]);
    //     $user = new User();
    //     $user->fuul_name = $request->input('fullname');
    //     $user->email = $request->input('email');
    //     $user->password = Hash::make($request->input('password'));
    //     $user->isAdmin = $request->input('isAdmin');
    //     $user->isAvailable = $request->input('isAvailable');
    //     $user->speciality = $request->input('speciality');
    //     $user->tel = $request->input('phone');
    //     $user->planning = $request->input('planning');
    //     $user->save();
    //     return redirect()->route('dashboard');
    // }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
        // dd($id);
        $coach = User::find($id);
        $specialities = User::select('speciality')->distinct()->pluck('speciality'); 
        $coachAppointments = Appointment::with('patient')->where('coach_id', $id)->get();
        return view('coach_profile', compact('coach','specialities','coachAppointments'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
        $user = User::find($id);
        $specialities = User::select('speciality')->distinct()->pluck('speciality'); 
        return view('edit_user', compact('user','specialities'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        // dd($request->all());
        $user = User::find($id);
        $user->full_name = $request->input('full_name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->is_available = (int)$request->input('is_available');
        $user->speciality = $request->input('specialist');
        $user->tel = $request->input('tel');
        $user->save();
       return redirect()->route('user.show', $user->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('user.index');
    }
}
