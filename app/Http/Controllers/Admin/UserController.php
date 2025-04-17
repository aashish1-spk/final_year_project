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


    public function deactivate(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);

        if (!$user) {
            session()->flash('error', 'User not found');
            return response()->json([
                'status' => false,
            ]);
        }

        $user->is_active = false; // Set is_active to false
        $user->save();

        session()->flash('success', 'User deactivated successfully');
        return response()->json([
            'status' => true,
        ]);
    }

    public function activate(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);

        if (!$user) {
            session()->flash('error', 'User not found');
            return response()->json([
                'status' => false,
            ]);
        }

        $user->is_active = true; // Set is_active to true
        $user->save();

        session()->flash('success', 'User activated successfully');
        return response()->json([
            'status' => true,
        ]);
    }



    public function pending()
    {
        // Fetch users with role 'company' and is_approved = false (pending)
        $companies = User::where('role', 'company')
            ->where('is_approved', false)
            ->get();

        return view('admin.companies.pending', compact('companies'));
    }

    public function approve($id)
    {
        // Find the company by id
        $company = User::findOrFail($id);

        // Check if the user has the 'company' role and is not yet approved
        if ($company->role == 'company' && !$company->is_approved) {
            // Update the 'is_approved' field to true
            $company->is_approved = true;
            $company->save();

            // Optionally, you can send an email notification or any other action here.
            // For example: Mail::to($company->email)->send(new CompanyApprovedMail());

            // Redirect with a success message
            return redirect()->route('admin.companies.pending')->with('success', 'Company approved successfully.');
        }

        // If the company is not valid, you can redirect with an error message
        return redirect()->route('admin.companies.pending')->with('error', 'Invalid company or already approved.');
    }
}
