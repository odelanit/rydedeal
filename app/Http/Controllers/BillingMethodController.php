<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\Exception\InvalidRequestException;

class BillingMethodController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user->hasStripeId()) {
            $user->createAsStripeCustomer();
        }
        try {
            $paymentMethods = $user->paymentMethods();
        } catch (InvalidRequestException $exception) {
            $user->createAsStripeCustomer();
            $paymentMethods = $user->paymentMethods();
        }
        return view('admin.billing-methods')->with([
            'user' => $user,
            'paymentMethods' => $paymentMethods,
            'intent' => $user->createSetupIntent(),
            'defaultMethod' => $user->defaultPaymentMethod(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required',
            'nameOnCard' => 'required|string',
        ]);
        $validator->validate();

        $user = auth()->user();
        $method_input = $request->input('payment_method');
        $methodObject = $user->addPaymentMethod($method_input);

        return back()->with('success', 'Success');
    }

    public function delete(Request $request, $id)
    {
        $paymentMethod = auth()->user()->findPaymentMethod($id);
        $paymentMethod->delete();
        return back()->with('success', 'Successfully Deleted.');
    }

    public function setAsDefault(Request $request, $id)
    {
        auth()->user()->updateDefaultPaymentMethod($id);
        return back()->with('success', 'Successfully set as default.');
    }
}
