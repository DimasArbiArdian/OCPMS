<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateProgress extends Model
{
    /** @use HasFactory<\Database\Factories\CandidateProgressFactory> */
    use HasFactory;

    protected $table = 'candidate_progress';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'candidate_id',
        'dp_status',
        'medical_status',
        'visa_status',
        'ticket_status',
        'departure_date',
        'remarks',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'departure_date' => 'date',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
