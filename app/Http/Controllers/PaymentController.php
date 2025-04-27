<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use App\Models\Job;
use App\Http\Controllers\Admin\JobController;

class PaymentController extends Controller
{
    public function initiateKhaltiPayment($name, $email, $phone, $amount, $jobId, $userId = null)
    {
        $userId = $userId ?? auth()->id();
        $orderId = 'Order_' . uniqid();
        $orderName = "Order for " . $name;

        session([
            'khalti_order_id' => $orderId,
            'khalti_job_id' => $jobId,
            'khalti_name' => $name,
            'khalti_email' => $email,
            'khalti_phone' => $phone,
        ]);

        $returnUrl = route('khalti.success', [
            'order_id' => $orderId,
        ]);

        $data = [
            "return_url" => $returnUrl,
            "website_url" => "https://yourwebsite.com",
            "amount" => $amount * 100,
            "purchase_order_id" => $orderId,
            "purchase_order_name" => $orderName,
            "customer_info" => [
                "name" => $name,
                "email" => $email,
                "phone" => $phone
            ],
        ];

        $jsonData = json_encode($data);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://dev.khalti.com/api/v2/epayment/initiate/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => [
                'Authorization: key 0deb39af81de4918bff29de6cfbbca39',
                'Content-Type: application/json',
            ],
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            Log::error('Khalti Initiation Error', ['error' => curl_error($curl)]);
            curl_close($curl);

            session()->flash('error', 'Failed to initiate payment. Please try again.');
            return redirect()->route('account.myJobs');
        }

        $parsedResponse = json_decode($response, true);
        curl_close($curl);

        if (isset($parsedResponse['payment_url'])) {
            return redirect()->away($parsedResponse['payment_url']);
        } else {
            Log::error('Khalti API Error', ['response' => $parsedResponse]);

            session()->flash('error', 'Khalti payment initiation failed.');
            return redirect()->route('account.myJobs');
        }
    }

    public function khaltiSuccess(Request $request)
    {
        Log::debug('Khalti Success Data', $request->all());

        $pidx = $request->query('pidx');
        $orderId = $request->query('order_id');

        if (!$pidx || !$orderId) {
            session()->flash('error', 'Missing transaction reference.');
            return redirect()->route('account.myJobs');
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://dev.khalti.com/api/v2/epayment/lookup/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode(["pidx" => $pidx]),
            CURLOPT_HTTPHEADER => [
                "Authorization: key 0deb39af81de4918bff29de6cfbbca39",
                "Content-Type: application/json",
            ],
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            Log::error('Khalti Lookup Error', ['error' => curl_error($curl)]);
            curl_close($curl);

            session()->flash('error', 'Failed to verify payment status.');
            return redirect()->route('account.myJobs');
        }

        $lookupResponse = json_decode($response, true);
        curl_close($curl);

        Log::info('Khalti Lookup Response', $lookupResponse);

        if (!isset($lookupResponse['status']) || strtolower($lookupResponse['status']) !== 'completed') {
            session()->flash('error', 'Payment was not successful.');
            return redirect()->route('account.myJobs');
        }

        $jobId = session('khalti_job_id');
        $name = session('khalti_name');
        $email = session('khalti_email');
        $phone = session('khalti_phone');

        $payment = Payment::create([
            'order_id' => $orderId,
            'user_id' => auth()->id(),
            'job_id' => $jobId,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'amount' => $lookupResponse['total_amount'] / 100,
            'status' => 'completed',
            'pidx' => $pidx,
            'transaction_id' => $lookupResponse['transaction_id'] ?? null, // Use transaction_id as the identifier
        ]);





        session()->forget([
            'khalti_order_id', 'khalti_job_id', 'khalti_name',
            'khalti_email', 'khalti_phone'
        ]);

        $job = Job::find($payment->job_id);

        if (!$job) {
            session()->flash('error', 'Job not found.');
            return redirect()->route('account.myJobs');
        }

        if ($job->user_id !== auth()->id()) {
            session()->flash('error', 'Unauthorized action.');
            return redirect()->route('account.myJobs');
        }

        $jobController = app(JobController::class);
        $jobController->requestFeatured($job->id);

        session()->flash('success', 'Payment completed and job is now requested to admin for featured.');
        return redirect()->route('account.myJobs');
    }
}
