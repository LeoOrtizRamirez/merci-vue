<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

use App\Datatables\CustomerDatatable;
use Inertia\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class CustomerController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Customer/Index');
    }

    public function datatable(
        Request       $request,
        CustomerDatatable $datatable
    ): JsonResponse
    {
        $data = $datatable->make($request);

        return response()->json($data);
    }
    
    public function create(): Response
    {
        return Inertia::render('Customer/Create');
    }

    public function store(Request $request)
    {
        $customer = new Customer;
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->document = $request->document;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->save();
        return redirect()->route('customers.index');
    }

    public function show(Customer $customer)
    {
        return Inertia::render('Customer/Show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return Inertia::render('Customer/Edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $customer = Customer::find($customer->id);
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->document = $request->document;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->save();
        return redirect()->route('customers.index');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->back();
    }
}
