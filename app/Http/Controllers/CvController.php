<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Cv;

class CvController extends Controller
{
    // Show the form to create CV
    public function create()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $existingCv = Cv::where('user_id', $userId)->first();

            if ($existingCv) {
                return redirect()->route('account.editCV', $existingCv->id)
                                 ->with('info', 'You have already created your CV. You can edit it here.');
            }
        }

        return view('front.account.create-cv');
    }

    // Store the CV data
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'phone'         => 'required|string|max:20',
            'skills'        => 'required|string',
            'experience'    => 'required|string',
            'education'     => 'required|string',
            'objective'     => 'nullable|string',
            'certifications'=> 'nullable|string',
            'languages'     => 'nullable|string',
            'references'    => 'nullable|string',
            'github_link'   => 'nullable|url',
            'linkedin_link' => 'nullable|url',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to create a CV.');
        }

        $userId = Auth::id();
        $existingCv = Cv::where('user_id', $userId)->first();

        if ($existingCv) {
            return redirect()->route('account.editCV', $existingCv->id)
                             ->with('info', 'You already created a CV. You can update it instead.');
        }

        Cv::create([
            'name'            => $request->input('name'),
            'email'           => $request->input('email'),
            'phone'           => $request->input('phone'),
            'skills'          => $request->input('skills'),
            'experience'      => $request->input('experience'),
            'education'       => $request->input('education'),
            'objective'       => $request->input('objective'),
            'certifications'  => $request->input('certifications'),
            'languages'       => $request->input('languages'),
            'references'      => $request->input('references'),
            'github_link'     => $request->input('github_link'),
            'linkedin_link'   => $request->input('linkedin_link'),
            'user_id'         => $userId,
            'cv_file_path'    => $request->input('name') . '.pdf',
        ]);

        return redirect()->route('account.listCVs')->with('success', 'CV Saved!');
    }

    // Edit the CV
    public function edit($id)
    {
        $cv = Cv::findOrFail($id);

        if ($cv->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('front.account.edit-cv', compact('cv'));
    }

    // Update the CV
    public function update(Request $request, $id)
    {
        $cv = Cv::findOrFail($id);

        if ($cv->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'phone'         => 'required|string|max:20',
            'skills'        => 'required|string',
            'experience'    => 'required|string',
            'education'     => 'required|string',
            'objective'     => 'nullable|string',
            'certifications'=> 'nullable|string',
            'languages'     => 'nullable|string',
            'references'    => 'nullable|string',
            'github_link'   => 'nullable|url',
            'linkedin_link' => 'nullable|url',
        ]);

        $cv->update([
            'name'            => $request->input('name'),
            'email'           => $request->input('email'),
            'phone'           => $request->input('phone'),
            'skills'          => $request->input('skills'),
            'experience'      => $request->input('experience'),
            'education'       => $request->input('education'),
            'objective'       => $request->input('objective'),
            'certifications'  => $request->input('certifications'),
            'languages'       => $request->input('languages'),
            'references'      => $request->input('references'),
            'github_link'     => $request->input('github_link'),
            'linkedin_link'   => $request->input('linkedin_link'),
        ]);

        return redirect()->route('account.listCVs')->with('success', 'CV Updated Successfully!');
    }

    public function downloadCV()
    {
        $cvData = Cv::where('user_id', Auth::id())->latest()->first();

        if (!$cvData) {
            return redirect()->route('account.createCV')->with('error', 'No CV data found');
        }

        $user = Auth::user();
        $username = $user->name;

        $mpdf = new \Mpdf\Mpdf();
        $html = view('front.account.cv-pdf', compact('cvData'))->render();
        $mpdf->WriteHTML($html);

        return response()->stream(
            function () use ($mpdf, $username) {
                $mpdf->Output($username . '_cv.pdf', 'D');
            },
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $username . '_cv.pdf"',
            ]
        );
    }

    public function downloadCVCompany($id)
    {
        $cvData = Cv::find($id);

        if (!$cvData) {
            return response()->json(['error' => 'No CV found with the given ID'], 404);
        }

        $user = Auth::user();
        $username = $user->name;

        $mpdf = new \Mpdf\Mpdf();
        $html = view('front.account.cv-pdf', compact('cvData'))->render();
        $mpdf->WriteHTML($html);

        return response()->stream(
            function () use ($mpdf, $username) {
                $mpdf->Output($username . '_cv.pdf', 'D');
            },
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $username . '_cv.pdf"',
            ]
        );
    }

    // List all CVs of the logged-in user
    public function listCVs()
    {
        $cvs = Cv::where('user_id', Auth::id())->latest()->get();
        return view('front.account.cv-list', compact('cvs'));
    }

    public function delete($id)
{
    $cv = Cv::find($id);

    if (!$cv) {
        return redirect()->back()->with('error', 'CV not found!');
    }

    $cv->delete();

    return redirect()->route('account.listCVs')->with('success', 'CV deleted successfully.');
}
}
