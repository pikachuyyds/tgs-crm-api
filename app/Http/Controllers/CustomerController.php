<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    // list all customers
    public function index(Request $request)
    {
        $query = Auth::user()->customers();

        // search by name or email 
        if ($request->has('search')) {
            $search = $request->search;

            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
            });
        }

        // pagination, 10
        $customers = $query->paginate($request->get('per_page', 10));

        return response()->json($customers, 200);
    }

    // add new customer
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $customer = Auth::user()->customers()->create($request->only('name', 'email', 'phone'));

        return response()->json($customer, 201);
    }

    // show specific customer
    public function show(Customer $customer)
    {
        if ($customer->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($customer, 200);
    }

    // update customer
    public function update(Request $request, Customer $customer)
    {
        if ($customer->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name'  => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:customers,email,' . $customer->id,
            'phone' => 'sometimes|required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $customer->update($request->only('name', 'email', 'phone'));

        return response()->json($customer, 200);
    }

    // delete customer
    public function destroy(Customer $customer)
    {
        if ($customer->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $customer->delete();

        return response()->json(['message' => 'Customer deleted'], 200);
    }
}
