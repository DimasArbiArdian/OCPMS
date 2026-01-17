<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\CandidateProgress;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'System Admin',
            'email' => 'admin@ocpms.test',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        $staff = User::factory()->create([
            'name' => 'Recruitment Staff',
            'email' => 'staff@ocpms.test',
            'role' => 'staff',
            'password' => Hash::make('password'),
        ]);

        $candidateUser = User::factory()->create([
            'name' => 'Candidate Demo',
            'email' => 'candidate@ocpms.test',
            'role' => 'candidate',
            'password' => Hash::make('password'),
        ]);

        $candidate = Candidate::factory()
            ->for($candidateUser)
            ->create([
                'name' => 'Rahmat Wijaya',
                'position' => 'Welding Technician',
                'phone' => '+628123000111',
                'passport_number' => 'B1234567',
                'passport_expired' => now()->addYears(4),
                'country' => 'Japan',
            ]);

        CandidateProgress::factory()->for($candidate)->create([
            'dp_status' => 'done',
            'medical_status' => 'done',
            'visa_status' => 'process',
            'ticket_status' => 'not_yet',
            'departure_date' => now()->addMonths(2),
        ]);

        Document::factory()
            ->count(4)
            ->sequence(
                ['type' => 'passport', 'file_path' => 'documents/passport.pdf'],
                ['type' => 'medical', 'file_path' => 'documents/medical.pdf'],
                ['type' => 'visa', 'file_path' => 'documents/visa.pdf'],
                ['type' => 'ticket', 'file_path' => 'documents/ticket.pdf'],
            )
            ->for($candidate)
            ->create();

        Candidate::factory()
            ->count(5)
            ->has(CandidateProgress::factory(), 'progress')
            ->has(Document::factory()->count(2), 'documents')
            ->for(
                User::factory()->state(fn () => ['role' => 'candidate'])
            )
            ->create();
    }
}
