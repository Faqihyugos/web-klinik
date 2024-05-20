<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientAppoinment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'date_of_appoinment',
        'status',
        'note',
        'prescription',
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id')->whereHas('roles', function ($query) {
            $query->where('name', 'dokter');
        });
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
