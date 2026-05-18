<?php

namespace App\Http\Controllers;

use App\Models\InvoiceStructureTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvoiceStructureTemplateController extends Controller
{
    public function index()
    {
        $templates = InvoiceStructureTemplate::latest()->paginate(10);
        return view('templates.index', compact('templates'));
    }

    public function create()
    {
        return view('templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'unique:invoice_structure_templates,slug'],
            'sender_email' => ['nullable', 'email', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        InvoiceStructureTemplate::create($validated);

        return redirect()->route('templates.index')
            ->with('success', 'Invoice structure template created successfully!');
    }

    public function edit(InvoiceStructureTemplate $template)
    {
        return view('templates.edit', compact('template'));
    }

    public function update(Request $request, InvoiceStructureTemplate $template)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'unique:invoice_structure_templates,slug,' . $template->id],
            'sender_email' => ['nullable', 'email', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $template->update($validated);

        return redirect()->route('templates.index')
            ->with('success', 'Invoice structure template updated successfully!');
    }

    public function destroy(InvoiceStructureTemplate $template)
    {
        if ($template->recurringServices()->count() > 0) {
            $template->update(['status' => 'inactive']);
            return redirect()->route('templates.index')
                ->with('success', 'Template marked as inactive because it is linked to recurring services.');
        }

        $template->delete();
        return redirect()->route('templates.index')
            ->with('success', 'Template deleted successfully!');
    }

    // Dynamic Live Preview configuration: /invoices/templates/{slug}
    public function renderTemplate($slug)
    {
        $template = InvoiceStructureTemplate::where('slug', $slug)->firstOrFail();
        $sampleCompany = \App\Models\Company::whereNotNull('logo')->first() ?? \App\Models\Company::first();
        
        // Render a gorgeous sample invoice layout branded to this template!
        return view('templates.preview', compact('template', 'sampleCompany'));
    }
}
