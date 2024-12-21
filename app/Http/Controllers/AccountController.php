<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Contracts\Service\Attribute\Required;

class AccountController extends Controller
{
    //this method shows user registration page
    public function registration()
    {
        return view("front.account.registration");
    }

    //this method will save a user
    public function processRegistration(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|same:confirm_password',
            'confirm_password' => 'required',
        ]);

        if ($validator->passes()) {

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            session()->flash('success', 'you have registered successfully.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    //this method shows user login page
    public function login()
    {
        return view('front.account.login');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->passes()) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect()->route('account.profile');
            } else {
                return redirect()->route('account.login')->with('error', 'Either email or password is incorrect');
            }
        } else {
            return redirect()
                ->route('account.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }

    public function profile()
    {
        $id = Auth::user()->id;
        $user = User::where('id', $id)->first();

        if (Auth::user()->role == 'user') {
            return view('front.account.profile', [
                'user' => $user
            ]);
        } elseif (Auth::user()->role == 'company') {
            return view('front.account.companyprofile', [
                'user' => $user
            ]);
        }
    }

    public function updateProfile(Request $request)
    {
        $id = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:20',
            'email' => 'required|email|unique:users,email,' . $id . ',id',
        ]);

        if ($validator->passes()) {
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->designation = $request->designation;
            $user->save();

            session()->flash('success','profile updated successfully');

           return redirect()->back();


        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login');
    }

    //for image

    public function updateProfilePic(Request $request) {
        // dd($request->all());

        $id = Auth::user()->id;
        $validator = Validator::make($request->all(),[
            'image' => 'required|image'
        ]);
    

     
        if ($validator->passes()) {


            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = $id.'-'.time().'.'.$ext;
            $image->move(public_path('/profile_pic'), $imageName);


            User::where('id',$id)->update(['image' => $imageName]);



            session()->flash('success','profile picture updated successfully');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);
        

    
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    


    // for company

    public function processCompanyRegistration(Request $request)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|unique:users,email',
            'company_password' => 'required|string|min:8|same:company_password_confirmation',
            'company_password_confirmation' => 'required',
        ]);

        // If validation fails, return JSON with errors
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->all());
        }

        // to create the company user in the users table
        try {
            $user = User::create([
                'name' => $request->company_name,
                'email' => $request->company_email,
                'password' => Hash::make($request->company_password),
                'role' => 'company',
            ]);

            // Redirect to login page with success message in the session
            return redirect()->route('account.login')
                ->with('success', 'Company registered successfully! Please log in.');
        } catch (\Exception $e) {
            // Return JSON with an error message if an exception occurs
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again later.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function companyprofile()
    {
        return view('front.account.companyprofile');
    }




//update password
// public function updatePassword(Request $request){
//     $validator = Validator::make($request->all(),[
//         'old_password' => 'required',
//         'new_password' => 'required|min:5',
//         'confirm_password' => 'required|same:new_password',
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'status' => false,
//             'errors' => $validator->errors(),
//         ]);
//     }
// }




}
