<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrUpdateActivity;
use App\Http\Requests\CreateOrUpdateActivityLessons;
use App\Http\Requests\TeacherTime;
use App\Models\Activity;
use App\Models\Absance;
use App\Models\Category;
use App\Models\Place;
use App\Models\Lesson;
use App\Models\Admin;
use App\Models\Role;
use App\Models\Department;
use App\Models\Center;
use App\Models\School;
use App\Models\Participation;
use App\Models\Program;
use App\Models\Student;
use App\Models\Student_Payment;
use App\Models\Student_Account;
use App\Models\Teacher;
use App\Models\Feild;
use App\Models\Level;
use App\Models\Supplier;
use App\Models\Activity_Resource;
use App\Models\Lesson_Teacher;
use App\Services\DataOutput;
use App\Services\ImageUpload;
use App\Models\Setting;
use Gbrock\Table\Facades\Table;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use DB;
use DateTime;
use Auth;
use Excel;
use Validator;
use Gate;
use Carbon\Carbon;
use AppHelper;
use DataTables;
use Session;

class ActivityController extends BaseController
{
    /**
     * @var string
     */
    protected $targetDirName = 'activities/';
    
    /**
     * @var string
     */
    protected $targetDirNameResource = 'activities_resources/';
    
    /**
     * @var string
     */
    protected $fieldName = 'thumb';

    /**
     * @var string
     */
    protected $externalImagePathDir = '/images/';
    
    /**
     * @var ImageUpload
     */
    private $imageUpload;
    
    /**
     * @var DataOutput
     */
    private $dataOutput;


