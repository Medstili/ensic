<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $users = User::all();
        return view('dashboard', compact('users'));       
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        $specialities = User::select('speciality')->distinct()->pluck('speciality');
        return view('add_user', compact('specialities'));

    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    public function store(Request $request) {
            $request->validate([
                'fullname' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'tel' => 'required',
                'specialist' => 'required',
                'isAvailable' => 'required',
                'planning' => 'required|json',
            ]);
        
            $user = new User();
            $user->full_name = $request->input('fullname');
            $user->email = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            $user->tel = $request->input('tel');
            $user->speciality = $request->input('specialist');
            $user->is_available =  $request->input('isAvailable') == 'yes' ? 1:0;
            $user->planning =  $request->input('planning'); 

        
            $user->save();
        
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
        
        return view('coach_profile', compact('coach'));

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
        $user->full_name = $request->input('fullname');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->is_available = $request->input('isAvailable') == 'yes' ? 1:0;
        $user->speciality = $request->input('specialist');
        $user->tel = $request->input('tel');
        $user->planning = $request->input('planning');
        $user->save();
       return redirect()->route('coach.show', $user->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('dashboard');
    }
}
