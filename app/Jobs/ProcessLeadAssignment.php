<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Models\User;
use App\Notifications\LeadAssignedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessLeadAssignment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Lead $lead,
        public ?User $previousAdvisor = null
    ) {}

    public function handle(): void
    {
        if ($this->previousAdvisor && $this->previousAdvisor->id !== $this->lead->assigned_to) {
            $this->previousAdvisor->notify(new LeadAssignedNotification(
                $this->lead,
                'unassigned'
            ));
        }

        if ($this->lead->assigned_to) {
            $this->lead->assignedTo->notify(new LeadAssignedNotification(
                $this->lead,
                'assigned'
            ));
        }
    }
}