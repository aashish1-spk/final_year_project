<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\ReviewReply;
class ReviewController extends Controller
{
    public function store(Request $request, $company)
    {
        // Validation
        $validated = $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);
    
        // Save Review
        $review = new Review();
        $review->reviewer_id = auth()->id();
        $review->company_id = $company; // From URL
        $review->job_id = $validated['job_id'];
        $review->rating = $validated['rating'];
        $review->comment = $validated['comment'];
        $review->save();
    
        return back()->with('success', 'Your review has been submitted.');
    }

    public function destroy($reviewId)
{
    // Find the review by ID
    $review = Review::findOrFail($reviewId);

    // Check if the authenticated user is the reviewer
    if ($review->reviewer_id !== auth()->id()) {
        return back()->with('error', 'You are not authorized to delete this review.');
    }

    // Delete the review
    $review->delete();

    // Redirect back with success message
    return back()->with('success', 'Your review has been deleted.');
}

public function Companydestroy(Review $review)
{
    $user = auth()->user();

    // Company who posted the job can delete the review
    if ($user->role === 'company') {
        $job = $review->job;

        if ($job && $job->user_id === $user->id) {
            $review->delete();
            return back()->with('success', 'Review deleted successfully.');
        }
    }

    // Optional: Also allow the reviewer themselves to delete their review
    if ($review->reviewer_id === $user->id) {
        $review->delete();
        return back()->with('success', 'Your review was removed.');
    }

    return back()->with('error', 'You are not authorized to delete this review.');
}

public function reply(Request $request, $reviewId)
{
    $review = Review::findOrFail($reviewId);

    // Check if user is logged in and is a company
    if (!auth()->check() || auth()->user()->role !== 'company') {
        return redirect()->route('login')->with('error', 'You need to be logged in as a company to reply.');
    }

    // Check if the logged-in company is the one who posted the job
    if ($review->job->user_id !== auth()->id()) {
        return back()->with('error', 'You are not authorized to reply to this review.');
    }

    // Validate and store the reply
    $request->validate([
        'reply' => 'required|string|max:500',
    ]);

    $review->reply = $request->input('reply');
    $review->save();

    return back()->with('success', 'Your reply has been posted.');
}


public function reviewPage($job_id)
{
    $job = Job::findOrFail($job_id);
    return view('front.reviews.review', compact('job'));
}



public function postReply(Request $request, $reviewId)
{
    $request->validate([
        'message' => 'required|string|max:1000',
    ]);

    $review = Review::findOrFail($reviewId);

    // Check that either reviewer or job owner can post
    $user = auth()->user();
    if (
        $user->id !== $review->reviewer_id &&
        $user->id !== $review->job->user_id
    ) {
        return back()->with('error', 'Unauthorized to reply to this review.');
    }

    ReviewReply::create([
        'review_id' => $review->id,
        'user_id' => $user->id,
        'message' => $request->input('message'),
    ]);

    return back()->with('success', 'Message sent successfully.');
}



    
    
}
