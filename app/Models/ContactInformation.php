<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInformation extends Model
{
    use HasFactory;

    protected $primaryKey = 'student_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'student_id',
        'province',
        'city',
        'barangay',
        'mobile',
        'email'
    ];

    // Relationship back to PersonalInformation
    public function personalInformation()
    {
        return $this->belongsTo(PersonalInformation::class, 'student_id', 'student_id');
    }
}