    /**
     * ActivityController constructor.
     *
     * @param ImageUpload $imageUpload
     * @param DataOutput $dataOutput
     */
    public function __construct(ImageUpload $imageUpload, DataOutput $dataOutput)
    {
        $this->responseMessages = $this->mapResponseMessages('object', 'Activity');
        $this->imageUpload = $imageUpload;
        $this->dataOutput = $dataOutput;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function activity_teacher()
    {
        $activities = Activity::where('teacher_id', '!=', null)->get();

        foreach ($activities as $activity) {
            $teacher_count = DB::table('activity_teacher')
                ->where('teacher_id', '=', $activity->teacher_id)
                ->where('activity_id', '=', $activity->id)
                ->count();
            if ($teacher_count == 0) {
                DB::table('activity_teacher')
                    ->insert(
                        [
                            'activity_id' =>  $activity->id,
                            'teacher_id' =>  $activity->teacher_id
                        ]
                    );
            }
        }
    }
    
    public function changeDateFormat($date)
    {
        $date = date('Y-m-d', strtotime($date));
        return $date;
    }

    //Tell Day
    private function tellDay($date)
    {
        $timestamp = strtotime($date);
        $day = date('D', $timestamp);
        return $day;
    }

    //Diffrent between days
    private function diffD($ffrom, $fto)
    {
        $datetime1 = new DateTime($ffrom);
        $datetime2 = new DateTime($fto);
        $difference = $datetime1->diff($datetime2);
        $kim = $difference->days;
        return $kim;
    }

    /*Genatre Day for Lessons*/
    private function calculateLessons($from, $to, $dayss)
    {
        $dif = $this->diffD($from, $to);
        $array = array();
        for ($i=0;$i<=$dif;$i++) {
            $date = date('Y-m-d', strtotime($from. ' + '.$i.' days'));
            $day  =  $this->tellDay($date);
            
            if ($day == $dayss) {
                $array[$date]=$dayss;
            }
        }
        array_unshift($array, "");
        unset($array[0]);
        return $array;
    }

    public function index(Request $request)
    {   
        if ($request->is('api/*')) {
        if (Gate::allows('teacher')) {
            $activities = Activity::sorted()
                ->where('study_year', '=', date("Y"))
                ->where('lock_status', '!=', 1)
                ->where('teacher_id', '=', Auth::user()->teacher_id)
                ->get();
        }

        if (Gate::allows('staff')) {
            $center_id = Center::where('admin_id', Auth::user()->id)
                ->pluck('id')
                ->toArray();
            $activities = Activity::whereIn('center_id', $center_id)
                ->where('study_year', '=', date("Y"))
                ->where('lock_status', '!=', 1)
                ->get();
        }

        if (Gate::allows('eventManger')) {
            $activities = Activity::sorted()
                ->where('lock_status', '!=', 1)
                ->where('study_year', '=', date("Y"))
                ->where('admin_id', '=', Auth::user()->id)
                ->get();
        }
        if (Gate::allows('admin')) {
            $activities = Activity::sorted()
                ->where('study_year', '=', date("Y"))
                ->where('lock_status', '!=', 1)
                ->get();
        }
        $getModel = new Activity();
        $table = Table::create($activities, $getModel->getSortable());
        $table->addColumn('reg_status', __('activity.reg_status'), function ($model) {
            return $model->reg_status();
        });
        $table->addColumn('studnets', __('activity.students'), function ($model) {
            return $model->getStudentsNumber();
        });
        return $this->dataOutput->representDataAccordingToRequestType($request, $activities, __('sidebar.View Activities'), $table, 'activity.index', "");
        } else {
            $document_title = '';
            $document_title .= __('activity.activity list');
            $document_title .= ' - ';
            $document_title .= date('Y-m-d');
            return view('activity.index')->with([
                'bigtitle' => __('sidebar.View Activities'),
                'document_title' => $document_title
            ]);
        }
    }

    public function listByType(Request $request, $type)
    {
        $activities = Activity::where('type', $type)->sorted()->get();
        $getModel = new Activity();
        $table = Table::create($activities, $getModel->getSortable());
        $table->addColumn('reg_status', __('activity.reg_status'), function ($model) {
            return $model->reg_status();
        });

        $table->addColumn('studnets', __('activity.students'), function ($model) {
            return $model->getStudentsNumber();
        });

        $title = "";
        if ($type == 'course') {
            $title = __('activity.courses');
        } elseif ($type == 'event') {
            $title = __('activity.events');
        } elseif ($type == 'camp') {
            $title = __('activity.camps');
        }
        return $this->dataOutput->representDataAccordingToRequestType($request, $activities, $title, $table, $document_title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $categories = Category::pluck('category_name', 'id')->all();
        $departments = Department::pluck('name', 'id')->all();
        $programs =  Program::pluck('name', 'id')->all();
        $feilds =  Feild::pluck('name', 'id')->all();
        $centers = Center::pluck('name', 'id')->all();
        $schools = [];
        $supplier = Supplier::pluck('company', 'id')->all();
        $levels = Level::pluck('name', 'id');
        //$eventManger = Admin::where('role', 'eventManger')->pluck('name', 'id')->all();
        $event_manager_id = DB::Table('admin_role')->where('role_id', '=', 4)->pluck('admin_id');
        $eventManger = Admin::whereIn('id', $event_manager_id)->pluck('name', 'id')->all();
        $teachers = Teacher::select(DB::raw("CONCAT(COALESCE(`first_name`,''),' ',COALESCE(`father_name`,''),' ',COALESCE(`family_name`,'')) AS name"), 'id')->pluck('name', 'id')->all();
        
        return View('activity.CreateOrUpdate', ['teachers' => $teachers, 'categories' => $categories, 'types' => Activity::translateType(), 'departments' => $departments, 'programs' => $programs, 'feilds' => $feilds, 'centers' => $centers, 'schools' => $schools, 'levels' => $levels, 'supplier' => $supplier, 'eventManger' => $eventManger, 'bigtitle'=>__('activity.add_activity')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateOrUpdateActivity $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateOrUpdateActivity $request)
    {
        $upload_file_url = $request->fullUrl();
        $url = AppHelper::getUploadFilePath($upload_file_url, $this->targetDirName);
        
        /**
         * duplicate Activity Make array of student for old activity
        */
        if (date("Y-m-d", strtotime($request->start_date)) > date("Y-m-d", strtotime($request->end_date))) {
            $error = [
                'start_date_end_date_not_valid' => __('activity.start_date_end_date_not_valid')
            ];
            return redirect()->back()->withErrors(['error' => $error]);
        }
        $settings = Setting::pluck('value', 'key');
        $current_year = @$settings['study_year'];
        $error = [];
        if (date("Y", strtotime($request->start_date)) != $current_year) {
            $error = [
                'error' => __('activity.start_date_year_equal_to_study_year')
            ];

            return redirect()->back()->withErrors(['error' => $error]);
        }
        /*if (date("Y-m-d") > date("Y-m-d", strtotime($request->start_date)) ) {
            $error = [
                'error' => __('activity.start_date_must_be_valid')
            ];

            return redirect()->back()->withErrors(['error' => $error]);
        }*/
        if ($request->activity_id) {
            $student_id = [];
            $activity_id = $request->activity_id;
            $activitys = Activity::find($activity_id);
            $students = $activitys->students;
            foreach ($students as $key => $value) {
                $student_id[$key] = $value->id;
            }
            $settings = Setting::pluck('value', 'key');
            $current_year = @$settings['study_year'];
            $getData['study_year'] = $current_year;
        }

        $getData = $request->all();
        $date = $getData['start_date'];
        $date = $this->changeDateFormat($date);
        $getData['start_date'] = $date;
        $date = $getData['end_date'];
        $date = $this->changeDateFormat($date);
        $getData['end_date'] = $date;

        /*uplodad image*/
        $fullURL = '/home/almodir/public_html/'.config('app.project').'/images/' . $this->targetDirName;
        if ($request->thumb) {
            $file = $request->thumb;
            $file = $request->file('thumb');
            $imageHash = time();
            $imageName = $imageHash . '.' . $file->hashName();
            $moved = $file->move($fullURL, $imageName);
            $getData[$this->fieldName] = $this->targetDirName.''.$imageName;
        }
        
        $activity = Activity::create($getData);
        $saved = $activity->save();
        DB::table('activity_teacher')
            ->insert([
                'activity_id' =>  $activity->id, 'teacher_id' =>  $request->teacher_id
            ]);

        /**
         * Add Multi Level for one Activity
        */
        $activity= Activity::find($activity->id);
        $activity->levels()->attach($request->level);

        /**
         * Add old Student to duplicate Activity
        */
/*        if ($request->activity_id) {
            foreach ($student_id as  $student_ids) {
                $participation = Participation::create([
                    'student_id' => $student_ids,
                    'activity_id' =>  $activity->id
                ]);

                $participation->save();
            }
        }*/
        return redirect()->route('activity.create')
                         ->with('status', __('general.create-success'));
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try {
            $activity = Activity::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            $message = ($this->dataOutput->isApiRequest($request) ? [] : $this->responseMessages[0]);
            return $this->dataOutput->responseAccordingToRequestType($request, $message, 'home', false);
        }
        $total_cost = 0;
        $part = Participation::where('activity_id', $activity->id)->get();

        foreach ($part as $vlaue) {
            $total_cost += $vlaue->payments->sum('payment_cost');
        }

        $data = [
            'activity' => $activity,
            'types' => Activity::$types,
            'students' => Student::all(),
            'total_cost' => $total_cost
        ];
        return $this->dataOutput->representDataAccordingToRequestType($request, $data, $activity->title, null, 'activity.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $activity = Activity::findOrFail($id);
        $categories = Category::pluck('category_name', 'id')->all();
        $departments = Department::pluck('name', 'id')->all();
        $programs = Program::pluck('name', 'id')->all();
        $feilds = Feild::pluck('name', 'id')->all();
        $centers = Center::pluck('name', 'id')->all();

        $schools = School::where('center_id', '=', $activity->center_id)
            ->pluck('name', 'id')
            ->all();
        $places = Place::where('school_id', '=', $activity->school_id)
            ->pluck('name', 'id')
            ->all();

        $supplier = Supplier::pluck('company', 'id')->all();
        $levels = Level::pluck('name', 'id');
        $eventManger = Admin::where('role', 'eventManger')
            ->pluck('name', 'id')
            ->all();
        $event_managers = DB::table('admin_role')->where('role_id', '=', 4)->pluck('admin_id')->toArray();
        $eventManger = Admin::whereIn('id', $event_managers)->pluck('name', 'id')->toArray(); 
        $selectEventManger = Admin::where('id', $activity->admin_id)
            ->pluck('id', 'name');
        $selectLevel =$activity->levels->pluck('id', 'name');
        
        if (Admin::where('activity_id', $id)->get()->isEmpty()) {
            $admin = null;
        } else {
            $admin = Admin::where('activity_id', $id)->firstOrFail();
        }
        
        $teachers = Teacher::select(DB::raw("CONCAT(COALESCE(`first_name`,''),' ',COALESCE(`father_name`,''),' ',COALESCE(`family_name`,'')) AS name"), 'id')->pluck('name', 'id')->all();

        return View('activity.CreateOrUpdate', [
            'teachers' => $teachers,
            'activity' => $activity,
            'categories' => $categories,
            'types' => Activity::$types,
            'places' => $places,
            'departments' => $departments,
            'programs' => $programs,
            'centers' => $centers,
            'schools' => $schools,
            'feilds' => $feilds,
            'levels' => $levels,
            'selectLevel' => $selectLevel,
            'bigtitle'=> __('activity.edit activity'),
            'supplier' => $supplier,
            'admin' => $admin,
            'selectEventManger' => $selectEventManger,
            'eventManger' => $eventManger,
            'study_year' => $activity->study_year,
            'imagePathDir' => url('/') . '/images/activities/'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CreateOrUpdateActivity $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateOrUpdateActivity $request, $id)
    {
        try {
            $activity = Activity::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return $this->dataOutput->responseAccordingToRequestType($request, $this->responseMessages[3], ['activity.edit', $id], false);
        }

        if (date("Y-m-d", strtotime($request->start_date)) > date("Y-m-d", strtotime($request->end_date))) {

            $error = [
                'start_date_end_date_not_valid' => __('activity.start_date_end_date_not_valid')
            ];

            return redirect()->back()->withErrors(['error' => $error]);

        }
        $settings = Setting::pluck('value', 'key');
        $current_year = @$settings['study_year'];
        $error = [];
        if (date("Y", strtotime($request->start_date)) != $current_year) {
            $error = [
                'error' => __('activity.start_date_year_equal_to_study_year')
            ];

            return redirect()->back()->withErrors(['error' => $error]);
        }
        $getData = $request->all();
        $date = $getData['start_date'];
        $date = $this->changeDateFormat($date);
        $getData['start_date'] = $date;
        $date = $getData['end_date'];
        $date = $this->changeDateFormat($date);
        $getData['end_date'] = $date;

        /*if ($activity->teacher_id != $getData['teacher_id']) {

            $teacher_counts = DB::table('activity_teacher')
                ->where('teacher_id', '=', $getData['teacher'])
                ->where('activity_id', '=', $activity->id)
                ->count();
            if ($teacher_counts == 0) {
                DB::table('activity_teacher')
                    ->where('teacher_id', '=', $getData['teacher'])
                    ->where('activity_id', '=', $activity->id)
                    ->count();
            }    
            $lessons = Lesson::where('activity_id', '=', $id)
                ->where('lesson_date','>=', date('Y-m-d'))
                ->update([
                    'teacher_id' => $getData['teacher_id']
                ]);
        }*/
        /*Update Lessons when teacher change*/
        if ($activity->teacher_id != $request->teacher_id) {
            $teacher_count = DB::table('activity_teacher')
                ->where('teacher_id', '=', $request->teacher_id)
                ->where('activity_id', '=', $activity->id)
                ->count();

            $current_date = date('Y-m-d');
            $dt = new DateTime();
            $current_time = $dt->format('H:i:s');
            if ($teacher_count == 0) {
                DB::table('activity_teacher')
                    ->insert([
                        'activity_id' =>  $activity->id, 'teacher_id' =>  $request->teacher_id
                    ]);
            } else {
                DB::table('activity_teacher')
                    ->where('activity_id', $activity->id)
                    ->insert([
                        'teacher_id' =>  $request->teacher_id
                    ]);
            }
            $id_lessons = Lesson::where('activity_id', '=', $id)
                ->where('lesson_date', '>=', $current_date)
                ->pluck('id');

            DB::table('lesson_teacher')
                ->whereIn('lesson_id', $id_lessons)
                ->update(['teacher_id' =>  $request->teacher_id]);

            $lessons = Lesson::where('activity_id', '=', $id)
                ->where('lesson_date','>=', date('Y-m-d'))
                ->update([
                    'teacher_id' => $getData['teacher_id']
                ]);    
        }
        /*Update Level */
        $activity->levels()->sync($request->level);

        $fullURL = '/home/almodir/public_html/'.config('app.project').'/images/' . $this->targetDirName;
        if ($request->thumb) {
            $file = $request->thumb;
            $file = $request->file('thumb');
            $imageHash = time();
            $imageName = $imageHash . '.' . $file->hashName();
            $moved = $file->move($fullURL, $imageName);
            $getData[$this->fieldName] = $this->targetDirName.''.$imageName;
        }
        $activity->fill($getData);
        $saved = $activity->save();

        return redirect()
            ->route('activity.edit', ['id' => $id])
            ->with('status', __('general.update-success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {   
        $activity_id = $request->activity_id;
        try {
            $activity = Activity::where('id', '=', $activity_id)->first();
            if (!empty($activity) {
            	$activity = Activity::find($activity_id)->delete();
            	 Session::flash(
                	'alert-success', __('general.delete_done')
            	);
            } else {
            	Session::flash(
                	'alert-danger', __('general.delete_faild')
            	);
            }

        } catch (ModelNotFoundException $exception) {
            
        }
        return redirect()->route('activity.index');
    }



    public function delete(Request $request)
    {
        $ids = $request->ids;
        DB::table("activities")
            ->whereIn('id', explode(",", $ids))
            ->delete();
        return response()->json(['success'=>"Activities Deleted successfully."]);
    }


    public function duplicate($id)
    {   
        $settings = Setting::pluck('value', 'key');
        $current_year = @$settings['study_year'];
        $activities = "";

        $activity = Activity::findOrFail($id);
        $categories = Category::pluck('category_name', 'id');
        $places = Place::pluck('name', 'id');
        $departments = Department::pluck('name', 'id');
        $programs = Program::pluck('name', 'id');
        $feilds = Feild::pluck('name', 'id');
        $centers = Center::pluck('name', 'id');
        $schools = School::where('center_id', '=', $activity->center_id)
            ->pluck('name', 'id')
            ->all();
        $levels = Level::pluck('name', 'id');
        $selectLevel =$activity->levels->pluck('id', 'name');
        $supplier = Supplier::pluck('company', 'id')->all();
        $eventManger = Admin::where('role', 'eventManger')->pluck('name', 'id')->all();
        $selectEventManger = Admin::where('id', $activity->admin_id)->pluck('id', 'name');
        $teachers = Teacher::select(
            DB::raw("CONCAT(first_name,' ', father_name, ' ', family_name) AS name"), 'id'
        )->pluck('name', 'id');
        $title = __('activity.activity_duplicate');

        return View('activity.duplicate', [
            'teachers' => $teachers,
            'activity' => $activity,
            'categories' => $categories,
            'types' => Activity::$types,
            'places' => $places,
            'departments' => $departments,
            'programs' => $programs,
            'centers' => $centers,
            'schools' => $schools,
            'feilds' => $feilds,
            'levels' => $levels,
            'selectLevel' => $selectLevel,
            'bigtitle'=> $title,
            'supplier' => $supplier,
            'selectEventManger' => $selectEventManger,
            'eventManger' => $eventManger,
            'imagePathDir' => url('/') . '/images/activities/',
            'current_year' => $current_year
        ]);
    }


    public function lock($id, $status, $type = 0)
    {
        if ($status == 'lock') {
            DB::table('activities')
                ->where('id', $id)
                ->update(['lock_status' => 1]);
                
                if ($type != 1) {
                    return redirect()->route('activity.index');
                } else {
                    return redirect()->back();
                }
            
        } else {
            DB::table('activities')
                ->where('id', $id)
                ->update(['lock_status' => 0]);
            if ($type != 1) {
                return redirect()->route('activity.index');
            } else {
                return redirect()->back();
            }
        }
    }


    public function archives(Request $request)
    {   
        $settings = Setting::pluck('value', 'key');
        $current_year = @$settings['study_year'];
        $activities = "";
        $document_year = 0;
        if (Gate::allows('teacher')) {
            $activities = Activity::where('lock_status', '!=', 0)
                ->where('teacher_id', '=', Auth::user()->teacher_id);
        }

        if (Gate::allows('staff')) {
            $center_id = Center::where('admin_id', Auth::user()->id)
                ->pluck('id')
                ->toArray();
            $activities = Activity::whereIn('center_id', $center_id)
                ->where('lock_status', '!=', 0);
        }

        if (Gate::allows('eventManger')) {
            $activities = Activity::where('lock_status', '!=', 0)
                ->where('admin_id', '=', Auth::user()->id);
        }
        if (Gate::allows('admin')) {
            $activities = Activity::where('lock_status', '!=', 0);
        }
        if (isset($request->year)) {
            $activities = $activities->where('study_year', $request->year);
            $document_year = $request->year; 
        } elseif(isset($request->year) || $request->year == 0) {
            $document_year = __('general.all');
        }
        $activities = $activities->get();
        /*if (Gate::allows('teacher')) {
            $activities = Activity::sorted()
                ->where('teacher_id', Auth::user()->teacher_id)
                ->where('lock_status', '=', 1)
                ->get(); 
            $getModel = new Activity();
            $table = Table::create($activities, $getModel->getSortable());
            $table->addColumn('reg_status', __('activity.reg_status'), function ($model) {
                return $model->reg_status();
            });

            $table->addColumn('studnets', __('activity.students'), function ($model) {
                return $model->getStudentsNumber();
            });
        } else {
            $activities = Activity::sorted()->where('lock_status', '=', 1)->get();
            $getModel = new Activity();
            $table = Table::create($activities, $getModel->getSortable());
            $table->addColumn('reg_status', __('activity.reg_status'), function ($model) {
                return $model->reg_status();
            });
            $table->addColumn('studnets', __('activity.students'), function ($model) {
                return $model->getStudentsNumber();
            });
        }
        return $this->dataOutput->representDataAccordingToRequestType($request, $activities, __('sidebar.Archive Activity'), $table, 'activity.index');*/
        $document_title = '';
        $document_title .= __('activity.activity archive list');
        $document_title .= ' - ';
        $document_title .= date('Y-m-d');
        $document_title .= __('general.study_year'). " - ".$document_year;

        return view('activity.archive')->with([
            'bigtitle' => __('sidebar.Archive Activity'),
            'activities' => $activities, 
            'document_title' => $document_title
        ]);
    }



    public function getFeild(Request $request)
    {
        $program_id = $request->message;
        $feilds = Program::join('feilds', 'feilds.id', '=', 'programs.feild_id')
            ->select(['feilds.id','feilds.name'])
            ->pluck('name', 'id');
        return response()->json($feilds);
    }

    public function getSchoolByCenter(Request $request)
    {
        $center_id = $request->center_id;
        $schools = School::where('center_id', '=', $center_id)
            ->select(['id','name'])
            ->pluck('name', 'id');
        return response()->json($schools);
    }

    public function getPlaceBySchool(Request $request)
    {
        $school_id = $request->school_id;
        $places = Place::where('school_id', '=', $school_id)
            ->select(['id','name'])
            ->pluck('name', 'id');
        return response()->json($places);
    }

    public function lessons($id)
    {   
        if (Gate::allows('teacher')) {
            $activity = Activity::findOrFail($id);
            $lessonsSave = Lesson::where('activity_id', '=', $id)
                ->orderBy('lesson_date', 'ASC')
                ->where('teacher_id', '=', Auth::user()->teacher_id)
                ->get();
            $teachers = Teacher::select(
                DB::raw("CONCAT(first_name,' ',father_name, ' ',family_name) AS name"),
                'id'
            )->pluck('name', 'id');

            $title = __("activity.lessons");
            return view('activity.lessons')->with([
                'activity' => $activity,
                'lessonsSave' => $lessonsSave,
                'teachers' => $teachers,
                'bigtitle' => $title,
                'activityController' => $this
            ]);
        } else {
        $activity = Activity::findOrFail($id);
        $lessonsSave = Lesson::where('activity_id', '=', $id)
            ->orderBy('lesson_date', 'ASC')
            ->get();
        $teachers = Teacher::select(
            DB::raw("CONCAT(first_name,' ',father_name, ' ',family_name) AS name"),
            'id'
        )->pluck('name', 'id');

        $title = __("activity.lessons");
        return view('activity.lessons')->with([
            'activity' => $activity,
            'lessonsSave' => $lessonsSave,
            'teachers' => $teachers,
            'bigtitle' => $title,
            'activityController' => $this
        ]);
    }
    }

    public function LessonsView(CreateOrUpdateActivityLessons $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;
        $days = $request->days;
        $start_time = $request->start_time;
        $end_time = $request->end_time;

        $activity_id = $request->activity_id;
        $activity = Activity::findOrFail($activity_id);
        $lessons = $this->calculateLessons($start, $end, $days);

        $lessonsSave = $activity->lessons;
        $teachers = Teacher::select(
            DB::raw("CONCAT(first_name,' ',father_name, ' ',family_name) AS name"),
            'id'
        )->pluck('name', 'id');

        $title = __("activity.lessons");

        return view('activity.lessons')->with([
            'activity' => $activity,
            'lessons' => $lessons,
            'start' => $start,
            'end' => $end,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'lessonsSave' => $lessonsSave,
            'teachers' => $teachers,
            'bigtitle' => $title,
            'activityController' => $this
        ]);
    }


    public function LessonsStore(Request $request)
    {
        $students_array = [];
        $activity_id = $request->activity_id;
        $date = $request->date;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $activity = Activity::find($activity_id);
        $teachers = Teacher::find($activity->teacher_id);
        $student_activity = $activity->students;

        foreach ($student_activity as  $value) {
            array_push($students_array, $value->id);
        }

        for ($i=0; $i < count($date) ; $i++) {
            $Lessonss =  Lesson::firstOrCreate(['lesson_date' => $date[$i], 'activity_id' => $activity_id]);
            $Lessonss->activity_id = $activity_id;
            $Lessonss->lesson_date = $this->changeDateFormat($date[$i]);
            $Lessonss->day = $this->tellDay($date[$i]);
            $Lessonss->start_date = $start_date[$i];
            $Lessonss->end_date = $end_date[$i];
            $Lessonss->teacher_id = $activity->teacher_id;
            $Lessonss->save();
            $Lessonss->students()->attach($students_array);
            $teachers->lessons()->attach($Lessonss->id);
        }
        return redirect()->route('activity.lessons', $Lessonss->activity_id);
    }

    public function getLessonUpdatePeriod($exit_date)
    {
        $dateTime = new DateTime();
        $current_datetime = $dateTime->format('d-m-Y');
        $datetime1 = new DateTime($current_datetime);
        $datetime2 = new DateTime($exit_date);
        $difference = $datetime2->diff($datetime1);
        $kim = $difference->days;

        if ($kim <= 7) {
            return true;
        } else {
            return false;
        }
    }

    public function LessonsUpdate(Request $request)
    {   
        $lessons = Lesson::find($request->id);
        $lessons->lesson_date = $this->changeDateFormat($request->date);
        $lessons->day = $this->tellDay($request->date);
        $lessons->start_date = $request->start_date;
        $lessons->end_date = $request->end_date;
        $lessons->note = $request->note;
        $lessons->teacher_id = $request->teacher;
        $lessons->save();

        /*Update teachers */
        $lessons->teachers()->sync($request->teacher);
        $teacher_count = DB::table('activity_teacher')
            ->where('teacher_id', '=', $request->teacher)
            ->where('activity_id', '=', $request->activity_id)
            ->count();
           
        if ($teacher_count == 0) {
            DB::table('activity_teacher')->insert([
                'activity_id' =>  $request->activity_id, 'teacher_id' =>  $request->teacher
            ]);
        }

        DB::table('lesson_teacher')->where('lesson_id', '=', $request->id)->update([
            'teacher_id' => $request->teacher,
        ]);
        return response()->json(['success'=>"تم التعديل بنجاح"]);
    }

    public function deleteLesson(Request $request)
    {
        $ids = $request->id;
        DB::table("lessons")->where('id', $ids)->delete();
        return response()->json(['success'=>"Lesson Deleted"]);
    }

    public function absence($id)
    {
        $today = date("Y-m-d");
        $activity = Activity::findOrFail($id);

        if (Gate::allows('teacher')) {
            $lessons = Lesson::selectRaw('lessons.id AS id, lessons.day, lessons.lesson_date')
                ->join('lesson_teacher', 'lessons.id', '=', 'lesson_teacher.lesson_id')
                ->where('lessons.activity_id', '=', $activity->id)
                ->where('lesson_teacher.teacher_id', '=', Auth::user()->teacher_id)
                ->where('lessons.lesson_date', '<=', $today)
                ->orderBy('lessons.id', 'ASC')
                ->get();
        } else {
            $lessons = Lesson::selectRaw('lessons.id AS id, lessons.day, lessons.lesson_date')
                ->where('lessons.activity_id', '=', $activity->id)
                ->where('lessons.lesson_date', '<=', $today)
                ->orderBy('lessons.id', 'ASC')
                ->get();
        }
        $title =  __('activity.absence');
        return view('activity.absence')->with([
            'activity' => $activity,
            'lessons' => $lessons,
            'bigtitle' => $title,
        ]);
    }

    public function AbsenceShow($id)
    {
        $lessons = Lesson::findOrFail($id);
        $activity_id = $lessons->activity->id;
        $student = Activity::join('lessons', 'activities.id', '=', 'lessons.activity_id')
            ->join('participations', 'activities.id', '=', 'participations.activity_id')
            ->join('students', 'participations.student_id', '=', 'students.id')
            ->select(['students.id','students.first_name','students.family_name'])
            ->where('lessons.id', $id)
            ->orderBy('students.first_name', 'asc')
            ->get();

        $selectStudent =$lessons->students()
            ->where('status', '!=', 1)
            ->pluck('student_id');
        $selectReson =$lessons->students()
            ->pluck('reason', 'student_id');
        $title =  __('activity.absence');
        $teacher_time = DB::table('lesson_teacher')
            ->where('lesson_id', $id)
            ->get();
        $teachers = Teacher::select(
            DB::raw("CONCAT(first_name,' ',father_name, ' ',family_name) AS name"),
            'id'
        )->pluck('name', 'id');

        return view('activity.abseceList')->with([
            'student' => $student,
            'lessons' => $lessons,
            'selectStudent' => $selectStudent,
            'selectReson' => $selectReson,
            'activity_id' => $activity_id,
            'bigtitle' =>  $title,
            'teacher_time' => $teacher_time
        ]);
    }

    public function showAbsence(Request $request, $id)
    {
        $student_id = [];
        $lessons = Lesson::find($id);
        $activity_name = $lessons->activity->title;
        $lessons_student = $lessons->students()
            ->select('student_id')
            ->orderBy('first_name', 'asc')->get();
        foreach ($lessons_student as $key => $value) {
            $student_id[] = $value->student_id;
        }
        $studentAbcense = Absance::wherein('student_id', $student_id)
            ->where('lesson_id', $lessons->id)->get();
        $getModel = new Absance();
        $table = Table::create($studentAbcense, $getModel->getSortable());
        return $this->dataOutput->representDataAccordingToRequestType($request, $studentAbcense, $activity_name, $table, 'activity.index');
    }


    public function AbsenceStore(Request $request)
    {   
        $sync_data = [];
        $resons = $request->resons;
        $students = $request->student_id;
        $lessons = Lesson::find($request->lessons_id);

        foreach ($students as $key => $values) {
            $sync_data[$key] = ['reason' => $resons[$key], 'status' => $values];
        }

        $lessons->students()->sync($sync_data);
        return redirect()->route('activity.absence',$lessons->activity_id);
        return redirect()->route('activity.absence.show', $request->lessons_id);
    }

    public function AbsenceTeacherNote(TeacherTime $request)
    {   
        $getData = $request->all();
        $lesson_id = $request->lesson_id;
        $teacher_id = $request->teacher_id;
        $start_time = $request->start_time;
        $end_time = $request->end_time;
        $summary = $request->summary;

        DB::table('lesson_teacher')
            ->where('teacher_id', $teacher_id)
            ->where('lesson_id','=',$request->lesson_id)
            ->update([
                'start_time' => $start_time,
                'end_time' => $end_time,
                'summary' => $summary
            ]);
        return redirect()->back();
    }

    public function resource(Request $request, $id)
    {
        try {
            $ActivityResource = Activity::findOrFail($id)->resources;
        } catch (ModelNotFoundException $exception) {
            $message = ($this->dataOutput->isApiRequest($request) ? [] : $this->responseMessages[0]);
            return $this->dataOutput->responseAccordingToRequestType($request, $message, 'student.index', false);
        }
        return View('activity.activity_resource', ['ActivityResource' => $ActivityResource, 'activityID' => $id, 'bigtitle'=> __('student.files')]);
    }

    public function ResourceStore(Request $request)
    {   
        $file = $request->file('resource');
        $fullURL = '/home/almodir/public_html/'.config('app.project').'/images/' . $this->targetDirNameResource;
        $imageHash = time();
        $imageName = $imageHash . '.' . $file->hashName();
        $moved = $file->move($fullURL, $imageName);
        $resources = new Activity_Resource;
        $resources->resource = $this->targetDirNameResource."".$imageName;
        $resources->activity_id = $request->AID;
        $resources->description = $request->description;
        $resources->save();
        $saved =  $resources->save();
        return redirect()->route('activity.resource', $request->AID);
    }

    public function Resourcedestroy(Request $request, $id)
    {
        $activity_resource = Activity_Resource::findOrFail($id);
        $deleted = $activity_resource->delete();
        return redirect()->route('activity.resource', $activity_resource->activity->id);
    }

    public function activityTeacher($id)
    {
        $today = date("Y-m-d");
        $teacher_activity = Teacher::find($id)
            ->lessons->where('lesson_date', '<=', $today);
        return view('activity.activityTeacher')->with('teacher_activity', $teacher_activity);
    }

    public function studentPayment($activity_id, $student_id)
    {
        $activity_student = Participation::where('student_id', $student_id)
            ->where('activity_id', $activity_id)
            ->first();
        $activity = Activity::find($activity_student->activity_id);
        $student = Student::find($activity_student->student_id);

        return view('student.payments')->with([
            'activity' => $activity,
            'student' => $student,
            'participation' => $activity_student,
            'bigtitle'=> __('activity.payments'),
        ]);
    }

    public function paymentStore(Request $request)
    {
        $total_payment = Student_Account::select('payment_cost')
            ->where('student_id', $request->student_id)
            ->sum('payment_cost');

        $activity_cost = Student::find($request->student_id)
            ->activities
            ->sum('cost');

        $total =  $activity_cost - $total_payment;
        $getData = $request->all();
        $validator = Validator::make($getData, [
            'payment_description' => 'nullable|string',
            'payment_cost' => 'required|integer|between:1,'.$total.'',
            'payment_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator);
        }
        $payment = Student_Account::create($getData);
        return redirect()->back();
    }

    public function search(Request $request)
    {   
        $document_title = '';
        $document_title .= __('activity.activity list');
        $document_title .= ' - ';
        $document_title .= date('Y-m-d');
        $document_title .= ' ';

        $doc_title = [];
        /* Filter Search Activity */
        $title  = $request->title;
        $place_id  = $request->place_id;
        $category_id  = $request->category_id;
        $start_date  = $request->start_date;
        $start_rang  = $request->start_rang;
        $end_date  = $request->end_date;
        $end_rang  = $request->end_rang;
        $center   = $request->center;
        $levels  = $request->levels;
        $department   = $request->department;
        $program   = $request->program;
        $type   = $request->type;
        $supplier   = $request->supplier;

        $activities = Activity::where('lock_status', '=', 0)->get();

        if ($title) {
            $activities =  $activities->where('title', '=', $title);
            $title_search = __('activity.title') . ' ' . $title;
            $doc_title[] = $title_search;
        }

        if ($category_id) {
            $activities =  $activities->where('category_id', '=', $category_id);        
            $category = Category::where('id', '=', $category_id)->first();
            $category_search = __('activity.category') . ' ' .$category->category_name;
            $doc_title[] = $category_search;
        }

        if ($place_id) {
            $activities =  $activities->where('place_id', '=', $place_id);
            $place = Place::where('id', '=', $place_id)->first();
            $place_search = __('activity.place') . ' ' .$place->name;
            $doc_title[] = $place_search;
        }
      
        if ($levels) {
            $activities =  Activity::whereHas('levels', function ($q) use ($levels) {
                $q->where('levels.id', '=', $levels);
            })->get();
            $levels = Level::where('id', '=', $levels)->first();
            $level_search = __('activity.level') . ' ' . $levels->name;
            $doc_title[] = $level_search;
        }

        if ($department) {
            $activities =  $activities->where('department_id', '=', $department);
            $department = Department::where('id', '=', $department)->first();
            $department_search = __('activity.department') . ' ' .$department->name;
            $doc_title[] = $department_search;
        }

        if ($center) {
            $activities =  $activities->where('center_id', '=', $center);
            $center = Center::where('id', '=', $center)->first();
            $center_search = __('activity.center') . ' ' .$center->name;
            $doc_title[] = $center_search;
        }

        if ($type) {
            $activities =  $activities->where('type', '=', $type);
            $types = Activity::$types;
            foreach ($types as $key => $value) {
                if ($key == $type) {
                    $type1 = $value;
                }
            }
            $type_search = __('activity.type') . ' ' .$type1;
            $doc_title[] = $type_search;
        }

        if ($program) {
            $activities =  $activities->where('program_id', '=', $program);
            $program = Program::where('id', '=', $program)->first();
            $program_search = __('activity.program') . ' ' .$program->name;
            $doc_title[] = $program_search;
        }

        if ($supplier) {
            $activities =  $activities->where('supplier_id', '=', $supplier);
            $supplier = Supplier::where('id', '=', $supplier)->first();
            $supplier_search = __('supplier.item') . ' ' .$supplier->company;
            $doc_title[] = $supplier_search;
        }
        if ($start_date && $start_rang) {
            $activities =  $activities
                ->where('start_date', '>=', $start_date)
                ->where('start_date', '<=', $start_rang);
      
            $start_date_start_rang_search = __('activity.start_date_from') . ' ' .$start_date . ' '. __('activity.start_date_to') . ' ' .$start_rang;
            $doc_title[] = $start_date_start_rang_search;

        } elseif ($start_date) {
            $activities =  $activities->where('start_date', '=', $start_date);
            $start_date = __('activity.start_date') . ' ' .$start_date;
            $doc_title[] = $start_date_search;
        }

        if ($end_date && $end_rang) {
            $activities =  $activities->where('end_date', '>=', $end_date)->where('end_date', '<=', $end_rang);
            $end_date_end_rang_search = __('activity.end_date_from') . ' ' .$end_date . ' '. __('activity.end_date_to') . ' ' .$end_rang;
            $doc_title[] = $end_date_end_rang_search;
        } elseif ($end_date) {
            $activities =  $activities->where('end_date', '=', $end_date);
            $end_date_search = __('activity.end_date') . ' ' .$end_date;
            $doc_title[] = $end_date_search;
        }

        if (isset($doc_title) and count($doc_title)) {
            for ($i=0; $i<count($doc_title); $i++) {
                $document_title .= ' - ';
                $document_title .= $doc_title[$i];
            }
        }
        /*************************/

        $getModel = new Activity();
        $table = Table::create($activities, $getModel->getSortable());
        $table->addColumn('reg_status', __('activity.reg_status'), function ($model) {
            return $model->reg_status();
        });
        $table->addColumn('studnets', __('activity.students'), function ($model) {
            return $model->getStudentsNumber();
        });
        return $this->dataOutput->representDataAccordingToRequestType($request, $activities, __('sidebar.View Activities'), $table, 'activity.index', $document_title);
    }

    public function studentExcel($id)
    {
        $ids = [];
        $activity = Activity::find($id);
        foreach ($activity->participations()->where('student_id', '!=', null)->get() as $participation) {
            if ($participation->student) {
                $student = $participation->student()->first();
                $ids[] = $student->id;
            }
        }
        DB::statement(DB::raw('set @row=0'));
        $students = Student::select(DB::raw("@row := @row + 1 as NO"), DB::raw("CONCAT(first_name,' ', father_name,' ', family_name) AS Full_Name"), 'National_Id', 'Email', 'Mobile', 'Date_Of_Birth')->wherein('id', $ids)->orderBy('first_name', 'asc')->get();

        Excel::create(''.__('activity.students').'_'.$activity->title.'_'.$id.'', function ($excel) use ($students) {
            $excel->sheet('Sheetname', function ($sheet) use ($students) {
                $sheet->fromModel($students, null, 'A1', false, false)->prependRow(
              array(__('activity.number'), __('student.full_name'), __('student.national_id'),  __('student.email'), __('student.mobile'), __('student.date_of_birth')
                            )

                        );
            });
        })->export('xls');
    }

    public function abcenceExcel($id)
    {
        $lessons = Lesson::find($id);
        $activity_name = $lessons->activity->title;
        DB::statement(DB::raw('set @row=0'));
        $lessons_student = $lessons->students()->select(DB::raw("@row := @row + 1 as NO"), DB::raw("CONCAT(first_name,' ', father_name,' ', family_name) AS Full_Name"), 'status', 'reason')->orderBy('first_name', 'asc')->get();

        $arr =array();
        $count = 1;
        foreach ($lessons_student as $student) {
            if ($student->status == 1) {
                $status = __('activity.absence_out');
            } else {
                $status = __('activity.absence_in');
            }
            $data =  array($count, $student->Full_Name, $status, $student->reason);
            array_push($arr, $data);
            $count++;
        }

        Excel::create(''.$activity_name.' '.__('activity.absence').' '.$lessons->lesson_date.'', function ($excel) use ($arr) {
            $excel->sheet('Sheet1', function ($sheet) use ($arr) {
                $sheet->fromArray($arr, null, 'A1', false, false)->prependRow(
                    array(__('activity.number'), __('student.full_name'), __('activity.reg_absence'), __('activity.note')
                         )

                        );
            });
        })->export('xls');
    }


    public function report(Request $request)
    {   
        $student_payment = null;
        $activity_title = null;
        $school_name = null;
        $year = $request->activity_year;
        $activity_year = $request->activity_year;
        $center_id = Center::where('admin_id', Auth::user()->id)->pluck('id')->toArray();
        if (Gate::allows('staff')) {
            $activitys = Activity::whereIn('center_id',$center_id)->pluck('title', 'id')->all();
        } else {
            $activitys = Activity::pluck('title', 'id')->all();
        }
        $table = null;
        $activity_reports = null;
        $lessons_dates = null;
        $studenta = null;

        if ($request->activityId) {
            $today = date("Y-m-d");
            $activity = Activity::findOrFail($request->activityId);
            $activity_title = $activity->title." ".date('Y-m-d');
            if (isset($activity->school_id) and !empty($activity->school_id)) {
                $school = School::findOrFail($activity->school_id);
                $school_name = $school->name;
            }
            $lessons_dates = Lesson::select('id', 'lesson_date')
                ->where('activity_id', $request->activityId)
                ->orderBy('lesson_date', 'asc')
                ->get();
            $id_lessons = $lessons_dates->pluck('id')->toArray();
            $activity_reports = Absance::join('lessons', 'lessons.id', '=', 'lesson_student.lesson_id')
                ->select('lesson_student.status', 'lesson_student.student_id', 'lesson_date')
                ->where('lessons.activity_id', $request->activityId)
                ->orderBy('student_id', 'asc')
                ->orderBy('lesson_date', 'asc')
                ->get();

            $students_id =  Participation::where('activity_id', '=', $request->activityId)
                ->pluck('student_id')
                ->toArray();
            $studenta = Student::select('id', 'first_name', 'family_name', 'national_id')
                ->whereIn('id', $students_id)
                ->get();
        }

        if ($request->activity_id) {
            //$unarchive_student = Student::where('is_archive', '=', 0)->pluck('id')->toArray();
            $activity_id = $request->activity_id;
            $activity_name = Activity::find($activity_id);
            $student = $activity_name->students->pluck('id')->toArray();
            $activity_title = $activity_name->title." ".date('Y-m-d');
            $student_payment = Student_Account::select([
                    'payment_type',
                    'activity_id',
                    'student_id',
                    DB::raw('(sum(CASE WHEN payment_type = "order" THEN payment_cost ELSE 0 END)) AS orders_sum'),
                    DB::raw('(sum(CASE WHEN payment_type = "payment" THEN payment_cost ELSE 0 END)) AS payment_sum'),
                    DB::raw('(sum(CASE WHEN payment_type = "order" THEN 1 ELSE 0 END)) AS orders_number'),
                    DB::raw('(sum(CASE WHEN payment_type = "payment" THEN 1 ELSE 0 END)) AS payment_number'),
            ])->where('activity_id', $activity_id)
                ->whereIn('student_id', $student)
                ->orderBy('created_at', 'desc')
                ->groupby('student_id')
                ->get();
        }
        if ($activity_year == 0) {
            $activity_year = __('general.all');
        }
        return view('activity.reports.reports', ['student_payment' => $student_payment, 'activity_title' => $activity_title, 'school_name' => $school_name, 'activity_reports' => $activity_reports, 'activitys' => $activitys,'lessons_dates' => $lessons_dates, 'activiy_id' => $request->activity_id,'activity_year' => $activity_year ,'studenta' => $studenta, 'bigtitle' => __('sidebar.Reports')]);
    }

    /**
     * function to show activity by study year
     * 
     * @return  view
     */
    public function showByYear()
    {
        return view('activity.reports.year-report')->with([
            'bigtitle' => __('sidebar.previous_study_year')
        ]);
    }

    /**
     * function to get data by year
     * 
     * @param  Request $request
     * 
     * @return  response
     */
    public function serachByYear(Request $request)
    {
        $year = $request->year;
        $activites = Activity::where('study_year', $year)->get();
        $html = "";
        foreach($activites as $activity){
            $cat_name = "";
            $place_name = "";
            $reg_status = "";
            $students = $activity->getStudentsNumber();
            if ($activity->reg_status == 0) {
                $reg_status = __('activity.opened');
            } else {
                $reg_status = __('activity.closed');
            }
            try {
                $category = Category::findOrFail($activity->category_id);
                $place = Place::findOrFail($activity->place_id);
                $cat_name = $category->category_name;
                $place_name = $place->name;

            } catch (ModelNotFoundException $e) {
                $cat_name = "";
            }    
            $html .= "<tr>";
            $html .= "<td>".$activity->title."</td>";
            $html .= "<td>".$activity->type."</td>";
            $html .= "<td>".$cat_name."</td>";
            $html .= "<td>".$activity->start_date."</td>";
            $html .= "<td>".$activity->end_date."</td>";
            $html .= "<td>".$reg_status."</td>";
            $html .= "<td>".$students."</td>";
            $html .= "</tr>";
        }
        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * function to get auto archive and manual archive activities
     * 
     * @return  view
     */
    public function autoArchive()
    {   
        $bigtitle =  __('sidebar.Archive Activity');
        return view('activity.auto-archive')->with([
            'bigtitle' => $bigtitle
        ]);
    }

    /**
     * function to search for archived activity
     * 
     * @param  Request $request
     * 
     * @return  response
     */
    public function searchArchivedActivity(Request $request)
    {   
        if ($request->year != 0) {
            $activites = Activity::where('study_year', '=', $request->year)->where('lock_status', '=', 1)->where('title', '=', $request->name)->get();
        } else {
            $activites = Activity::where('title', 'like', '%'.$request->name.'%')->where('lock_status', '=', 1)->get();
        }
        $html = "";
        foreach($activites as $activity){
            $cat_name = "";
            $place_name = "";
            $reg_status = "";
            $students = $activity->getStudentsNumber();
            if ($activity->reg_status == 0) {
                $reg_status = __('activity.opened');
            } else {
                $reg_status = __('activity.closed');
            }
            try {
                $category = Category::findOrFail($activity->category_id);
                $place = Place::findOrFail($activity->place_id);
                $cat_name = $category->category_name;
                $place_name = $place->name;

            } catch (ModelNotFoundException $e) {
                $cat_name = "";
            }    
            $html .= "<tr>";
            $html .= "<td>".$activity->title."</td>";
            $html .= "<td>".$activity->type."</td>";
            $html .= "<td>".$cat_name."</td>";
            $html .= "<td>".$activity->start_date."</td>";
            $html .= "<td>".$activity->end_date."</td>";
            $html .= "<td>".$reg_status."</td>";
            $html .= "<td>".$students."</td>";
            $html .= "<td style='width:20%'><a href=".route('activity.show', $activity->id)." class='btn btn-info' role='button' aria-pressed='true' title=".__('general.show')."><i class='mdi mdi-magnify-plus-outline'></i></a>".""."<a href=".route('activity.duplicate',$activity->id)." class='btn btn-primary' title=".__('activity.duplicate')."><i class='mdi mdi-content-duplicate'></i></a>"." ".
                "<a href=".route('student.byImage2', $activity->id)." class='btn btn-warning' title=".__('student.student_list')."><i class='mdi mdi-account-multiple'></i></a>"."<a href=".route('activity.lock',["id" => $activity->id, "status"=>"unlock","type" => 1])." class='btn btn-success' onclick='return confirm(".__('activity.confirm').")' title=".__('activity.unArchive')."><i class='mdi  mdi-lock-open-outline'></i></a>"."</td>";
            $html .= "</tr>";
        }
        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * function to get activities for datatable
     * 
     * @return response
     */
    public function getForDatatable()
    {   
        $settings = Setting::pluck('value', 'key');
        $current_year = @$settings['study_year'];
        $activities = "";
        $roles = [];
        if (Gate::allows('teacher')) {
            array_push($roles, 'teacher');
            $teacher_id = Auth::user()->teacher_id;
            $teacher_activities = DB::table('activity_teacher')
                ->where('teacher_id', '=', $teacher_id)
                ->pluck('activity_id');

            $activities = Activity::query()
                ->where('study_year', '=', $current_year)
                ->where('lock_status', '!=', 1)
                ->whereIn('id', $teacher_activities);
        }

        if (Gate::allows('staff')) {
            $center_id = Center::where('admin_id', Auth::user()->id)
                ->pluck('id')
                ->toArray();
            $activities = Activity::query()->whereIn('center_id', $center_id)
                ->where('study_year', '=', $current_year)
                ->where('lock_status', '!=', 1);
        }

        if (Gate::allows('eventManger')) {
            $activities = Activity::query()
                ->where('lock_status', '!=', 1)
                ->where('study_year', '=', $current_year)
                ->where('admin_id', '=', Auth::user()->id);
        }
        if (Gate::allows('eventManger') && Gate::allows('staff')) {
            
            $center_id = Center::where('admin_id', Auth::user()->id)
                ->pluck('id')
                ->toArray();
            $admin_id = Auth::user()->id;    
            $activities = Activity::where('lock_status', '=', 0)->where(function($activities) use ($center_id, $current_year){
                $activities->whereIn('center_id', $center_id);
                $activities->where('study_year', '=', $current_year);
            })->orWhere(function($activities) use ($admin_id, $current_year){

                $activities->where('admin_id', '=', $admin_id);
                $activities->where('study_year', '=', $current_year);
            });
        }
        if (Gate::allows('admin')) {
            $activities = Activity::query()
                ->where('study_year', '=', $current_year)
                ->where('lock_status', '!=', 1);
        }
        return datatables()
            ->of($activities)
            ->editColumn('title', function ($activities) {
                return '<a href='.route('activity.show', $activities->id).'>'.$activities->title.'</a>';
            })->addColumn('type', function ($activities){
                return __('activity.'.$activities->type);
            })->addColumn('category_id', function ($activities) {
                $cat_name = "";
                try {
                    $category = Category::findOrFail($activities->category_id);
                    return $category->category_name;
                } catch (ModelNotFoundException $e) {
                    return $cat_name;
                }
            })->addColumn('place_id', function ($activities) {
                $place_name = "";
                try {
                    $place = Place::findOrFail($activities->place_id);
                    return $place->name;
                } catch (ModelNotFoundException $e) {
                    return $place_name;
                }
            })->addColumn('reg_status', function ($activities) {
                if ($activities->reg_status == 1) {
                    return __('activity.opened');
                } else{
                    return __('activity.closed');
                }
            })->addColumn('students', function ($activities) {
                return $activities->getStudentsNumber();
            })->addColumn('actions', function ($activities) {
                if (Gate::allows('admin')) {
                    $buttons = 
                    //'<a href='. route('activity.show', $activities->id).' class="btn btn-info" role="button" aria-pressed="true" title='.__('activity.show').'><i class="mdi mdi-magnify-plus-outline"></i></a>'. ' '.
                    
                    '<div class="btn-group"><button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti-settings"></i></button>'.
                    '<div class="dropdown-menu animated flipInX">'.

                    '<a href='.route('activity.edit', $activities->id).' class="dropdown-item" title='.__('activity.edit').'><i class="mdi mdi-lead-pencil"></i>'. __('activity.edit').'</a>'.
                    '<a href='.route('activity.resource',$activities->id).' class="dropdown-item" title="'.__('activity.attachments').'"><i class="mdi mdi-attachment"></i>'.__('activity.attachments').'</a>'.' '.

                    '<a href='.route('student.byImage2', $activities->id).' class="dropdown-item" title="'.__('activity.student_list').'"><i class="mdi mdi-account-multiple"></i>'.__('activity.student_list').'</a>'. ' '.

                    '<a href='.route('participation.create', $activities->id).' class="dropdown-item" title="'.__('activity.new_participant').'"><i class="mdi mdi-account-plus"></i> '.__('activity.new_participant').'</a>'.
                    '<a href='.route('activity.lessons', $activities->id).' class="dropdown-item" title='.__('activity.lessons').'><i class="mdi  mdi-calendar-today"></i>'.__('activity.lessons').'</a>';
                    if (AppHelper::countLessons($activities->id) > 0) {
                        $buttons .= '<a href='.route('activity.absence', $activities->id).' class="dropdown-item" title="'.__('activity.absence').'"><i class="mdi mdi-timetable"></i> '.__('activity.absence').'</a>';
                     }   
                     $buttons .= '<a href='.route('activity.duplicate',  $activities->id).' class="dropdown-item" title='.__('activity.duplicate').'><i class="mdi mdi-content-duplicate"></i> '.__('activity.duplicate').'</a>';
                     $buttons .= '<a href="javascript:;" onclick="confirmArchive('.$activities->id.')" class="dropdown-item" id="archive_activity" ><i class="mdi mdi-lock"></i> '.__('activity.archive').'</a></div></div>';
                } elseif(Gate::allows('staff') || Gate::allows('eventManger')) {
                    $buttons = 
                    //'<a href='. route('activity.show', $activities->id).' class="btn btn-info" role="button" aria-pressed="true" title='.__('activity.show').'><i class="mdi mdi-magnify-plus-outline"></i></a>'. ' '.                    
                    '<div class="btn-group"><button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti-settings"></i></button>'.
                    '<div class="dropdown-menu animated flipInX">'.

                    '<a href='.route('activity.resource',$activities->id).' class="dropdown-item" title="'.__('activity.attachments').'"><i class="mdi mdi-attachment"></i>'.__('activity.attachments').'</a>'.' '.

                    '<a href='.route('student.byImage2', $activities->id).' class="dropdown-item" title="'.__('activity.student_list').'"><i class="mdi mdi-account-multiple"></i>'.__('activity.student_list').'</a>'. ' '.

                    '<a href='.route('participation.create', $activities->id).' class="dropdown-item" title="'.__('activity.new_participant').'"><i class="mdi mdi-account-plus"></i> '.__('activity.new_participant').'</a>';
                    if (AppHelper::countLessons($activities->id) > 0) {
                        $buttons .= '<a href='.route('activity.absence', $activities->id).' class="dropdown-item" title="'.__('activity.absence').'"><i class="mdi mdi-timetable"></i> '.__('activity.absence').'</a>';
                     }  
                    $buttons .= '<a href='.route('activity.lessons', $activities->id).' class="dropdown-item" title='.__('activity.lessons').'><i class="mdi  mdi-calendar-today"></i>'.__('activity.lessons').'</a>'.'</div></div>';
                } elseif (Gate::allows('teacher')) {
                    $buttons = '<a href='.route('student.byImage2', $activities->id).' class="btn btn-warning" title="'.__('activity.student_list').'"><i class="mdi mdi-account-multiple"></i></a>'. ' '.
                        '<a href='.route('activity.lessons', $activities->id).' class="btn btn-info" role="button" aria-pressed="true" title='.__('activity.lessons').'><i class="mdi mdi-calendar-today"></i></a>'.' ';

                        if (AppHelper::countLessons($activities->id, Auth::user()->teacher_id)) {
                            $buttons .= '<a href='.route('activity.absence', $activities->id).' class="btn btn-success" role="button" aria-pressed="true" title="'.__('activity.absence').'"><i class="mdi mdi-lead-pencil"></i></a>';
                        } 
                }
                return $buttons;
            })->rawColumns(['actions', 'title'])->make(true);
    }

        /**
     * function to get activities for datatable
     * 
     * @return response
     */
    public function getForArchiveDatatable($year = null)
    {   
        $settings = Setting::pluck('value', 'key');
        $current_year = @$settings['study_year'];
        $activities = "";
        if (Gate::allows('teacher')) {
            $activities = Activity::query()
                ->where('lock_status', '!=', 0)
                ->where('teacher_id', '=', Auth::user()->teacher_id);
        }

        if (Gate::allows('staff')) {
            $center_id = Center::where('admin_id', Auth::user()->id)
                ->pluck('id')
                ->toArray();
            $activities = Activity::query()->whereIn('center_id', $center_id)
                ->where('lock_status', '!=', 0);
        }

        if (Gate::allows('eventManger')) {
            $activities = Activity::query()
                ->where('lock_status', '!=', 0)
                ->where('admin_id', '=', Auth::user()->id);
        }
        if (Gate::allows('admin')) {
            $activities = Activity::query()
                ->where('lock_status', '!=', 0);
        }
        if (isset($year)) {
            $activities = $activities->where('study_year', $year);
        }
        return datatables()
            ->of($activities)
            ->editColumn('type', function ($activities){
                return __('activity.'.$activities->type);
            })->addColumn('category_id', function ($activities) {
                $cat_name = "";
                try {
                    $category = Category::findOrFail($activities->category_id);
                    return $category->category_name;
                } catch (ModelNotFoundException $e) {
                    return $cat_name;
                }
            })->addColumn('place_id', function ($activities) {
                $place_name = "";
                try {
                    $place = Place::findOrFail($activities->place_id);
                    return $place->name;
                } catch (ModelNotFoundException $e) {
                    return $place_name;
                }
            })->addColumn('reg_status', function ($activities) {
                if ($activities->reg_status == 1) {
                    return __('activity.opened');
                } else{
                    return __('activity.closed');
                }
            })->addColumn('students', function ($activities) {
                return $activities->getStudentsNumber();
            })->editColumn('actions', function ($activities) {
                if (Gate::allows('admin')) {
                    $buttons = 
                    '<a href='. route('activity.show', $activities->id).' class="btn btn-info" role="button" aria-pressed="true" title='.__('activity.show').'><i class="mdi mdi-magnify-plus-outline"></i></a>'. ' '.
                    
                    '<a href='.route('student.byImage2', $activities->id).' class="btn btn-warning" title="'.__('activity.student_list').'"><i class="mdi mdi-account-multiple"></i></a>'. ' '.
                    
                    '<a href='.route('activity.resource',$activities->id).' class="btn btn-success" role="button" aria-pressed="true" title='.__('activity.attachments').'><i class="mdi mdi-attachment"></i></a>'.' '.
                    '<a href="javascript:;" onclick="unLock('.$activities->id.')" class="btn btn-primary" role="button" aria-pressed="true" title="'.__('activity.unArchive').'"><i class="mdi mdi-lock"></i></a>';
                } else {
                    $buttons = 
                    '<a href='. route('activity.show', $activities->id).' class="btn btn-info" role="button" aria-pressed="true" title='.__('general.show').'><i class="mdi mdi-magnify-plus-outline"></i></a>'. ' '.
                    
                    '<a href='.route('student.byImage2', $activities->id).' class="btn btn-warning" title="'.__('activity.student_list').'"><i class="mdi mdi-account-multiple"></i></a>'. ' '.
                    
                    '<a href='.route('activity.resource',$activities->id).' class="btn btn-success" role="button" aria-pressed="true" title='.__('general.attachments').'><i class="mdi mdi-attachment"></i></a>';
                }
                return $buttons;
            })->rawColumns(['actions'])->make(true);
    }

    /**
     * function to remove activity 
     * 
     * @param  Request $request
     * 
     * @return  response
     */
    public function confirmDelete(Request $request)
    {
        $activity_id = $request->activity_id;

        try {
            $activity = Activity::find($activity_id)->delete();
            Session::flash(
                'alert-success', __('general.delete_done')
            );
        } catch (ModelNotFoundException $e) {
            Session::flash(
                'alert-danger', __('general.delete_faild')
            );
        }

        return redirect()->route('activity.index');
    }

    /**
     * function to get list of activities by year
     * 
     * @param Request $request
     * 
     * @return  response
     */
    public function getListByYear(Request $request)
    {   
        $year = $request->year;
        if($year != 0){
            $center_id = Center::where('admin_id', Auth::user()->id)->pluck('id')->toArray();
            if (Gate::allows('staff')) {
                $activitys = Activity::whereIn('center_id',$center_id)
                    ->where('study_year', '=', $year)
                    ->pluck('title', 'id')
                    ->all();
            } else {
                $activitys = Activity::where('study_year', '=', $year)
                    ->pluck('title', 'id')
                    ->all();
            }
        } else {
            if (Gate::allows('staff')) {
                $activitys = Activity::whereIn('center_id',$center_id)
                    ->pluck('title', 'id')
                    ->all();
            } else {
                $activitys = Activity::pluck('title', 'id')
                    ->all();
            }
        }
        return response()->json([
            'activitys' => $activitys
        ]);
    }

    /**
     * function to test 
     * 
     * @return   response
     */
    public function testCase()
    {    
        $participations = Participation::all();
        $count = 0;
        $array = [];
        foreach ($participations as $participation) {
                $account = Student_Account::where('activity_id', '=', $participation->activity_id)
                    ->where('student_id', '=', $participation->student_id)->first();
                if (empty($account)) {
                    try {
                        $activity = Activity::findOrFail($participation->activity_id);
                        $activity_cost = $activity->cost;
                        $orders_number = '';
                        $orders =  DB::table('stduent_account')->where('payment_type','order')
                            ->orderby('payment_number', 'desc')->first();
                        if(is_null($orders))
                        {
                            $oreders_number = 100000;
                        }
                        else
                        {
                        $oreders_number = $orders->payment_number;
                        }
                        $student_account  = Student_Account::create([
                            'payment_cost' =>  $activity_cost,
                            'payment_number' => ($oreders_number + 1),
                            'payment_type' => 'order',
                            'payment_description' => '-',
                            'payment_date' => $participation->created_at,
                            'student_id' => $participation->student_id,
                            'activity_id' => $participation->activity_id
                        ]);

                        $student_account->save();
                    } catch (ModelNotFoundException $e) {
                        
                    }    

                }    
        }
    }

    /**
     * function to show activity payments
     * 
     * @param  int $activity_id
     * 
     * @return view
     */
    public function activityPayments($activity_id)
    {
        try {
            $activity = Activity::findOrFail($activity_id);
            $payments = Student_Account::where('activity_id', '=', $activity_id)->where('payment_type', '=', 'order')->orderBy('payment_date', 'DES')->get();
            return view('activity.show-payments')->with([
                'activity' => $activity,
                'payments' => $payments,
                'bigtitle' => $activity->title." - ".__('activity.activity_payments')
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e]);
        }    
    }

    /**
     * function to get activity for api 
     * 
     * @param  Request $request
     * 
     * @return  response
     */
    public function getActivityForApi(Request $request)
    {
        $document_title = '';
        $document_title .= __('activity.activity list');
        $document_title .= ' - ';
        $document_title .= date('Y-m-d');

        //if teacher login in 
        if (Gate::allows('teacher')) {
            $activities = Activity::sorted()
                ->where('study_year', '=', date("Y"))
                ->where('lock_status', '!=', 1)
                ->where('teacher_id', '=', Auth::user()->teacher_id)
                ->get();
        }

        if (Gate::allows('staff')) {
            $center_id = Center::where('admin_id', Auth::user()->id)
                ->pluck('id')
                ->toArray();
            $activities = Activity::whereIn('center_id', $center_id)
                ->where('study_year', '=', date("Y"))
                ->where('lock_status', '!=', 1)
                ->get();
        }

        if (Gate::allows('eventManger')) {
            $activities = Activity::sorted()
                ->where('lock_status', '!=', 1)
                ->where('study_year', '=', date("Y"))
                ->where('admin_id', '=', Auth::user()->id)
                ->get();
        }
        if (Gate::allows('admin')) {
            $activities = Activity::sorted()
                ->where('study_year', '=', date("Y"))
                ->where('lock_status', '!=', 1)
                ->get();
        }
        $getModel = new Activity();
        $table = Table::create($activities, $getModel->getSortable());
        $table->addColumn('reg_status', __('activity.reg_status'), function ($model) {
            return $model->reg_status();
        });
        $table->addColumn('studnets', __('activity.students'), function ($model) {
            return $model->getStudentsNumber();
        });


        return $this->dataOutput->representDataAccordingToRequestType($request, $activities, __('sidebar.View Activities'), $table, 'activity.index', $document_title);
    }

    /**
     * function to get activity name 
     * 
     * @param  Request $request
     * 
     * @return  response
     */
    public function getActivityName(Request $request)
    {
        try {
            $activity = Activity::findOrFail($request->activity_id);
            return response()->json([
                'activity_name' => $activity->title
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'activity_name' => ''
            ]);
        }
    }
}
