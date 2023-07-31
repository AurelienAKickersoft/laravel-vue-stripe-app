<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controller\StripeController;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::post('/payment/intent', function (Request $request){

    $body = $request->all();
    $total = (float)$body['total']*100;

    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

    try {
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $total, // amount in cents
            'currency' => 'usd',
            'payment_method_types' => ['card', 'paypal'], //paypal
            /*'automatic_payment_methods' => [
                'enabled' => true,
            ],*/
        ]);

        $output = [
            'clientSecret' => $paymentIntent->client_secret,
        ];

        return response()->json($output);

    } catch (Error $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }

});

Route::get('/', function () {
    return view('welcome');
});
