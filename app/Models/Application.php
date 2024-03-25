<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class Application extends Model
{
    use HasFactory;
    public function student()
{
    return $this->belongsTo(Student::class);
}
    public function school()
{
    return $this->belongsTo(School::class);
}
}
