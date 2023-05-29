<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function addTask(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'patient_id' => 'required',
            'room_num' => 'required|integer',
            'detils' => 'required|string',
            'patient_department' => 'required|string',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::find($request->patient_id);

        if ($user->role !== 'patient') {
            return response()->json([
                'status' => false,
                'message' => 'patient not found',
                'data' => null,
            ], 404);
        }

        $task = Task::create([

            'patient_id' => $request->patient_id,
            'room_num' => $request->room_num,
            'detils' => $request->detils,
            'patient_department' => $request->patient_department,

        ]);
        if ($user->department == $request->patient_department){

            return response()->json([
                'status' => true,
                'message' => 'Task at successfully',
                'data' => [

                    'name' => $user->name,
                    'age' => $user->age,
                    'nationalID' => $user->nationalID,
                    'department' => $user->department,
                    'room_num' => $task->room_num,
                    'detils' => $task->detils,
                ]
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'This is not your DEPARTMENT',
                'data' => null,
            ], 400);
        }
    }

    public function getTask(Request $request){
         
        $validator = Validator::make($request->all(), [
            'department' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = auth()->user();

         $task=Task::where('patient_department',$request->department)->get();

        if(!$task){
            return response()->json([
                'status'=>false,
                'message'=>'Department not found',
                'data'=>null,
            ],404);
        }
                  
        return response()->json([
            'status'=>true,
            'message'=>null,
            'data'=>$task,
        ],200);
    }

    public function updateTask(Request $request,$id){

        $validator = Validator::make($request->all(), [
            'patient_id' => 'nullable',
            'room_num' => 'nullable|integer',
            'detils' => 'nullable|string',
            'patient_department' => 'nullable|string',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $task=Task::find($id);
        if(!$task){
            return response()->json([
                'status'=>false,
                'message'=>'task not found',
            ],404);
        }

        $user = User::find($request->patient_id);
        if ($user->role !== 'patient') {
            return response()->json([
                'status' => false,
                'message' => 'only doctor can update',
                'data' => null,
            ], 404);
        }

        $task->patient_id = $request->input('patient_id')?$request->input('patient_id'):$task->patient_id;
        $task->room_num = $request->input('room_num')?$request->input('room_num'):$task->room_num;
        $task->detils = $request->input('detils')?$request->input('detils'):$task->detils;
        $task->patient_department = $request->input('patient_department')?$request->input('patient_department'):$task->patient_department;
        $task->save();

        return response()->json([
            'status'=>true,
            'message'=>'update successfully',
            'data'=>$task,
        ],200);
    }

    public function deleteTask(Request $request,$id){

        $task=Task::find($id);
        if(!$task){
            return response()->json([
                'status'=>false,
                'message'=>'task not found',
            ],404);
        }

        $task->delete();

        return response()->json([
            'status'=>true,
            'message'=>'task deleted successfully',
            'data'=>$task,

        ],200);


    }

}