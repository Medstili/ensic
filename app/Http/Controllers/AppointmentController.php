<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Speciality;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
// use Storage;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        
        $query = Appointment::query();

        // Filter by search query on patient or coach name
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
   
                $q->whereHas('patient', function($q1) use ($search) {
                    $q1->where('full_name', 'like', "%{$search}%");
                })
                ->orWhereHas('coachs', function($q2) use ($search) {
                      $q2->where('full_name', 'like', "%{$search}%");
                  });
            });
        }
    
        // Filter by speciality
        if ($request->filled('speciality')) {
            $query->where('choosen_speciality', $request->speciality);
        }
    
        // Optionally, order by date or other column
        $appointments = $query->with('patient','coachs')->get();
        
    
        return view('appointment', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $specialities = Speciality::all();
        return view('booking_appointment', compact('specialities'));
        

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $request->validate([
            'patient_id'=>'required',
            'speciality' => 'required',
            'planning' => 'required',
            'coach_id' => 'required',
        ]);

        $appointment = new Appointment();
        $appointment->patient_id = $request->patient_id;
        $appointment->coach_id = $request->coach_id;
        $appointment->appointment_planning= json_encode($request->planning);
        $appointment->choosen_speciality = $request->speciality;
        $appointment->status = 'pending';
        $appointment->save();
        return response()->json([
            'success'     => true,
            'appointment' => $appointment,
        ], 200);
        // return redirect()->route('appointment.show')->with('success', 'Appointment created successfully');
        
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $appointment = Appointment:: findOrFail($id);
        return view('appointment_details', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // return route('appointment.index');
        $appointment = Appointment:: findOrFail($id);
        return view('appointment_cancellation', compact('appointment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $appointment = Appointment::find($id);
        $appointment->status = 'cancel';
        $appointment->reason = $request->description;
        $appointment->cancelledBy= $request->cancelled_by;
        $appointment->save();
        return redirect()->route('appointment.index')->with('success', 'Appointment cancelled successfully');
        // $appointment->client_full_name = $request->client_name;
        // $appointment->client_tel = $request->client_tel;
        // $appointment->appointment_planning= $request->planning;
        // $appointment->choosen_speciality = $request->speciality;
        // $appointment->coach_id = $request->coach;
        $appointment->save();
        // return redirect()->route('appointment.index',compact('appointment'))->with('success', 'Appointment updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Appointment = Appointment::find($id);
        $this->deleteReport($id);
        $Appointment->delete();
        return redirect()->route('appointment.index');

    }
    // custom methods
    // public function checkAvailability(Request $request) 
    // {
    //     try {
    //         $validated = $request->validate([
    //             'choosen_speciality' => 'required|string',
    //             'date' => 'required|date_format:Y-m-d', // Adjust to match the frontend format
    //             'start_time' => 'required|date_format:H:i',
    //             'end_time' => 'required|date_format:H:i',
    //         ]);

    //         $date = $validated['date'];
    //         $startTime = $validated['start_time'];
    //         $endTime = $validated['end_time'];
    //         $speciality = $validated['choosen_speciality'];

    //         // Get all coaches with the requested specialty
    //         $coaches = User::where('speciality', $speciality)
    //                     ->where('is_available', true)
    //                     ->get();

    //         $availableCoaches = [];

    //         foreach ($coaches as $coach) {
    //             $planning = json_decode($coach->planning, true);

    //             if (isset($planning[$date])) {
    //                 $coachStartTime = $planning[$date]['startTime'];
    //                 $coachEndTime = $planning[$date]['endTime'];

    //                 if ($this->isTimeAvailable($coachStartTime, $coachEndTime, $startTime, $endTime, $coach->id, $date)) {
    //                     $availableCoaches[] = $coach;
    //                 }
    //             }
    //         }

    //         return response()->json([
    //             'success' => count($availableCoaches) > 0,
    //             'message' => count($availableCoaches) > 0 ? 'Available coaches found' : 'No available coaches. Please select another date or time.',
    //             'coaches' => $availableCoaches
    //         ]);
            
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error processing request: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }
    // private function isTimeAvailable($coachStartTime, $coachEndTime, $startTime, $endTime, $coachId, $date)
    // {
    //     // Parse coach's availability times WITH THE DATE
    //     $coachStart = strtotime("$date $coachStartTime");
    //     $coachEnd = strtotime("$date $coachEndTime");
    //     $requestedStart = strtotime("$date $startTime");
    //     $requestedEnd = strtotime("$date $endTime");

    //     \Log::info("CoachStart: $coachStart ($coachStartTime), CoachEnd: $coachEnd ($coachEndTime)");
    //     \Log::info("RequestedStart: $requestedStart ($startTime), RequestedEnd: $requestedEnd ($endTime)");

    //     // Handle midnight edge case for coach's end time
    //     if ($coachEndTime === '00:00') {
    //         $coachEnd = strtotime("$date +1 day 00:00"); // Next day's midnight
    //     }

    //     // 1. Check if requested time is within coach's availability
    //     if ($requestedStart < $coachStart || $requestedEnd > $coachEnd) {
            
    //         return false;
    //     }

    //     // 2. Fetch existing appointments for the date
    //     $existingAppointments = Appointment::where('coach_id', $coachId)
    //         ->whereNotNull('appointment_planning->' . $date)
    //         ->get();

    //     // 3. Check for overlaps
    //     foreach ($existingAppointments as $appointment) {
    //         $apptPlanning = json_decode($appointment->appointment_planning, true);
    //         if (isset($apptPlanning[$date])) {
    //             $apptStartTime = $apptPlanning[$date]['startTime'];
    //             $apptEndTime = $apptPlanning[$date]['endTime'];

    //             // Parse appointment times WITH THE DATE
    //             $apptStart = strtotime("$date $apptStartTime");
    //             $apptEnd = strtotime("$date $apptEndTime");

    //             // Handle midnight for appointments
    //             if ($apptEndTime === '00:00') {
    //                 $apptEnd = strtotime("$date +1 day 00:00");
    //             }

    //             // Check for overlap
    //             if ($requestedStart < $apptEnd && $requestedEnd > $apptStart) {
    //                 return false;
    //             }
    //         }
    //     }

    //     return true;
    // }
   
    public function uploadReport(Request $request, $id)
    {
        $request->validate([
            'report' => 'required|file|mimes:pdf,doc,docx,txt|max:2048', // Max 2MB
        ]);

        $appointment = Appointment::findOrFail($id);

        // Store the file in the 'reports' folder under 'storage/app/public/reports/'
        $filename = time() . '_' . $request->file('report')->getClientOriginalName();
        $filePath = $request->file('report')->storeAs('reports', $filename, 'public');

        // Save the file path in the database
        $appointment->report_path = $filePath;
        $appointment->save();

        return redirect()->back();
    }
    public function downloadReport($id)
    {
        $appointment = Appointment::findOrFail($id);

        if (!$appointment->report_path) {
            return redirect()->back()->with('error', 'No report found.');
        }

        return response()->download(storage_path('app/public/' . $appointment->report_path));
    }
    public function deleteReport($id)
    {
        $appointment = Appointment::findOrFail($id);

        // Check if there is a report to delete
        if ($appointment->report_path) {
            // Delete the file from storage
            Storage::disk('public')->delete($appointment->report_path);

            // Remove the file path from the database
            $appointment->report_path = null;
            $appointment->save();

            return redirect()->back()->with('success', 'Report deleted successfully.');
        }

        return redirect()->back()->with('error', 'No report found for this appointment.');
    }
    public function updateAppointmentStatus($id, $status){
        $appointment = Appointment::findOrFail($id);
        $appointment->status = $status;
        $appointment->save();
        return redirect()->back();
    }
    public function viewReport($id)
    {
        $appointment = Appointment::findOrFail($id);

        if (!$appointment->report_path) {
            abort(404, 'Report not found.');
        }

        // Get the full path to the file stored on the 'public' disk
        $path = storage_path('app/public/' . $appointment->report_path);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="report.pdf"'
        ]);
    }

  
    public function findAvailableCoach(Request $request)
    {
        // Validate input: patient ID and chosen speciality
        try {

            $validated = $request->validate([
                'patient_id' => 'required|integer',
                'speciality' => 'required|string',
            ]);
            
        } catch (\Throwable $th) {
            throw $th;
            // return response()->json(['error' => 'Invalid input'], 422);
        }
     

        // Retrieve the patient record, which already has priorities stored as JSON
        $patient = Patient::findOrFail($validated['patient_id']);
        $speciality = $validated['speciality'];

        // Decode the patient's priorities JSON
        $priorities = json_decode($patient->priorities, true);

        // Retrieve all available coaches for the given speciality
        $coaches = User::where('speciality', $speciality)
                    ->where('is_available', true)
                    ->get();

        if ($coaches->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No available coaches found for this specialty.',
                'available_coaches' => [],
            ]);
        }

        // Iterate through priorities in order: priority 1, then priority 2, then priority 3
        foreach (['priority 1', 'priority 2', 'priority 3'] as $priorityKey) {
            if (isset($priorities[$priorityKey])) {
           
                $priorityData = $priorities[$priorityKey];
                $days = array_keys($priorityData);
                if (empty($days)) {
                    continue;
                }
                $day = $days[0]; // e.g., "2025-02-12"
                $times = $priorityData[$day];
                $pStartTime = $times['startTime'];
                $pEndTime   = $times['endTime'];

                $matchingCoaches = [];
            // For each coach, get the free interval that fits within the patient request
            foreach ($coaches as $coach) {
                $freeInterval = $this->isTimeAvailableForPatient($coach->id, $day, $pStartTime, $pEndTime);
                if ($freeInterval !== null) {
                    $matchingCoaches[] = [
                        'coach' => $coach,
                        'date'=> $day,
                        'free_interval' => $freeInterval,
                    ];
                }
            }

            if (!empty($matchingCoaches)) {
                return response()->json([
                    'success' => true,
                    'priorityUsed' => $priorityKey,
                    'available_coaches' => $matchingCoaches,
                ]);
            }
      
                // $availableCoaches = $coaches->filter(function($coach) use ($day, $pStartTime, $pEndTime) {
                //     return $this->isTimeAvailableForPatient($coach->id, $day, $pStartTime, $pEndTime);
                // });

                // if ($availableCoaches->isNotEmpty()) {
                //     return response()->json([
                //         'success' => true,
                //         'priorityUsed' => $priorityKey,
                //         'available_coaches' => $availableCoaches->values()
                //     ]);
                // }
            }
        }

        // If no coaches available for any priority:
        return response()->json([
            'success' => false,
            'message' => 'No available coaches match the patient’s requirements.',
            'available_coaches' => []
        ]);
    }
  
    private function isTimeAvailableForPatient($coachId, $day, $patientStartTime, $patientEndTime)
    
        {
        // Combine day and time into timestamps (assume $day is in "Y-m-d" format)
        $pStart = strtotime("$day $patientStartTime");
        $pEnd   = strtotime("$day $patientEndTime");
    
        // Retrieve all appointments for this coach that have a planning for the given day
        $appointments = Appointment::where('coach_id', $coachId)
            ->whereRaw("JSON_EXTRACT(appointment_planning, '$.\"$day\"') IS NOT NULL")
            // ->where('status','pending')
            ->get();
    
        $occupied = [];
        foreach ($appointments as $appointment) {
            $apptPlanning = json_decode($appointment->appointment_planning, true);
            if (isset($apptPlanning[$day])) {
                $aStart = strtotime("$day " . $apptPlanning[$day]['startTime']);
                $aEnd   = strtotime("$day " . $apptPlanning[$day]['endTime']);
    
                // Check if the appointment overlaps with the patient's requested interval
                if ($aEnd > $pStart && $aStart < $pEnd) {
                    $occupied[] = [
                        'start' => max($pStart, $aStart),
                        'end'   => min($pEnd, $aEnd)
                    ];
                }
            }
        }
    
        // Sort occupied intervals by start time
        usort($occupied, function($a, $b) {
            return $a['start'] <=> $b['start'];
        });
    
        // Compute free intervals between $pStart and $pEnd
        $freeIntervals = [];
        $currentStart = $pStart;
        foreach ($occupied as $interval) {
            if ($interval['start'] > $currentStart) {
                $freeIntervals[] = [
                    'start' => $currentStart,
                    'end'   => $interval['start']
                ];
            }
            $currentStart = max($currentStart, $interval['end']);
        }
        if ($currentStart < $pEnd) {
            $freeIntervals[] = [
                'start' => $currentStart,
                'end'   => $pEnd
            ];
        }
    
        // Define minimum duration: 45 minutes (in seconds)
        $minDuration = 45 * 60;
        $maxDuration = 60 * 60;
        foreach ($freeIntervals as $free) {
            $duration = $free['end'] - $free['start'];
            if ($duration >= $minDuration) {
                if ($duration > $maxDuration) {
                    // Return a block exactly 1 hour long starting at free start
                    $free['end'] = $free['start'] + $maxDuration;
                }
                return [
                    'startTime' => date('H:i', $free['start']),
                    'endTime'   => date('H:i', $free['end'])
                ];
            }
        }
    
        return null;
    }
    

}
