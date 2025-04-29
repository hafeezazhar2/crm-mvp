<?php

namespace App\Repositories;

use App\Enums\LeadStatus;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class LeadRepository
{
    public function paginateForUser(User $user, int $perPage = 15): LengthAwarePaginator
    {
        $query = Lead::with(['assignedTo', 'createdBy']);
        
        if ($user->isAdvisor()) {
            $query->where('assigned_to', $user->id);
        }

        return $query->latest()->paginate($perPage);
    }

    public function findForUser(int $id, User $user): ?Lead
    {
        $query = Lead::with(['assignedTo', 'createdBy', 'notes.user']);
        
        if ($user->isAdvisor()) {
            $query->where('assigned_to', $user->id);
        }

        return $query->find($id);
    }

    public function create(array $data, User $user): Lead
    {
        $data['created_by'] = $user->id;
        return Lead::create($data);
    }

    public function update(Lead $lead, array $data): Lead
    {
        $lead->update($data);
        return $lead->fresh();
    }

    public function delete(Lead $lead): void
    {
        $lead->delete();
    }

    public function addNote(Lead $lead, string $note, User $user): void
    {
        $lead->notes()->create([
            'user_id' => $user->id,
            'note' => $note
        ]);
    }

    public function getStatusCounts(User $user): array
    {
        $query = Lead::query();
        
        if ($user->isAdvisor()) {
            $query->where('assigned_to', $user->id);
        }

        return $query->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }
}
