<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'student_name',
        'date_of_birth',
        'father_occupation',
        'father_name',
        'mother_name',
        'mother_occupation',
        'address',
        'father_cnic',
        'student_cnic',
        'applying_for_class'
    ];
}
