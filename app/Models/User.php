<?php

namespace App\Models;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Session;

class User extends Authenticatable implements JWTSubject, CanResetPasswordContract
{
    use HasFactory, Notifiable, CanResetPassword;
    public $incrementing = false;

    // public function newQuery($excludeDeleted = true) {
    //     return parent::newQuery($excludeDeleted)
    //         ->where('status', '=', 'Active');
    // }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'name',        
        'role',
        'department',
        'faculty',
        'courses'
    ];
    
    public function scopeActive($query) {
        return $query->where('users.status', 'Active');
    }
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getNameAttribute(): string
    {
        return $this->other_names . ' ' . $this->first_name;
    }

    public function getFacultyAttribute(): string
    {
        $faculty = Faculty::find($this->faculty_id);
        return $faculty->code;
    }

    public function getDepartmentAttribute(): string
    {
        $department = Department::find($this->department_id);
        return $department->code;
    }

    public function getCoursesAttribute(): string
    {
        $courses = UserCourse::where('user_id',$this->id)->get()->toArray();
        if(sizeof($courses)>0){
            return implode(', ',array_column($courses,'code'));

        }
        return '';        
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'user_courses');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }    
    public function role()
    {
        return $this->belongsTo(Role::class);
    }    


    public function result()
    {
        return $this->hasMany(ExperimentResult::class);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    
    public function getRoleAttribute(){
        $role = Role::find($this->role_id);
        if(!is_null($role)){
            return $role->title;
        }else{
            return '';
        }
    }
    
}
