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

        $returnUrl = route('khalti.success', [
            'order_id' => $orderId,
            'job_id' => $jobId,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
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
            "job_id" => $jobId,
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

        $data = $request->only([
            'order_id', 'pidx', 'transaction_id', 'tidx',
            'total_amount', 'status', 'name', 'email',
            'phone', 'job_id',
        ]);

        if (!isset($data['status'])) {
            session()->flash('error', 'Missing payment status.');
            return redirect()->route('account.myJobs');
        }

        if (strtolower($data['status']) === 'completed') {
            $payment = Payment::create([
                'order_id' => $data['order_id'],
                'user_id' => auth()->id(),
                'job_id' => $data['job_id'],
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'amount' => $data['total_amount'] / 100,
                'status' => 'completed',
                'pidx' => $data['pidx'],
                'transaction_id' => $data['transaction_id'],
                'tidx' => $data['tidx'],
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
        } else {
            session()->flash('error', 'Payment was not successful.');
            return redirect()->route('account.myJobs');
        }
    }
}
