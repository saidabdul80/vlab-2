<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExperimentResult extends Model
{
    use HasFactory;
    public $incrementing = false;

    protected $hidden = [
        'remember_token',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'user_id',
        'course_id',
        'experiment_id',
        'session_id',
        'weekly_work_id',
        'result_json',
        'time_started',
        'time_submited',
        'time_left',
        'completion_status',
        'restart',
        'status',
        'experiment_id',
        'weekly_work_id',
        'course_id',
        'user_id',
        'session_id',
    ];

    public function experiments()
    {
    	return $this->belongsTo(Experiment::class, 'experiment_id');
    }

     public function student()
    {
        return $this->hasOne(User::class,'id', 'user_id');
    }
}
