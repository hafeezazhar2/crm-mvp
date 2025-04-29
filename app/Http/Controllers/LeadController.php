<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lead\StoreLeadRequest;
use App\Http\Requests\Lead\UpdateLeadRequest;
use App\Models\Lead;
use App\Repositories\LeadRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LeadController extends Controller
{
    public function __construct(private LeadRepository $leadRepository)
    {
    }

    public function index(Request $request): Response
    {
        $leads = $this->leadRepository->paginateForUser($request->user());
        $statusCounts = $this->leadRepository->getStatusCounts($request->user());

        return Inertia::render('Leads/Index', [
            'leads' => $leads,
            'statusCounts' => $statusCounts,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Leads/Create');
    }

    public function store(StoreLeadRequest $request): RedirectResponse
    {
        $lead = $this->leadRepository->create($request->validated(), $request->user());

        return redirect()->route('leads.show', $lead)->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead): Response
    {
        $this->authorize('view', $lead);

        $lead = $this->leadRepository->findForUser($lead->id, request()->user());

        return Inertia::render('Leads/Show', [
            'lead' => $lead,
        ]);
    }

    public function edit(Lead $lead): Response
    {
        $this->authorize('update', $lead);

        return Inertia::render('Leads/Edit', [
            'lead' => $lead,
        ]);
    }

    public function update(UpdateLeadRequest $request, Lead $lead): RedirectResponse
    {
        $this->authorize('update', $lead);

        $this->leadRepository->update($lead, $request->validated());

        return redirect()->route('leads.show', $lead)->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $this->authorize('delete', $lead);

        $this->leadRepository->delete($lead);

        return redirect()->route('leads.index')->with('success', 'Lead deleted successfully.');
    }

    public function addNote(Request $request, Lead $lead): RedirectResponse
    {
        $this->authorize('view', $lead);

        $request->validate(['note' => 'required|string|max:1000']);

        $this->leadRepository->addNote($lead, $request->note, $request->user());

        return back()->with('success', 'Note added successfully.');
    }
}
