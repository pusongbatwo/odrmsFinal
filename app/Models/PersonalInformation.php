<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalInformation extends Model
{
    use HasFactory;

    protected $primaryKey = 'student_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'student_id',
        'first_name',
        'middle_name',
        'last_name',
        'course'
    ];

    // Relationship to ContactInformation
    public function contact()
    {
        return $this->hasOne(ContactInformation::class, 'student_id', 'student_id');
    }

    // Relationship to DocumentRequests
    public function documentRequests()
    {
        return $this->hasMany(DocumentRequest::class, 'student_id', 'student_id');
    }
}