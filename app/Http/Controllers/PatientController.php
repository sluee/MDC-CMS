<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(){
        $patient = Patient::all();
        return inertia('Patient/Index', [
            'patient' => $patient
        ]);
    }

    public function create(){
        return inertia('Patient/Create');
    }

    public function store(Request $request){

        $fields = $request->validate([
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'middlename' => 'nullable|string',
            'ext_name' => 'nullable|string',
            'sex' => 'required|string',
            'type' => 'required|string',
            'email' => 'required|email|',
            'contact_no' => 'required',
            'emergency_contact' => 'required',
            'dob' => 'required|date',
            'address' => 'required',
        ]);

        $type = $fields['type'];
        $patient = Patient::create($fields);

        if ($type === 'Student') {
            // Handle the "student" type here.
            $student_no = $request->input('student_no');
            $course = $request->input('course');

            // Create a new student associated with the user
            $student = new Student([
                'student_no' => $student_no,
                'course'    =>$course

            ]);

            // Save the student first to get an ID.
            $patient->student()->save($student);
        }elseif($type === 'Teacher') {
            // Handle the "teacher" type here.
            $teacher_no = $request->input('teacher_no');

            // Create a new teacher associated with the user
            $teacher = new Teacher([
                'teacher_no' => $teacher_no,

            ]);

            // Save the teacher first to get an ID.
            $patient->teacher()->save($teacher);
        }
        // $patient->update(['type' => $type]);
        return redirect()->route('patient.index');
    }

    public function edit(Patient $patient){
        $patient->load('student', 'teacher')->find($patient->id);

        return inertia('Patient/Edit', [
            'patient' => $patient,
        ]);
    }

    public function update(Request $request, Patient $patient){
        $fields = $request->validate([
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'middlename' => 'nullable|string',
            'ext_name' => 'nullable|string',
            'sex' => 'required|string',
            'type' => 'required|string',
            'email' => 'required|email|unique:patients,email,' . $patient->id,
            'contact_no' => 'required',
            'emergency_contact' => 'required',
            'dob' => 'required|date',
            'address' => 'required',
        ]);

        $type = $fields['type'];

        // Update the patient details
        $patient->update($fields);

        // If the patient type is being changed, update the associated model
        if ($type !== $patient->type) {
            if ($type === 'Student') {
                // Update the "student" type here.
                $student_no = $request->input('student_no');
                $course = $request->input('course');

                // If the patient already has a student record, update it; otherwise, create a new one.
                if ($patient->student) {
                    $patient->student->update([
                        'student_no' => $student_no,
                        'course'=> $course]
                    );
                } else {
                    $student = new Student([
                        'student_no' => $student_no,
                        'course'=> $course]);
                    $patient->student()->save($student);
                }

                // Remove the teacher record if it exists
                if ($patient->teacher) {
                    $patient->teacher->delete();
                }
            } elseif ($type === 'Teacher') {
                // Update the "teacher" type here.
                $teacher_no = $request->input('teacher_no');

                // If the patient already has a teacher record, update it; otherwise, create a new one.
                if ($patient->teacher) {
                    $patient->teacher->update(['teacher_no' => $teacher_no]);
                } else {
                    $teacher = new Teacher(['teacher_no' => $teacher_no]);
                    $patient->teacher()->save($teacher);
                }

                // Remove the student record if it exists
                if ($patient->student) {
                    $patient->student->delete();
                }
            }
        }

        return redirect()->route('patient.index');
    }

    public function search(Request $request)
    {
        try {
            $term = $request->query('term');

            $patients = Patient::where('type', 'Student')
            ->orWhere('type', 'Teacher')
            ->where(function ($query) use ($term) {
                $query->where('firstname', 'like', '%' . $term . '%')
                    ->orWhere('lastname', 'like', '%' . $term . '%');
            })
            ->where('status', 1) // Filter active patients
            ->get();

            return response()->json(['patients' => $patients]);
        } catch (\Exception $e) {
            // Handle the exception and return an error response if needed
            return response()->json(['error' => 'An error occurred while searching for patients.'], 500);
        }
    }

    public function show(Patient $patient){

        $patient = Patient::all()->find($patient->id);
        $user = $patient->user;

        // Retrieve the appointments for the user (patient)
        $patientAppointments = Appointment::with(['doctor.user','service'])->where('pat_id', $patient->id)->get();
        // $form = Form::with('patient.user', 'doctor.user')->where('pat_id', $patient->id)->get();
        return inertia ('Patient/Show',
            [
                'patient' => $patient,
                'patientAppointments' => $patientAppointments,
                // 'form' => $form
            ]);
    }


    public function destroy(Patient $patient) {
        $patient->delete();

        return back();
    }

    public function getPatientInfo($patId)
    {
        $patient = Patient::find($patId);

        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        // Get the patient Firstname and Lastname
        return response()->json([
            'id' => $patient->id,
            'name' => $patient->firstname . ' ' . $patient->lastname,

        ]);
    }
}
