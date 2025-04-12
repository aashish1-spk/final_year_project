<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
        
public function initiateKhaltiPayment($name, $email, $phone, $amount)
{
    // dd('a');
    // Generate a unique order ID (you can modify this to suit your needs, e.g., using database order ID)
    $orderId = 'Order_' . uniqid();  // Generating a unique order ID
    $orderName = "Order for " . $name; // Use the customer's name as the order name

    // Initialize cURL session
    $curl = curl_init();

    // Prepare JSON payload
    $data = array(
        "return_url" => "http://127.0.0.1:8000/client/success/khalti",  // Change to your return URL
        "website_url" => "https://example.com/",  // Change to your website URL
        "amount" => $amount * 100, // Amount in paisa (1 NRP = 100 paisa, so multiply by 100)
        "purchase_order_id" => $orderId, // Use dynamically generated order ID
        "purchase_order_name" => $orderName, // Dynamic purchase order name
        "customer_info" => array(
            "name" => $name, // Dynamic customer name
            "email" => $email, // Dynamic customer email
            "phone" => $phone // Dynamic customer phone number
        )
    );

    // Convert data array to JSON
    $jsonData = json_encode($data);

    // Set cURL options
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://dev.khalti.com/api/v2/epayment/initiate/',  // API endpoint for sandbox
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $jsonData,
        CURLOPT_HTTPHEADER => array(
            'Authorization: key 7fd230ac7e0f4008b042704c4c910b59', // Replace with your live secret key
            'Content-Type: application/json',
        ),
        CURLOPT_SSL_VERIFYPEER => false, // Disable SSL verification for testing
    ));

    // Execute cURL request and get response
    $response = curl_exec($curl);

    // Check for errors
    if (curl_errno($curl)) {
        $message = 'Error:' . curl_error($curl); // Print error message if any
        $status = "error";
        $paymentUrl = null;
    } else {
        // Parse the response
        $parsedResponse = json_decode($response, true);

        // Check if the response contains the 'payment_url'
        if (isset($parsedResponse['payment_url'])) {
            // Payment initiation successful
            $paymentUrl = $parsedResponse['payment_url'];
            $message = "Payment initiation successful! Please proceed with the payment.";
            $status = "success";
        } else {
            // Error in response
            $message = isset($parsedResponse['detail']) ? $parsedResponse['detail'] : "Something went wrong. Please try again.";
            $status = "error";
            $paymentUrl = null;
        }
    }

    // Close cURL session
    curl_close($curl);

    // Return the view with data
    return view('khalti', compact('message', 'status', 'paymentUrl', 'orderId', 'orderName', 'amount'));
}


public function khaltiSuccess(Request $request)
{
    $data = $request->only([
        'pidx',
        'transaction_id',
        'tidx',
        'total_amount',
        'status',
        'purchase_order_id',
        'purchase_order_name',
    ]);

    dd($request->all());
    // Store payment in database
    // $payment = Payment::create($data);

    // return response()->json([
    //     'message' => 'Payment recorded successfully',
    //     'payment' => $payment,
    // ]);
}


}
