<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginuserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Requests\Storerequest;
use Illuminate\Support\Facades\Hash;
use App\Traits\HttpResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use HttpResponse;

    
    public function register(Storerequest $request) {
    $request->validated($request->all());

    $user = User::create([
        'name' => $request['name'],
        'email' => $request['email'],
        'password' => Hash::make($request['password'])
    ]);

    return $this->success([
        "user" => $user,
        "token" => $user->createToken('Api Token'. $user->name)->plainTextToken
    ]);
}

    public function login(LoginuserRequest $request) {
      
        $request ->validated();
      
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('', "Credentials do not match", 401);
        }
        $user = User::where('email', $request->email)->first();

        return $this-> success([
            'user' => $user,
            'message' => "User Successfully Login ",
            'token' => $user->createToken('Api Token of' . $user->name)-> plainTextToken
        ]);


    }
    public function logout() {
        return response()->json("Logout Successfully");
    }



    public function index()
    {
        $users = User::when(auth()->user()->hasPermissionTo('user.view_own'), function ($quarry) {
            $quarry->where('create_by', auth()->id());
        })->with('roles', 'createBy')
            ->whereNot('id', 1)
            ->get();

        return view('setting.users.index', compact('users'));
    }


    public function create()
    {
        abort_if(!auth()->user()->can('user.create'), 403);

        $roles = Role::get(['id', 'name']);

        return view('setting.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $data = $request->validate([
           'name' => ['required', 'string'],
           'email' => ['required', 'string', 'unique:users,email'],
           'password' => 'required|confirmed|min:6',
           'role' => ['required', 'exists:roles,name']
       ]);

       $data['password'] = Hash::make($data['password']);

        try {
            DB::beginTransaction();
            $user = User::create($data + [
                    'create_by' => auth()->id()
            ]);

            $user->assignRole($request->role);

            DB::commit();
        } catch (\Exception|\Error $error) {
            DB::rollBack();
            dd($error->getMessage());
        }
       alert()->success('Added', 'user Added Successfully');

       return redirect()->route('settings.users.index');
    }

    public function edit(string $id)
    {
        abort_if(!auth()->user()->can('user.edit'), 403);
        $user = User::with('roles')->findOrFail($id);
        $roles=Role::get();
        return view('setting.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_if(!auth()->user()->can('user.update'), 403);

        $item = User::findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id)
            ],
            'role' => ['required', 'string']
        ]);

        $item->update($data);



        $item -> syncRoles($request->role);

        return redirect()->route('settings.users.index');
    }


    public function destroy(string $id)
    {
        abort_if(!auth()->user()->can('user.delete'), 403);

        $item = User::findOrFail($id);
        $item->delete();
        return redirect()->route('settings.users.index');
    }

   
}