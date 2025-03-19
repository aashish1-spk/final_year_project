<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.users.list', [
            'users' => $users
        ]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', [
            'user' => $user
        ]);
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:1|max:20',
            'email' => 'required|email|unique:users,email,' . $id . ',id',
            'mobile' => 'nullable|digits:10|regex:/^[0-9]+$/', // Not required, but must be 10 digits if provided
            'designation' => 'nullable|string|max:50'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::find($id);
        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('mobile')) {
            $user->mobile = $request->mobile;
        }
        $user->designation = $request->designation;
        $user->save();

        return back()->with('success', 'User information updated successfully.');
    }
    

    public function destroy(Request $request){
        $id = $request->id;

        $user = User::find($id);

        if ($user == null) {
            session()->flash('error','User not found');
            return response()->json([
                'status' => false,
            ]);
        }

        $user->delete();
        session()->flash('success','User deleted successfully');
        return response()->json([
            'status' => true,
        ]);
    }
}
