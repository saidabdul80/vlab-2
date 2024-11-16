<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCourse extends Model
{
    use HasFactory;
    protected $appends = ['code'];
    public function getCodeAttribute(): string
    {
        $course = Course::find($this->id);
        return $course?->code??'';
    }

}
