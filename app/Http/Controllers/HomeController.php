<?php

namespace App\Http\Controllers;

use App\Models\AmaalDetail;
use App\Models\Event;
use App\Models\Job;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Community;
use App\Models\CommunityMember;
use App\Models\JobKura;
use App\Models\JobsApplied;
use App\Models\Notification;
use App\Models\ServiceProvider;
use App\Models\Task;
use App\Models\TaskDay;
use App\Models\TaskDetail;
use App\Models\TaskRecurringDetail;
use App\Models\TaskTags;
use App\Models\TaskTime;
use App\Models\Quote;
use App\Models\User;
use App\Models\UserJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use File;
use Validator;
use Onesignal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $data['title'] = "Dashboard";
        return view('admin.dashboard',$data);
    }

    public function awardedJobs()
    {
        $data['title'] = "Awarded Jobs";
        $data['jobs'] = Job::with('community','userJob')->where('community_id', '!=' ,null)->where('status','approved')->get();
        return view('admin.awarded_jobs',$data);
    }

    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }

    public function providers()
    {
        $data['title'] = 'Providers';
        $data['users'] = User::where('type', 'provider')->get();
        return view('admin.user.users', $data);
    }

    public function customers()
    {
        $data['title'] = 'Users';
        $data['users'] = User::where('type', 'customer')->get();
        return view('admin.user.users', $data);
    }

    public function profile($id)
    {
        $data['user'] = User::find($id);
        $data['title'] = $data['user']->name.' Profile';
        return view('admin.user.profile', $data);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'birth_date' => 'required',
            'gender' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'user_image' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['name'] = $request->first_name .' '. $request->last_name;
        $user = User::create($input);

        return back()->with('success', 'User Created Successfully');
    }

    public function delete($id)
    {
        $user = User::find($id);
        $type = $user->type;
        $user->delete();
        if ($type == 'provider') {

            return redirect('/providers');
        }

        return redirect('/customers');
    }

    public function dashboard(){
        $data['title'] = "Dashboard";
         return view('admin.dashboard',$data);
    }
    public function category(){
        $data['title'] = "Category";
        $data['amaal'] = Category::whereNull('parent_id')->get();
        return view('admin.amaal',$data);
    }
    public function createCategory(Request $request)
    {

        if($request->hasFile('image')) {
            $rand = substr(str_shuffle(str_repeat("0123456789", 5)), 0, 5);
            $fileName = time() . $rand . '.' . $request->image->extension();

            $request->image->move(public_path('images/categories/'), $fileName);
        }

        $amaal = new Category();
        $amaal->name = $request->name;
        $amaal->icon = $fileName;
        $amaal->description = $request->description;
        $amaal->save();
        return back()->with('success','Category created successfully');
    }

    public function deleteCategory(Request $request)
    {
        $amaal = Category::findOrFail($request->amal_id)->delete();
        return response()->json('success');
    }
    public function editCategory($id)
    {
        $data['title'] = "Edit Amaal";
        $data['amaal'] = Category::findOrFail($id);
        return view('admin.edit_amaal',$data);
    }
    public function submitCategory(Request $request)
    {
        $amaal = Category::findOrFail($request->amaal_id);

        if($request->hasFile('image')) {
            if(!is_null($amaal->icon)){
                $image_path = $amaal->icon;
                if(File::exists(public_path('images/categories/'.$image_path)))
                {
                    // unlink(public_path($image_path));
                    File::delete(public_path('images/categories/'.$image_path));
                }
            }
            $rand = substr(str_shuffle(str_repeat("0123456789", 5)), 0, 5);
            $fileName = time() . $rand . '.' . $request->image->extension();

            $request->image->move(public_path('images/categories/'), $fileName);
        }

        $amaal = Category::findOrFail($request->amaal_id);
        $amaal->name = $request->name;
        if($request->hasFile('image')) {
            $amaal->icon = $fileName;
        }
        $amaal->description = $request->description;
        $amaal->save();
        return back()->with('success','Category updated successfully');
    }
    public function subCategory()
    {
        $data['title'] = "Sub Category";
        $data['sub_category'] = Category::with('parentCategory')->whereNotNull('parent_id')->get();
        $data['categories'] = Category::whereNull('parent_id')->get();
        // dd($data['sub_category']);
        return view('admin.sub_category',$data);
    }
    public function addSubCategory(Request $request)
    {
        $sub_category = new Category();
        $sub_category->name = $request->name;
        $sub_category->description = $request->description;
        $sub_category->naiki = $request->naiki;
        $sub_category->parent_id = $request->parent_category;
        $sub_category->save();
        return back()->with('success','Sub Category created successfully');
    }
    public function editSubCategory($id)
    {
        $data['title'] = "Edit Sub Category";
        $data['sub_category'] = Category::findOrFail($id);
        $data['categories'] = Category::whereNull('parent_id')->get();
        return view('admin.edit_sub_category',$data);
    }
    public function updateSubCategory(Request $request)
    {
        $sub_category = Category::findOrFail($request->sub_category_id);
        $sub_category->name = $request->name;
        $sub_category->description = $request->description;
        $sub_category->naiki = $request->naiki;
        $sub_category->parent_id = $request->parent_category;
        $sub_category->save();
        return back()->with('success','Sub Category updated successfully');
    }
    public function tasks()
    {
        $data['title'] = "Tasks";
        // $data['amaal'] = Category::with('task')->whereNotNull('parent_id')->get();
        $data['tasks'] = Task::with('category','category.parentCategory')->get();
        return view('admin.tasks',$data);
    }


    public function viewTask($id)
    {
        $data['title'] = "View Task";
        $data['task'] = Task::with('category','category.parentCategory','taskDay','taskTime')->where('id',$id)->first();

        $tagNames = array();
        $tagIds = explode(',', $data['task']->tags_id);
        foreach ($tagIds as $tagId)
        {
            $tag = TaskTags::find($tagId);
            if($tag != null)
                array_push($tagNames, $tag->name);
        }
        $tagNames = implode(',', $tagNames);
        $data['tagNames'] = $tagNames;

        return view('admin.view_task',$data);
    }


    public function updateTask(Request $request)
    {
        if($request->tags == Null)
        {
            return back();
        }
        $tagsId = array();
        $tags = explode(',', $request->tags);

        foreach ($tags as $tag)
        {
            $tagName  = TaskTags::where('name', $tag)->first();
            if($tagName == Null)
            {
                $tagName = TaskTags::create([
                    "name" => $tag
                ]);
                array_push($tagsId,$tagName->id);
                continue;
            }
            $tagName  = TaskTags::where('name', $tagName->name)->first();
            array_push($tagsId,$tagName->id);

        }
        $tagIds = implode(',', $tagsId);

        $task = Task::find($request->task_id);
        $task->name = $request->name;
        $task->description = $request->description;
        $task->task_count = $request->task_count;
        $task->tags_id = $tagIds;
        $task->save();

            if(count($request->task_timepick_id) > 0)
            {
                foreach($request->task_timepick_id as $key=>$task_time_id)
                {
                    $index = $key;
                    $task_time = TaskTime::find($task_time_id);
                    $task_time->name = $request['task_time_name'][$index];
                    $task_time->time = $request['task_timepicker'][$index];
                    $task_time->quantity = $request['task_time_quant'][$index];
                    $task_time->save();
                }
            }
        if(isset($request->task_timepicker_new)){
            if(count($request->task_timepicker_new) > 0)
            {
                foreach($request->task_timepicker_new as $key=>$task_timepicker_new)
                {
                    $index = $key;
                    $task_time = new TaskTime();
                    $task_time->name = $request['task_time_name_new'][$index];
                    $task_time->time = $task_timepicker_new;
                    $task_time->quantity = $request['task_time_quant_new'][$index];
                    $task_time->task_id = $request->task_id;
                    $task_time->save();
                }
            }
        }

        return back()->with('success','Task updated successfully');
    }
    public function editTask($id)
    {
        $data['title'] = "Edit Task";
        $data['task'] = Task::with('category','category.parentCategory','taskDay','taskTime')->where('id',$id)->first();
        $data['category'] = Category::whereNull('parent_id')->get();
        $data['sub_categories'] = Category::where('parent_id',$data['task']['category']['parentCategory']['id'])->get();

        $allTags = array();
        $tagNames = array();
        $tagIds = explode(',', $data['task']->tags_id);
       foreach ($tagIds as $tagId)
       {
           $tag = TaskTags::find($tagId);
           if($tag != null)
            array_push($tagNames, $tag->name);
       }
        $tagNames = implode(',', $tagNames);
        $data['tagNames'] = $tagNames;
        $tags = TaskTags::all();
        foreach ($tags as $tag)
        {
            array_push($allTags, $tag->name);
        }
        $data['tags'] = json_encode($allTags);

        return view('admin.edit_task',$data);
    }
    public function enableTaskTime(Request $request)
    {
        $task_time = TaskTime::find($request->task_time_id);
        $task_time->is_active = 1;
        $task_time->save();
        return response()->json('success');
    }
    public function disableTaskTime(Request $request)
    {
        $task_time = TaskTime::find($request->task_time_id);
        $task_time->is_active = 0;
        $task_time->save();
        return response()->json('success');
    }
    public function disableTask($id)
    {
        $task = Task::findOrFail($id);
        $task->is_active = 0;
        $task->save();
        return back()->with('success','Task disabled successfully');
    }
    public function enableTask($id)
    {
        $task = Task::findOrFail($id);
        $task->is_active = 1;
        $task->save();
        return back()->with('success','Task enabled successfully');
    }
    public function categoryDetail(Request $request)
    {
        $sub_category = Category::where('parent_id',$request->cat_id)->get();
        return response()->json($sub_category);
    }
    // public function submitSubAmaal(Request $request)
    // {
    //     dd($request->all());
    //     $amaal = Category::findOrFail($request->amaal_id);
    //     $amaal->name = $request->name;
    //     $amaal->parent_id = $request->parent_category;
    //     $amaal->save();

    //     $task = Task::where('amaal_id',$request->amaal_id)->first();
    //     $task->description = $request->description;
    //     $task->recurring = $request->recurring;
    //     $task->recurring_type = $request->recurring_type;
    //     $task->task_count = $request->task_count;
    //     $task->save();

    //     foreach($request->task_timepicker as $key => $time){
    //         // dd($time);
    //         $index = $key;
    //         $time_id = $request->task_timepick_id[$index];
    //         $change_time = UserTaskTime::findOrFail($time_id);
    //         $change_time->time = $time;
    //         $change_time->save();
    //     }
    // }
    public function deleteSubAmaal($id)
    {
        $amaal = Category::findOrFail($id)->delete();
        return back()->with('success','Amaal deleted successfully');
    }
    public function addTask()
    {
        $data['title'] = "Add Task";
        $data['category'] = Category::whereNull('parent_id')->get();
        $tagsName = array();
        $tags = TaskTags::all();
        foreach ($tags as $tag)
        {
            array_push($tagsName, $tag->name);
        }
        $tagsName = json_encode($tagsName);
        return view('admin.add_task',$data, ["tags" => $tagsName]);
    }
    public function createTask(Request $request)
    {
        if($request->tags == Null)
        {
            return back();
        }
        $tagsId = array();
        $tags = explode(',', $request->tags);

        foreach ($tags as $tag)
        {
          $tagName  = TaskTags::where('name', $tag)->first();
          if($tagName == Null)
          {
             $tagName = TaskTags::create([
                  "name" => $tag
              ]);
             array_push($tagsId,$tagName->id);
             continue;
          }
            $tagName  = TaskTags::where('name', $tagName->name)->first();
            array_push($tagsId,$tagName->id);

        }
        $tagIds = implode(',', $tagsId);
        $new_task = new Task();
        $new_task->name = $request->name;
        $new_task->description = $request->description;
        $new_task->category_id = $request->sel_sub_category;
        $new_task->recurring_type = $request->recurring_type;
        $new_task->task_count = $request->task_count;
        $new_task->tags_id = $tagIds;
        $new_task->save();

        if($request->recurring_type == "weekly"){
            foreach($request->week_days as $weekdays){
                $task_week_day = new TaskDay();
                $task_week_day->task_id = $new_task->id;
                $task_week_day->number = $weekdays;
                $task_week_day->save();
            }
        }
        elseif($request->recurring_type == "monthly"){
            foreach($request->month_days as $monthDays){
                $task_month_days = new TaskDay();
                $task_month_days->task_id = $new_task->id;
                $task_month_days->number = $monthDays;
                $task_month_days->save();
            }
        }
        foreach($request->task_timepicker as $key => $time){
            $index = $key;
            $time_name = $request->task_time_name[$index];
            $task_quant = $request->task_time_quant[$index];
            $task_time = new TaskTime();
            $task_time->task_id = $new_task->id;
            $task_time->time = $time;
            $task_time->time_to = $request->task_timepicker2[$index];
            $task_time->name = $time_name;
            if($task_quant > 1)
            {
                $task_time->quantity = $task_quant;
            }
            $task_time->save();
        }
        return redirect()->route('tasks')->with('success','Task created successfully');
    }
    public function communities()
    {
        $data['title'] = "Communities";
        $data['active_communities'] = Community::with('communityMembers','jobs')->where('is_active',1)->get();
        $data['pending_communities'] = Community::with('communityMembers','jobs')->where('is_active',2)->get();
        $data['disabled_communities'] = Community::with('communityMembers','jobs')->where('is_active',0)->get();
        $data['draft_communities'] = Community::with('communityMembers','jobs')->where('is_active',3)->get();
        return view('admin.communities',$data);
    }
    public function createCommunity()
    {
        $data['title'] = "Create Community";
        return view('admin.add_community',$data);
    }
    public function addCommunity(Request $request)
    {
        $community = new Community();
        $community->name = $request->name;
        $community->description = $request->description;
        $community->is_active = 1;
        $community->type = 'public';
        $community->created_by = auth()->user()->email;
        $community->save();
        return redirect()->route('communities')->with('success','Community created successfully');
    }
    public function editCommunity($id)
    {
        $data['title'] = "Edit Community";
        $data['community'] = Community::findOrFail($id);
        return view('admin.edit_community',$data);
    }
    public function updateCommunity(Request $request)
    {
        $community = Community::findOrFail($request->community_id);
        $community->name = $request->name;
        $community->description = $request->description;
        $community->save();
        return back()->with('success','Community updated successfully');
    }
    public function viewCommunity($id)
    {
        $data['title'] = "View Community";
        $data['community'] = Community::with('users','jobs','jobs.subCategory','jobs.userJob')
            ->find($id);
        $data['all_users'] = $data['community']->users()->where('type' ,'!=','Admin')->get();
        return view('admin.view_community',$data);
    }
    public function addCommunityMember(Request $request)
    {
        if(count($request->sel_members) == 1){
            $member = "Member";
        }
        else{
            $member = "Members";
        }
        foreach($request->sel_members as $new_member){
            $comm_member = new CommunityMember();
            $comm_member->member_id = $new_member;
            $comm_member->community_id = $request->comm_id;
            $comm_member->save();
        }
        return back()->with('success',$member.' created successfully');
    }
    public function viewCommunityJob($id)
    {
        $data['title'] = "Community Job";
        $data['job'] = Job::with('')->findOrFail($id);
        return view('admin.community_job',$data);
    }
    public function disableCommunity($id)
    {
        $community = Community::findOrFail($id);
        $community->is_active = 0;
        $community->save();
        return back()->with('success','Community disabled successfully');
    }
    public function enableCommunity($id)
    {
        $community = Community::findOrFail($id);
        $community->is_active = 1;
        $community->save();
        return back()->with('success','Community enabled successfully');
    }
    public function jobs()
    {
        $data['title'] = "Jobs";
        $data['jobs'] = Job::with('user','assigned_to')->where('community_id', NULL)->get(); // 5 for se
        $data['communities'] = Community::where('is_serviceProvider',1)->get();
        return view('admin.jobs',$data);
    }

    public function appliedJobs(){
        $data['title'] = "Applied Jobs";
        $data['jobs'] = JobsApplied::with('user','jobs')->get();

        return view('admin.jobs_applied',$data);
    }

    public function imageView(){
        return view('image_upload');
    }
    public function approveCommunity($id){
        $community = Community::findOrFail($id);
        $community->is_active = 1;
        $community->save();
        return back()->with('success','Community approved successfully');
    }

    /*public function imageUpload(Request $request){

        if($request->hasFile('image')) {
                $rand = substr(str_shuffle(str_repeat("0123456789", 5)), 0, 5);
                $fileName = time() . $rand . '.' . $request->image->extension();

                $request->image->move(public_path('images/categories/'), $fileName);
        }
        $banner = new Category();
        $banner->name = "Namaz";
        $banner->icon  = $fileName;
        $banner->save();
        return back();
    }*/
    public function dailyQuotes()
    {
        $data['title'] = "Quotes";
        $data['quotes'] = Quote::all();
        return view('admin.quotes',$data);
    }
    public function addQuote()
    {
        $data['title'] = "Add Quote";
        return view('admin.add_quote',$data);
    }
    public function submitQuote(Request $request)
    {
         $validator = Validator::make($request->all(), [
           'date_from'    => 'required|date',
           'date_to'      => 'required|date|date_format:Y-m-d|after:date_from',
        ],[
            'date_from.date' => 'Date must be in valid format',
            'date_to.date' => 'Date must be in valid format',
        ]);
          if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $image = $request->screenshot_img;  // your base64 encoded
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = Str::random(10).'.'.'png';
         // $decoded_img = base64_decode($image);
         // $decoded_img->move(public_path('images/quotes/'), $imageName);
        \File::put(public_path('/images/quotes/') . $imageName, base64_decode($image));


         $quote = new Quote();
         $quote->text = $request->text;
         // $quote->color = $request->txt_color;
         // $quote->background = $request->back_color;
         $quote->color = "#ffffff";
         $quote->background = "#000000";
         $quote->date_from = $request->date_from;
         $quote->date_to = $request->date_to;
         $quote->image = $imageName;
         $quote->save();
         return redirect()->route('quotes')->with('success','Quote created successfully');
    }
    public function editQuote($id)
    {
        $data['title'] = "Edit Quote";
        $data['quote'] = Quote::find($id);
        return view('admin.edit_quote',$data);
    }
    public function updateQuote(Request $request)
    {
        // dd($request->all());
         $validator = Validator::make($request->all(), [
           'date_from'    => 'required|date',
           'date_to'      => 'required|date|date_format:Y-m-d|after:date_from',
        ],[
            'date_from.date' => 'Date must be in valid format',
            'date_to.date' => 'Date must be in valid format',
        ]);
          if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $quote = Quote::find($request->quote_id);

        if(!is_null($request->screenshot_img))
        {
            if(!is_null($quote->image)){
                $image_path = $quote->image;
                if(File::exists(public_path('images/quotes/'.$image_path)))
                {
                    File::delete(public_path('images/quotes/'.$image_path));
                }
            }
            $image = $request->screenshot_img;  // your base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = Str::random(10).'.'.'png';
            \File::put(public_path('/images/quotes/') . $imageName, base64_decode($image));
        }
        $quote->text = $request->text;
         // $quote->color = $request->txt_color;
         // $quote->background = $request->back_color;
        $quote->color = "#ffffff";
        $quote->background = "#000000";
        $quote->date_from = $request->date_from;
        $quote->date_to = $request->date_to;
        if(!is_null($request->screenshot_img))
        {
            $quote->image = $imageName;
        }
        $quote->save();
        return redirect()->back()->with('success','Quote updated successfully');
    }
    public function deleteQuote($id)
    {
        $quote = Quote::where('id',$id)->delete();
         return redirect()->back()->with('success','Quote deleted successfully');
    }
    public function generateKura(Request $request)
    {
        $job = Job::find($request->job_id);
        $kura = UserJob::with('user')->where('job_id',$request->job_id)->inRandomOrder()->limit($job->winners)->get();
        return response()->json($kura);
    }
    public function addKura(Request $request)
    {

        $job = Job::with('subCategory')->where('id',$request->job_id)->first();

        $naiki = $job->subCategory->naiki;
        $total_naiki = $naiki * $job->quantity;
        $each_naiki = $total_naiki / count($request->winners);
        foreach($request->winners as $winner)
        {
            $kura = new JobKura();
            $kura->job_id  = $request->job_id;
            $kura->user_id = $winner;
            $kura->naiki   = $each_naiki;
            $kura->save();
            $message = "Congratulation You won in {$job->title} kurra";
            $notification = \OneSignal::sendNotificationUsingTags(
                $message, array(
                ["field" => "tag", "key" => "user_id", "relation" => "=", "value" => $winner]
            ), $url = null, $data = null, $buttons = null, $schedule = null
            );
                Notification::create([
                    "user_id"      =>  $winner,
                    "notification" =>  $message,
                    "type"         =>  'community_job_kura',
                    "job_id"       =>  $request->job_id,
                    "is_read"      =>  0,
                ]);
        }
        $job->is_kura = 1;
        $job->save();
        return redirect()->back()->with('success','Kura generated successfully');
    }
}
