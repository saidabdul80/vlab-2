<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\WeeklyWork;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class WhoAreYou
{
    public function handle(Request $request, Closure $next)
    {
        if (empty($_SERVER['HTTP_USER_AGENT'])) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            if (preg_match('/(Mobile|Android|Tablet|GoBrowser|[0-9]x[0-9]*|uZardWeb\/|Mini|Doris\/|Skyfire\/|iPhone|Fennec\/|Maemo|Iris\/|CLDC\-|Mobi\/)/uis', $userAgent)) {
                return redirect('/access-denied');
            }
        }

        if (!Session::has('info')) {
            return redirect('/login');
        }

        $userData = session('info')->data->user ?? '';
        $role = $userData->role_id ?? '';

        if ($role == config('calculations.default_roles.student')) {
           return $this->handleStudentLogic($userData, $request, $next);
        } elseif ($role == config('calculations.default_roles.instructor')) {
            return $this->handleInstructorLogic($request, $next);
        }

        return redirect('/no-access');
    }

    private function handleStudentLogic($userData, $request, $next)
    {
        $user_id = $userData->id;
        $weeklyExperimentWorkId = $request->route()->parameter('id');
        $page = explode('/', $request->route()->uri)[0];
        $weeklyExperimentWorkId =  $request->route()->parameter('id');
        // Fetch current session and existing data from DB
        $currentSession = DB::table('sessions')->where(['is_current' => 1, 'status' => 'Active'])->first()->id;
  
        $existInDB = WeeklyWork::join('weekly_work_experiments', 'weekly_work_experiments.weekly_work_id', 'weekly_works.id')
            ->join('experiments', 'weekly_work_experiments.experiment_id', 'experiments.id')
            ->join('user_courses', 'user_courses.course_id', 'weekly_works.course_id')
            ->leftJoin('experiment_results', function ($query) {
                $query->on(['user_courses.user_id' => 'experiment_results.user_id', 'weekly_work_experiments.weekly_work_id' => 'experiment_results.weekly_work_id', 'weekly_work_experiments.experiment_id' => 'experiment_results.experiment_id']);
            })
            ->where([
                'weekly_work_experiments.id' => $weeklyExperimentWorkId,
                'experiments.page' => $page,
                'user_courses.user_id' => $user_id,
            ])
            ->first();
          
        if (!$existInDB) {
          return redirect('/no-access');
        }

        $completion_status = $existInDB->completion_status ?? null;
        $time = explode(':', $completion_status != null ? $existInDB->time_left : $existInDB->limitation);

        $time_left = [
            'hour' => (int) $time[0],
            'minute' => (int) $time[1],
        ];

        session(['time_left' => $time_left]);
        session(['access_code' => $existInDB->access_code]);
        session(['experimentMode' => $existInDB->mode]);
        session(['setdata' => $existInDB->setdata]);
        session(['user_type' => 'student']);
        
        if (!$existInDB->expired) {
            if(is_null($existInDB->time_left)){
                return $next($request); //this student has not start expirement
            }
            if ($existInDB->mode != 1 || ($existInDB->restart == 'Allow' && $existInDB->time_left != '00:00')) {
                return $next($request);
            } else {
                return redirect('/closed-409')->with(['weekly_work_id' => $existInDB->weekly_work_id, 'reattempt_page' => $existInDB->page]);
            }
        } else {
            return redirect('/no-access');
        }
    }

    private function handleInstructorLogic($request, $next)
    {
        $page = explode('/', $request->route()->uri)[0];
        // Fetch the weeklyExperimentWorkId using DB query
        $weeklyExperimentWorkId =  $request->route()->parameter('id');
        $time_left = [
            'hour' => 3,
            'minute' => 20,
        ];

        session(['time_left' => $time_left]);
        session(['access_code' => 'free']);
        session(['experimentMode' => false]);
      //  session(['setdata' => $existInDB->setdata]);
        session(['time_left' => $time_left]);
        session(['user_type' => 'instructor']);

        if (!$request->route()->parameter('id')) {
            return redirect($page . '/' . $weeklyExperimentWorkId);
        }

        return $next($request);
    }
}
