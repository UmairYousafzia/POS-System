<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public  function index()
    {
        $projects = Project::when(auth()->user()->hasPermissionTo('projects.view_own'), function ($query) {
            $query->where('create_by', auth()->id());
        })
        ->with('user:id,name', 'createBy:id,name')
        ->get();

        return view('operations.project.index', compact('projects'));
    }

    public  function create()
    {
        abort_if(!auth()->user()->can('project.create'), 403);

        $users = User::get();
        return view('operations.project.create', compact('users'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->can('project.create'), 403);

        $data = $request->validate([
            'name' => ['required', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'user_id' => ['required', 'string'],
        ]);

        Project::create($data + [
            'create_by' => auth()->id()
        ]);

        return redirect()->route('operations.projects.index');
    }

    public function edit($id) {

        abort_if(!auth()->user()->can('project.edit'), 403);

        $projects = Project::findOrFail($id);
        $users = User::get();
        return view('operations.project.edit', compact('projects','users'));

    }
    public function update( Request $request, $id) {

        abort_if(!auth()->user()->can('project.edit'), 403);

        $items=Project::findOrFail($id);
        $data=$request->validate([
            'name'=>['required', 'string'],
            'start_date'=>['required', 'string'],
            'end_date'=>['required', 'string'],
            'user_id'=>['nullable', 'string']
        ]);
        $items->update($data);
        return redirect()->route('operations.projects.index');

    }
    public function destroy($id)
    {
        abort_if(!auth()->user()->can('project.delete'), 403);

        $data=Project::findOrFail($id);
        $data->delete();
        return redirect()->route('operations.projects.index');
    }


}
