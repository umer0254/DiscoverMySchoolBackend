<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Application;

class School extends Model
{
    use HasFactory;
    // School.php
public function applications()
{
    return $this->hasMany(Application::class);
}


    protected $fillable = [
        'school_name',
        'email',
        'address',
        'contact_number',
        'is_approved',
        'admission_fee',
        'admission_status',
        'tuition_fee',
        'admin_id',
        'user_id',
        'city',
        'area',
        'board',
        'school_image',
        'principal_name',
        'principal_contact',
        'principal_qualifications',
        'principal_biography',
        'mission_statement',
        'school_history',
        'facilities',
        'extracurricular_activities',

    ];




}
