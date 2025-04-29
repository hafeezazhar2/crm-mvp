<?php

namespace App\Console\Commands;

use App\Enums\LeadStatus;
use App\Enums\UserRole;
use App\Models\Lead;
use App\Models\PolicyDocument;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class GenerateDemoData extends Command
{
    protected $signature = 'demo:generate';
    protected $description = 'Generate demo data for testing';

    public function handle(): int
    {
        $this->createRolesIfNeeded();
        $this->createAdmin();
        $advisors = $this->createAdvisors();
        $this->createLeads($advisors);
        $this->createPolicyDocuments();

        $this->info('Demo data generated successfully!');
        return 0;
    }

    private function createAdmin(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@crm.test'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        )->assignRole(UserRole::ADMIN->value);
    }

    private function createAdvisors(): array
    {
        $advisors = [];

        for ($i = 1; $i <= 5; $i++) {
            $advisors[] = User::firstOrCreate(
                ['email' => "advisor{$i}@crm.test"],
                [
                    'name' => "Advisor {$i}",
                    'password' => Hash::make('password'),
                ]
            )->assignRole(UserRole::ADVISOR->value);
        }

        return $advisors;
    }

    private function createLeads(array $advisors): void
    {
        $statuses = LeadStatus::cases();

        for ($i = 1; $i <= 50; $i++) {
            Lead::firstOrCreate(
                ['email' => "lead{$i}@test.com"],
                [
                    'name' => "Lead {$i}",
                    'phone' => '123456789' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'notes' => "Notes for lead {$i}",
                    'status' => $statuses[array_rand($statuses)]->value,
                    'assigned_to' => rand(0, 1) ? $advisors[array_rand($advisors)]->id : null,
                    'created_by' => 1, // Admin user
                ]
            );
        }
    }

    private function createPolicyDocuments(): void
    {
        $policies = [
            'Privacy Policy' => file_get_contents(resource_path('demo/privacy_policy.txt')),
            'Terms of Service' => file_get_contents(resource_path('demo/terms_of_service.txt')),
            'Refund Policy' => file_get_contents(resource_path('demo/refund_policy.txt')),
        ];

        foreach ($policies as $title => $content) {
            PolicyDocument::firstOrCreate(
                ['title' => $title],
                [
                    'content' => $content,
                    'version' => '1.0',
                ]
            );
        }
    }

    protected function createRolesIfNeeded()
    {
        if (!Role::where('name', 'admin')->exists()) {
            Role::create(['name' => 'admin', 'guard_name' => 'web']);
        }
        
        if (!Role::where('name', 'advisor')->exists()) {
            Role::create(['name' => 'advisor', 'guard_name' => 'web']);
        }
    }
}