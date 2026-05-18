<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::latest()->paginate(10);
        $totalCompanies = Company::count();
        $activeCompanies = Company::where('status', 'active')->count();
        $inactiveCompanies = Company::where('status', 'inactive')->count();

        return view('companies.index', compact(
            'companies',
            'totalCompanies',
            'activeCompanies',
            'inactiveCompanies'
        ));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:companies,email'],
            'phone' => ['nullable', 'string', 'regex:/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'],
            'address' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ], [
            'phone.regex' => 'Please enter a valid standard phone format (e.g. +1-555-555-5555, (555) 555-5555, etc.)'
        ]);

        $data = $validated;
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = 'storage/' . $path;
        }

        Company::create($data);

        return redirect()->route('companies.index')
            ->with('success', 'Company created successfully!');
    }

    public function show(Company $company)
    {
        // Historical Profile: Eager load services and invoices
        $company->load([
            'recurringServices.invoiceStructureTemplate',
            'invoices.recurringService',
            'invoices.invoiceItems'
        ]);

        return view('companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:companies,email,' . $company->id],
            'phone' => ['nullable', 'string', 'regex:/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'],
            'address' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ], [
            'phone.regex' => 'Please enter a valid standard phone format.'
        ]);

        $data = $validated;
        if ($request->hasFile('logo')) {
            // Delete old logo if it exists
            if ($company->logo && file_exists(public_path($company->logo))) {
                @unlink(public_path($company->logo));
            }
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = 'storage/' . $path;
        }

        $company->update($data);

        return redirect()->route('companies.index')
            ->with('success', 'Company updated successfully!');
    }

    public function destroy(Company $company)
    {
        // Toggle active/inactive instead of delete, or delete if no recurring services
        if ($company->recurringServices()->count() > 0) {
            $company->update(['status' => 'inactive']);
            return redirect()->route('companies.index')
                ->with('success', 'Company marked as inactive because it has linked recurring services.');
        }

        $company->delete();
        return redirect()->route('companies.index')
            ->with('success', 'Company deleted successfully!');
    }
}
