<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseExperiment;
use App\Models\Experiment;
use App\Models\ExperimentResult;
use App\Models\WeeklyWorkExperiment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ExperimentController extends Controller
{

    private $currentUser;
    private $schoolId;
    private $facultyId;
    private $userId;
    private $currentSession;

    public function __construct()
    {
        if (Auth::check()) {
            $this->currentUser = Auth::user();
            $this->schoolId = $this->currentUser->school_id;
            $this->facultyId = $this->currentUser->faculty_id;
            $this->userId = $this->currentUser->id;
            $this->currentSession = SessionController::getCurrentSessionId();
        }
    }

    public function getCourseExperiments()
    {
        $experiments = DB::select('SELECT c.id as course_id, e.id as experiment_id,e.name as name FROM course_experiment AS c INNER JOIN experiments as e on c.experiment_id = e.id');
        return response()->json($experiments, 200);
    }

    public function create(Request $request)
    {
        $experiment = new Experiment();        
        $validator = Validator::make($request->all(), [
            'name' => 'required',       
            'experiment_goal' => 'required',                     
            'required' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => "All fields are required"], 400);
        }

        $id = Util::uuid();
        $name = $request->get('name');
        
        $experiment_intro = $request->get('experiment_intro')??'-';
        $experiment_goal = $request->get('experiment_goal')??'-';    
        $experiment_diagram = $request->experiment_diagram;        
        $apparatus = $request->get('apparatus')??'-';
        $experiment_resource = $request->get('experiment_resource')??'-';
        $procedures = $request->get('procedures')??'-';
        $exercise = $request->get('exercise')??'-';
        $required = $request->get('required');    
        $video_url = $request->get('video_url')??'-';
        $page = $request->get('page')??'-';
        $tables = $request->get('tables')??'false';
        $graph = $request->get('graph')??'false';
        $status = $request->get('status') ?? 'Active';
        $experiment_diagram_url = "-";
        if ($experiment_diagram != null) {            
            $file = $experiment_diagram;                      
            //$file_size = round($file->getSize() / 1024);
            $file_ex = $file->getClientOriginalExtension();
            $file_mime = $file->getMimeType();

            if (!in_array(strtolower($file_ex), array('jpg', 'gif', 'png'))) return response()->json(['error' => 'invalid experiments diagram'.strtolower($file_ex)], 401);
                 $newname = $page.'.'.$file_ex;
                 $path = 'images/resources/';
                 $experiment_diagram_url=  $path.$newname;
                 $file->move(base_path().$path, $newname);
        }

        $experiment->id = $id;
        $experiment->name = $name;        
        $experiment->experiment_intro = $experiment_intro;
        $experiment->experiment_goal = $experiment_goal;
        $experiment->experiment_diagram = $experiment_diagram_url;/*uploaded url*/
        $experiment->apparatus = $apparatus;
        $experiment->experiment_resource = $experiment_resource;
        $experiment->procedures = $procedures;
        $experiment->exercise = $exercise;
        $experiment->required = $required;
        $experiment->video_url = $video_url;
        $experiment->page = $page;
        $experiment->faculty_id = $this->facultyId;
        $experiment->tables = $tables;
        $experiment->graph = $graph;
        $experiment->status = $status;

        if ($experiment->save()) {
            return response()->json(['success' => true], 200);
        }
        return response()->json(['success' => false], 400);
    }

    public function deleteExperiment(Request $request)
    {
        $experimentId = $request->get('experiment_id');

        $check = DB::table('course_experiment')->where(['experiment_id'=>$experimentId, 'status'=>'Active'])->first();

        if (is_null($check)) {
            //delete
            $experiment = Experiment::find($experimentId);

            if ($experiment) {
                $experiment->status = 'Inactive';
                $save = $experiment->save();
                if ($save) {
                    return response()->json(['success' => true], 200);
                }
            } else {
                return response()->json(['error' => 'No Experiment with this id'], 404);
            }
            return response()->json(['success' => false], 400);

        }else{
            //cant delete
            return response()->json(['error' => "can't delete this experiment"], 409);                    
        }
        
    }

    public function updateExperiment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'experiment_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => "experiment_id field is required"], 400);
        }

        $experimentId = $request->get('experiment_id');
        $experiment = Experiment::find($experimentId);

        if ($experiment) {
            $request->get('name') != null ? $experiment->name = $request->get('name') : null;
            $request->get('experiment_number') != null ? $experiment->experiment_number = $request->get('experiment_number') : null;
            $request->get('experiment_intro') != null ? $experiment->experiment_intro = $request->get('experiment_intro') : null;
            $request->get('experiment_goal') != null ? $experiment->experiment_goal = $request->get('experiment_goal') : null;
            $request->get('experiment_diagram') != null ? $experiment->experiment_diagram = $request->get('experiment_diagram') : null;
            $request->get('apparatus') != null ? $experiment->apparatus = $request->get('apparatus') : null;
            $request->get('experiment_resource') != null ? $experiment->experiment_resource = $request->get('experiment_resource') : null;
            $request->get('procedures') != null ? $experiment->procedures = $request->get('procedures') : null;
            $request->get('exercise') != null ? $experiment->exercise = $request->get('exercise') : null;
            $request->get('required') != null ? $experiment->required = $request->get('required') : null;
            $request->get('video_url') != null ? $experiment->video_url = $request->get('video_url') : null;
            $request->get('tables') != null ? $experiment->tables = $request->get('tables') : null;
            $request->get('graph') != null ? $experiment->graph = $request->get('graph') : null;
            //$request->get('description') != null ? $experiment->description = $request->get('description') : null;

            $save = $experiment->save();

            if ($save) {
                return response()->json(['success' => true], 200);
            }
        } else {
            return response()->json(['error' => 'No Experiment with this id'], 404);
        }

        return response()->json(['success' => false], 400);
    }


    public function getAllExperiment()
    {
        $experiments = Experiment::where('status', 'Active')->get();
        return response()->json($experiments, 200);
    }

    public function getAllCourseExperiment()
    {
        $experiments = CourseExperiment::with('experiments')->get();
        return response()->json($experiments, 200);
    }

    public function getExperiment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'experiment_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => "experiment_id field is required"], 400);
        }

        $experimentId = $request->get('experiment_id');
        $experiment = Experiment::find($experimentId);

        if (!empty($experiment)) {
            return response()->json($experiment, 200);
        } else {
            return response()->json(['error' => 'Experiment not found'], 404);
        }
    }

    public function getExperimentByWeeklyExperimentId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'weekly_work_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => "experiment_id field is required"], 400);
        }

        $weeklyWorkExperimentId = $request->get('weekly_work_id');
       $experiment = DB::table('weekly_work_experiments','w')->join('experiments', 'w.experiment_id','experiments.id')->where('w.id',$weeklyWorkExperimentId)->first();
        
        if (!empty($experiment)) {
            return response()->json($experiment, 200);
        } else {
            return response()->json(['error' => 'Experiment not found'], 404);
        }
    }
    public function getExperimentResult(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'weekly_work_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => "All fields are required"], 400);
        }
        $userId = $request->get('user_id');
        $weeklyWorkExperimentId = $request->get('weekly_work_id');


        $weeklyWorkExperiment = WeeklyWorkExperiment::find($weeklyWorkExperimentId);
         // Check for existing experiment result
         $existingResult = ExperimentResult::where([
            'user_id' => $userId,
            'session_id' => $this->currentSession,
            'weekly_work_id' => $weeklyWorkExperiment->weekly_work_id,
            'experiment_id' => $weeklyWorkExperiment->experiment_id
        ])->first();

        return response()->json($existingResult, 200);

    }

    public function saveExperimentResult(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'weekly_work_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => "All fields are required"], 400);
        }
    
        $userId = $request->get('user_id');
        $fortimer = $request->get('fortimer');
        $weeklyWorkExperimentId = $request->get('weekly_work_id');
        $timeStarted = $request->get('time_started');
        $timeLeft = $request->get('time_left');
        $timeSubmitted = $request->get('time_submitted');
        $resultJson = $request->get('result_json');
        
        // Fetch user's course details
        $userDetail = Course::join('weekly_works', 'courses.id', 'weekly_works.course_id')
            ->join('weekly_work_experiments', 'weekly_work_experiments.weekly_work_id', 'weekly_works.id')
            ->where(['weekly_works.session_id' => $this->currentSession, 'weekly_work_experiments.id' => $weeklyWorkExperimentId])
            ->first();
    
        if (!$userDetail) {
            return response()->json(['error' => "Invalid user or experiment"], 400);
        }
    
        $experimentId = $userDetail->experiment_id;
        $courseId = $userDetail->course_id;
        $weeklyWorkExperiment = WeeklyWorkExperiment::find($weeklyWorkExperimentId);
        
        // Check for existing experiment result
        $existingResult = ExperimentResult::where([
            'user_id' => $userId,
            'session_id' => $this->currentSession,
            'weekly_work_id' => $weeklyWorkExperiment->weekly_work_id,
            'experiment_id' => $weeklyWorkExperiment->experiment_id
        ])->first();
            
        // Update existing experiment result
        if ($existingResult) {
            // Update common fields
            $existingResult->time_left = $timeLeft;
            
            if ($fortimer === 0) {
                $existingResult->result_json = $resultJson;
                $existingResult->time_submited = $timeSubmitted;
                $existingResult->completion_status = 'Completed';
                $existingResult->restart = 'Deny';
            }
            
            if ($timeLeft == '00:00') {
                // Submit if time elapsed
                $existingResult->restart = 'Deny';
                $existingResult->completion_status = 'Completed';
            }
    
            if ($existingResult->save()) {
                return response()->json(['message' => "Experiment Result has been updated"], 200);
            }
        }
        // If no existing result, create a new one
        else {
            $newResult = new ExperimentResult;
            $newResult->id = Util::uuid();
            $newResult->user_id = $userId;
            $newResult->experiment_id = $experimentId;
            $newResult->time_started = $timeStarted;
            $newResult->time_left = $timeLeft;
            $newResult->time_submited = $timeSubmitted;
            $newResult->result_json = $resultJson;
            $newResult->completion_status = 'Started';
            $newResult->restart = 'Allow';
            $newResult->course_id = $courseId;
            $newResult->weekly_work_id = $weeklyWorkExperiment->weekly_work_id;
            $newResult->session_id = $this->currentSession;
            
            if ($newResult->save()) {
                return response()->json(['success' => true], 200);
            }
        }
    
        return response()->json(['success' => false], 400);
    }
    

    public function getExperimentResultsByExpSessId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'weekly_experiment_id' => 'required',
            'session_id' => 'required',
        ]);
        $session_id = $request->session_id;
        
        if ($validator->fails()) {
            return response()->json(['error' => "weekly work experiment id field is required"], 400);
        }

        $experimentId = $request->get('weekly_experiment_id');        
        $experimentResult = ExperimentResult::with('student')->where([
            'session_id' => $session_id,
            'weekly_work_id' => $experimentId
        ])->get();

        if (!empty($experimentResult)) {
            return response()->json($experimentResult, 200);
        } else {
            return response()->json(['error' => 'result not found'], 404);
        }
    }

    public function getExperimentResultsByCourseSessId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => "course_id field is required"], 400);
        }

        $courseId = $request->get('course_id');
        return $this->experimentResults($courseId);
    }

    public function getExperimentResultsByCourseUserSessId(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => "course_id field is required"], 400);
        }

        $courseId = $request->get('course_id');

        $result = collect($this->experimentResults($courseId));
        $result = $result->where('user_id', $this->userId);

        return response()->json($result, 200);
    }

    public function experimentResults($courseId)
    {
        $experiments = [];
        $results = [];
        $courseExperiments = Course::where('id',$courseId)->with('experiments')->get();

        if(sizeof($courseExperiments) > 0){
            foreach ($courseExperiments as $courseExperiment) {
                $experiments[] = $courseExperiment['experiments'];
            }
            foreach ($experiments as $experiment) {
                foreach ($experiment as $result) {
                    $experimentResults = ExperimentResult::where(['experiment_id' => $result->id, 'session_id' => $this->currentSession])->get();
                    if (sizeof($experimentResults) > 0) {
                        array_push($results, $experimentResults);
                    }
                }
            }
            if(sizeof($results) > 0){
                return $results[0];
            }
        } else {
            return response()->json(['error' => 'Course not found'], 404);
        }

        return response()->json(['success' => false], 400);
    }
  /*    public function deleteExperiment(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'experiment_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => "experiment_id field is required"], 400);
        }

        $experiment_id = $request->get('experiment_id');
        $check = DB::table('course_experiment')->where('experiment_id',$experiment_id)->first();

        if (is_null($check)) {
            $experiment = Experiment::find($experiment_id);
            //delete
            if ($experiment) {
                $experiment->status = 'Inactive';
                $save = $experiment->save();
                if ($save){
                    return response()->json(['success' => true], 200);
                }
            } else{
                return response()->json(['error' => 'No experiment with this id'], 404);
            }
            return response()->json(['success' => false], 400);
        }else{
            //cant delete
            return response()->json(['error' => "can't delete this experiment"], 409);                    
        }
    }*/

    
    public function reattemptExperimentbyrid(Request $request)
    {
            
        $validator = Validator::make($request->all(), [
            'result_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => "result id is required"], 400);
        }

        $result_id = $request->get('result_id');


        $ExperimentResult = ExperimentResult::find($result_id);
        $ExperimentResult->restart = 'Allow';
        $ExperimentResult->time_left = $request->get('time_left');
        $ExperimentResult->completion_status = 'Started';
        $save = $ExperimentResult->save();
        if ($save){
            return response()->json(['success' => true], 200);
        }
    }    
}
