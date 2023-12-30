<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_name',
        'email',
        'admission_fee',
        'admission_status',
        'tuition_fee',
        'school_type',
        'contact_number',
        'address',
        'admin_id',
        'user_id',
        'is_approved'
    ];




}
