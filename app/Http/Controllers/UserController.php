<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // index
    public function index(Request $request)
    {
        //get all users with pagination
        $users = DB::table('users')
        ->when($request->input('name'), function ($query, $name) {
            return $query->where('name', 'like', '%' . $name . '%')->
            orWhere('email', 'like', '%' . $name . '%');
        })
        ->orderBy('id', 'desc')
        ->paginate(10);
        return view('pages.users.index', compact('users'));
    }

    //create
    public function create()
    {
        return view('pages.users.create');
    }

    //store
    public function store(Request $request)
    {
        //validate the request
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,staff,user'
        ]);

        //store the data
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->save();

        //redirect to the users page
        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    //show
    public function show($id)
    {
        $user = User::find($id);
        return view('pages.users.profile', compact('user'));
    }

    //edit
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('pages.users.edit', compact('user'));
    }

    //update
    public function update(Request $request, $id)
    {
        //validate the request
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required|in:admin,staff,user'
        ]);

        //store the data
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->save();

        // if password is not empty, update the password
        if ($request->password) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        //redirect to the profile page
        return redirect()->route('users.index', $id)->with('success', 'User updated successfully');
    }

    //destroy
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        //redirect to the login page
        return redirect() -> route('users.index')->with('success', 'User deleted successfully');
    }

}
