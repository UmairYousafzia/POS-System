<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{


    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with('projects', 'createBy:id,name', 'users')->get();

        return view('operations.tasks.index', compact( 'tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::get();
        $users = User::get();
        return view('operations.tasks.create', compact('projects','users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
          $data = $request -> validate([

              'project_id' => ['required'],
              'task' => ['required', 'string'],
              'start_date' => ['required', 'date'],
              'end_date' => ['required', 'date'],
              'user_id' => ['required', 'array'],
          ]);
         try {
            DB::beginTransaction();

            $task = Task::create($data + [ 'created_by' => auth()->id()]);
            $task->users()->attach($request->user_id);

            DB::commit();
            return redirect()->route('operations.tasks.index')->with('status',"Successfully Added");
      }
      catch(\Exception|\Error $error){
        DB::rollBack();
        dd('Error:', $error->getMessage(), $error->getTraceAsString());
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id = null)
    {
        if(!empty($id)){
            $tasks = Task::with('users')->findOrFail($id);
            $users = User::get();
            $projects = Project::get();
            return view('operations.tasks.edit',compact('tasks','projects', 'users'));
        }
        else {
            echo "id not found";
            return redirect()->route('operations.tasks.edit');
        }


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
         $data = $request -> validate([

                      'project_id' => ['required'],
                      'task' => ['required', 'string'],
                      'start_date' => ['required', 'date'],
                      'end_date' => ['required', 'date'],
                      'user_id' => ['required', 'array'],
                  ]);
         $task = Task::findOrFail($id);
         $task->update($data);
         return redirect()->route('operations.tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return redirect()->route('operations.tasks.index');

    }
}
