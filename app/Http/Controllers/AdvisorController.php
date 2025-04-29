<?php

namespace App\Http\Controllers;

use App\Http\Requests\Advisor\StoreAdvisorRequest;
use App\Http\Requests\Advisor\UpdateAdvisorRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdvisorController extends Controller
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', User::class);

        $advisors = $this->userRepository->paginateAdvisors();

        return Inertia::render('Advisors/Index', [
            'advisors' => $advisors,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', User::class);

        return Inertia::render('Advisors/Create');
    }

    public function store(StoreAdvisorRequest $request): RedirectResponse
    {
        $this->authorize('create', User::class);

        $this->userRepository->createAdvisor($request->validated());

        return redirect()->route('advisors.index')->with('success', 'Advisor created successfully.');
    }

    public function edit(User $advisor): Response
    {
        $this->authorize('update', $advisor);

        return Inertia::render('Advisors/Edit', [
            'advisor' => $advisor,
        ]);
    }

    public function update(UpdateAdvisorRequest $request, User $advisor): RedirectResponse
    {
        $this->authorize('update', $advisor);

        $this->userRepository->updateAdvisor($advisor, $request->validated());

        return redirect()->route('advisors.index')->with('success', 'Advisor updated successfully.');
    }

    public function destroy(User $advisor): RedirectResponse
    {
        $this->authorize('delete', $advisor);

        $this->userRepository->deleteAdvisor($advisor);

        return redirect()->route('advisors.index')->with('success', 'Advisor deleted successfully.');
    }
}
