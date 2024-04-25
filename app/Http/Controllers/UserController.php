<?php

namespace App\Http\Controllers;

use App\Models\TaskTags;
use App\Models\UserAdress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Amaal;
use App\Models\Task;
use App\Models\User;
use App\Models\LoginUser;
use App\Models\Banner;
use App\Models\CategoriesDetail;
use App\Models\Category;
use App\Models\Community;
use App\Models\CommunityMember;
use App\Models\CommunityMessage;
use App\Models\CommunityRequest;
use App\Models\Job;
use App\Models\JobKuraMarhoom;
use App\Models\JobMarhoom;
use App\Models\JobsApplied;
use App\Models\Notification;
use App\Models\ProviderCategory;
use App\Models\ReadMessage;
use App\Models\RecurringDetail;
use App\Models\TaskDetail;
use App\Models\UserTaskDetail;
use App\Models\UserSubTaskDetail;
use App\Models\SubCategory;
use App\Models\SubTaskDetail;
use App\Models\UserTask;
use App\Models\TaskTime;
use App\Models\Sahadat;
use App\Models\Taqleed;
use App\Models\UserJob;
use App\Models\Quote;
use App\Models\UserCard;
use App\Models\UserMarhoom;
use App\Models\UserTransaction;
use App\Models\VUserTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use phpseclib3\File\ASN1\Maps\UserNotice;
use Illuminate\Support\Facades\DB;
use File;
use OneSignal;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class UserController extends Controller
{

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function leaveCommunity($communityId, $userId)
    {
       $community = Community::find($communityId);
       if(is_null($community))
           return sendError('No such community exist',404);
        $leaved = $community->users()->detach($userId);
       if($leaved)
           return sendSuccess('You left the Community!',[]);
        if(!$leaved)
            return sendError('Server Error!',500);

    }
    public function getCommunityMembers($id)
    {
      $community = Community::find($id);
      $communityMembers = $community->users;
      return  sendSuccess("",$communityMembers);

    }

    public function contactUs()
    {
        return view('contact_us',[]);
    }
    public function addUserAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_type' => 'required',
            'country' => 'required',
            'city' => 'required',
            'locality' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return sendError($errors, 400);
        }

       $created = UserAdress::create([

            'user_id' => $request->user()->id,
            'type' => $request->address_type,
            'country' => $request->country,
            'city' => $request->city,
            'locality' => $request->locality,
        ]);
        if($created)
        {
            return sendSuccess("Address added successfully.", $created);
        }
        if(!$created)
        {
            return sendError("Error! Something went wrong.", 500, );
        }
    }

    public function getUserAddresses(Request $request)
    {
      $userAddresses = UserAdress::where('user_id', $request->user()->id)->get();

        $addressList = array();
        if(!is_null($userAddresses)) {
         foreach ($userAddresses as $userAddress) {
             $fullAddress = $userAddress->locality . "," . $userAddress->city . "," . $userAddress->country . "(" . $userAddress->type . ")";
             array_push($addressList, $fullAddress);
         }
         $user = User::find($request->user()->id);
         $fullAddress = $user->address."(".$user->address_type.")";
         array_push($addressList, $fullAddress);

         return response()->json([
             "status" => "success",
             "data" => [
               "AddressList" => $addressList
             ]
         ],200);
     }

    }
    public function submitContactUs(Request $request)
    {
        $data['user'] = $request->all();
        // dd($data);
        Mail::send('emails.contact_us', $data, function($message) use ($data) {
            $message->to(env('MAIL_USERNAME'), 'Qalb e Saleem')
                    ->from($data['user']['email'], $data['user']['name'])
                    ->subject($data['user']['subject']);
        });
        return back()->with('success','Your request forward successfully.');
    }
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            // $abc = Str::random(32);
            $data['message'] = "User login successfully";
            $data['user'] = $user;
            $data['token'] = Str::random(100);
            $login_user = new LoginUser();
            $login_user->user_id = $user->id;
            $login_user->session_key = $data['token'];
            $login_user->save();
            return response()->json(['success' => $data], 200);
            // return  sendSuccess('User login successfully', $data);
        }
        else {
                // $errors = implode(', ', $validator->errors()->all());
                $errors = "Credentials not valid";
                return sendError($errors, 400);
        }
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'gender' => 'required',
            'taqleed_id' => 'required',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'phone' => 'required',
            'address' => 'required',
            'address_type' => 'required',
            'currency_code' => 'required',
            // 'code' => 'required'
        ]);
        if ($validator->fails()) {
            // $errors = implode(', ', $validator->errors()->all());
            $errors = $validator->errors()->all();
            return sendError($errors, 400);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->taqleed_id = $request->taqleed_id;
        $user->gender = $request->gender;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->address_type = $request->address_type;
        $user->currency_code = $request->currency_code;
        $user->save();
        // $success['message'] = "User login successfully.";
        $user_data = User::find($user->id);
        $data['user'] = $user_data;
        $data['token'] = Str::random(100);
        $login_user = new LoginUser();
        $login_user->user_id = $user->id;
        $login_user->session_key = $data['token'];
        $login_user->save();
        // return  sendSuccess('User login successfully', $data);
        return response()->json(['success' => $data], 200);
    }
    public function verifyPhone()
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;
        $user = User::find($user_id);
        $user->phone_verified = 1;
        $user->save();
        return sendSuccess('User number verified successfully', $user);
        // return response()->json(['success'=> $data],200);
    }
    public function socialMediaRegister(Request $request)
    {
        // Check ID's of Social Media
        if(isset($request->fb_id)){
            $chk_user_fb = User::where('fb_id',$request->fb_id)->first();
            if($chk_user_fb){
                $data['user'] = $chk_user_fb;
                $data['token'] = Str::random(100);
                $login_user = new LoginUser();
                $login_user->user_id = $chk_user_fb->id;
                $login_user->session_key = $data['token'];
                $login_user->save();
                // return  sendSuccess('User login successfully', $data);
                return response()->json(['success' => $data], 200);
            }
        }
        if(isset($request->google_id)){
            $chk_user_google = User::where('google_id',$request->google_id)->first();
            if($chk_user_google){
                $data['user'] = $chk_user_google;
                $data['token'] = Str::random(100);
                $login_user = new LoginUser();
                $login_user->user_id = $chk_user_google->id;
                $login_user->session_key = $data['token'];
                $login_user->save();
                // return  sendSuccess('User login successfully', $data);
                return response()->json(['success' => $data], 200);
            }
        }
        if(isset($request->ios_id)){
            $chk_user_ios = User::where('google_id',$request->ios_id)->first();
            if($chk_user_ios){
                $data['user'] = $chk_user_ios;
                $data['token'] = Str::random(100);
                $login_user = new LoginUser();
                $login_user->user_id = $chk_user_ios->id;
                $login_user->session_key = $data['token'];
                $login_user->save();
                // return  sendSuccess('User login successfully', $data);
                return response()->json(['success' => $data], 200);
            }
        }
        $chk_user_email = User::where('email',$request->email)->first();
        if($chk_user_email){
            if(is_null($chk_user_email->fb_id) || is_null($chk_user_email->google_id) || is_null($chk_user_email->ios_id))
            {
                if(is_null($chk_user_email->fb_id) && isset($request->fb_id))
                {
                    $chk_user_email->fb_id = $request->fb_id;
                }
                if(is_null($chk_user_email->google_id) && isset($request->google_id)){
                    $chk_user_email->google_id = $request->google_id;
                }
                if(is_null($chk_user_email->ios_id) && isset($request->ios_id)){
                    $chk_user_email->ios_id = $request->ios_id;
                }
                $chk_user_email->save();
                $data['user'] = $chk_user_email;
                $data['token'] = Str::random(100);
                $login_user = new LoginUser();
                $login_user->user_id = $chk_user_email->id;
                $login_user->session_key = $data['token'];
                $login_user->save();
                // return  sendSuccess('User login successfully', $data);
                return response()->json(['success' => $data], 200);
            }
            $data['user'] = $chk_user_email;
            $data['token'] = Str::random(100);
            $login_user = new LoginUser();
            $login_user->user_id = $chk_user_email->id;
            $login_user->session_key = $data['token'];
            $login_user->save();
            // return  sendSuccess('User login successfully', $data);
            return response()->json(['success' => $data], 200);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        if(isset($request->fb_id)){
            $user->fb_id = $request->fb_id;
        }
        if(isset($request->google_id)){
            $user->fb_id = $request->google_id;
        }
        if(isset($request->ios_id)){
            $user->fb_id = $request->ios_id;
        }
        $user->save();

        $data['user'] = $user;
        $data['token'] = Str::random(100);
        $login_user = new LoginUser();
        $login_user->user_id = $user->id;
        $login_user->session_key = $data['token'];
        $login_user->save();
        // return  sendSuccess('User login successfully', $data);
        return response()->json(['success' => $data], 200);
    }
    public function forgetPassword(Request $request)
    {
        $user = User::where('email',$request->email)->first();
        if(!$user)
        {
            return sendError("User didn't exists", 400);
        }
        // $this->securekey = md5('email');
        //     $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
        //     $this->iv = openssl_random_pseudo_bytes($ivlen);
        //     $plain_text = time();
        //     $token = base64_encode(openssl_encrypt($plain_text, "AES-128-CBC", $this->securekey, $options = OPENSSL_RAW_DATA, $this->iv));
        //     $newtoken = str_replace('/', '_', $token);
        $newtoken = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 10);
            $user->email_token = $newtoken;
            $user->save();
        $emaildata = array('to' => $user->email, 'to_name' => $user->name);
        // dd(env('MAIL_USERNAME'));
        $data['token'] = $newtoken;
        $data['name'] = $user->name;
        Mail::send('emails.forgetpassword', $data, function($message) use ($emaildata) {
            $message->to($emaildata['to'], $emaildata['to_name'])
                    ->from(env('MAIL_USERNAME'), 'Qalb e Saleem')
                    ->subject('Reset Your Password');
        });
        return sendSuccess('Link to reset your password has been sent to your email', $user);
        // Send Email To User Provided Email

    }
    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function userProfile()
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;
        $user = User::with('taqleed')->where('id',$user_id)->first();
        // $data['user'] = $user;
        // return response()->json(['success' => $user], 200);
        return sendSuccess('User Profile', $user);
    }
    public function editProfile(Request $request)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'email' => 'required|email',
            'taqleed_id' => 'required',
            'gender' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'address_type' => 'required',
        ]);
        if ($validator->fails()) {
            // $errors = implode(', ', $validator->errors()->all());
            $errors = $validator->errors()->all();
            return sendError($errors, 400);
        }

        $user = User::find($user_id);
        $user->name = $request->name;
        $user->taqleed_id = $request->taqleed_id;
        $user->gender = $request->gender;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->address_type = $request->address_type;
        $user->save();
        return sendSuccess('Profile updated successfully', $user);
    }
    public function completeProfile(Request $request)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'email' => 'required|email',
            'taqleed_id' => 'required',
            'gender' => 'required',
            'phone' => 'required',
        ]);
        if ($validator->fails()) {
            // $errors = implode(', ', $validator->errors()->all());
            $errors = $validator->errors()->all();
            return sendError($errors, 400);
        }

        $user = User::find($user_id);
        $user->name = $request->name;
        $user->taqleed_id = $request->taqleed_id;
        $user->gender = $request->gender;
        $user->phone = $request->phone;
        $user->save();

        $data['user'] = $user;
        $data['token'] = Str::random(100);
        $login_user = new LoginUser();
        $login_user->user_id = $user->id;
        $login_user->session_key = $data['token'];
        $login_user->save();
        // return sendSuccess('Profile completed successfully', $data);
         return response()->json(['success' => $data], 200);
    }
    public function updateImage(Request $request)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;
        $user = User::find($user_id);
        if(!is_null($user->user_image))
        {
            if(File::exists(public_path('images/profile/'.$user->user_image)))
            {
                // unlink(public_path($image_path));
                File::delete(public_path('images/profile/'.$user->user_image));
            }
        }
        $rand = substr(str_shuffle(str_repeat("0123456789", 5)), 0, 5);
        $fileName = time() . $rand . '.' . $request->image->extension();
        $request->image->move(public_path('images/profile/'), $fileName);

        $user->user_image = $fileName;
        $user->save();
        return sendSuccess('Image updated successfully', []);
    }

    public function banners(){

        $weekday_no = date('w');
        $data['banners'] = Banner::orderBy('priority', 'ASC')->get();
        $data['naqsh'] = "naqsh_".$weekday_no.".png";
        $data['quote'] = Quote::whereRaw('? between date_from and date_to', [Carbon::now()])->first();

        if(is_null($data['quote']))
            $data['quote'] = Quote::inRandomOrder()->first();
        return sendSuccess('Banners', $data);
    }

    public function categories(){
        $data['categories'] = Category::whereNull('parent_id')->get();
        // return response()->json(['success' => $categories], 200);
        return sendSuccess('Categories', $data);
    }

    public function userTasks(Request $request)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;
        if(!isset($request->task_date)){
            // return response()->json(['error' => 'Task Date is required'], 401);
            return sendError('Task Date is required', 400);
        }
        $day = date('l',strtotime($request->task_date));
        $week_day = date('N',strtotime($request->task_date));
        $month_day = date('j',strtotime($request->task_date));
        $task_date = date('Y-m-d',strtotime($request->task_date));

        $daily = "daily";
        $yearly = "yearly";
        $start_week_date = Carbon::parse($task_date)->startOfWeek()->format('Y-m-d');
        $end_week_date = Carbon::parse($task_date)->endOfWeek()->format('Y-m-d');
        $start_month_date = Carbon::parse($task_date)->startOfMonth()->format('Y-m-d');
        $end_month_date = Carbon::parse($task_date)->endOfMonth()->format('Y-m-d');
        $current_year = date('Y',strtotime($request->task_date));

        $task = Task::with('category','category.parentCategory')->where('is_active',1);

        $daily_task = clone $task;
        $weekly_task = clone $task;
        $monthly_task = clone $task;
        $yearly_task = clone $task;
        $once_task = clone $task;

        $daily_task  = $daily_task
                             ->where('recurring_type','daily')
                             ->with('taskTime', function($q) use($task_date,$user_id){
                                    $q->with(['userTask' => function ($q) use($task_date,$user_id)
                                    {
                                        $q->where('user_id',$user_id)->whereDate('task_date',$task_date);
                                    }]);
                                    $q->withCount(['userTask AS sumQuantity' => function ($q) use($task_date,$user_id)
                                    {
                                        $q->select(DB::raw("SUM(quantity) as sumquant"))->where('user_id',$user_id)->whereDate('task_date',$task_date);
                                    }]);
                                })
                                ->withCount(['countUserTask' => function ($q) use($task_date,$user_id)
                                    {
                                        $q->where('user_id',$user_id)->whereDate('task_date',$task_date);
                                    }])
                            ->get();
        $weekly_task = $weekly_task
                             ->where('recurring_type','weekly')
                             ->with('taskTime', function($q) use($start_week_date,$end_week_date,$user_id){
                                    $q->with(['userTask' => function ($q) use($start_week_date,$end_week_date,$user_id)
                                    {
                                        $q->where('user_id',$user_id)->where('task_date', '>=', $start_week_date)->where('task_date', '<=', $end_week_date);
                                    }]);
                                    $q->withCount(['userTask AS sum_quantity' => function ($q) use($start_week_date,$end_week_date,$user_id)
                                    {
                                        $q->select(DB::raw("SUM(quantity) as sumquant"))->where('user_id',$user_id)->where('task_date', '>=', $start_week_date)->where('task_date', '<=', $end_week_date);
                                    }]);
                                })
                                ->whereHas('taskDay', function($q) use($week_day){
                                        $q->where('number',$week_day);
                                })
                                ->withCount(['countUserTask' => function ($q) use($start_week_date,$end_week_date,$user_id)
                                    {
                                        $q->where('user_id',$user_id)->where('task_date', '>=', $start_week_date)->where('task_date', '<=', $end_week_date);
                                    }])
                            ->get();
        $monthly_task = $monthly_task
                             ->where('recurring_type','monthly')
                             ->with('taskTime', function($q) use($start_month_date,$end_month_date,$user_id){
                                    $q->with(['userTask' => function ($q) use($start_month_date,$end_month_date,$user_id)
                                    {
                                        $q->where('user_id',$user_id)->where('task_date', '>=', $start_month_date)->where('task_date', '<=', $end_month_date);
                                    }]);
                                    $q->withCount(['userTask AS sum_quantity' => function ($q) use($start_month_date,$end_month_date,$user_id)
                                    {
                                        $q->select(DB::raw("SUM(quantity) as sumquant"))->where('user_id',$user_id)->where('task_date', '>=', $start_month_date)->where('task_date', '<=', $end_month_date);
                                    }]);
                                })
                             ->whereHas('taskDay', function($q) use($month_day){
                                    $q->where('number',$month_day);
                                })
                             ->withCount(['countUserTask' => function ($q) use($start_month_date,$end_month_date,$user_id)
                                {
                                    $q->where('user_id',$user_id)->where('task_date', '>=', $start_month_date)->where('task_date', '<=', $end_month_date);
                                }])
                            ->get();
        $yearly_task = $yearly_task
                             ->where('recurring_type','yearly')
                             ->with('taskTime', function($q) use($current_year,$user_id){
                                    $q->with(['userTask' => function ($q) use($current_year,$user_id)
                                    {
                                        $q->where('user_id',$user_id)->whereYear('task_date', '=', $current_year);
                                    }]);
                                    $q->withCount(['userTask AS sum_quantity' => function ($q) use($current_year,$user_id)
                                    {
                                        $q->select(DB::raw("SUM(quantity) as sumquant"))->where('user_id',$user_id)->whereYear('task_date', '=', $current_year);
                                    }]);
                                })
                             ->withCount(['countUserTask' => function ($q) use($current_year,$user_id)
                                {
                                    $q->where('user_id',$user_id)->whereYear('task_date', '=', $current_year);
                                }])
                            ->get();
        $once_task = $once_task
                              ->where('recurring_type','once')
                              ->with('taskTime', function($q) use($user_id){
                                    $q->with(['userTask' => function ($q) use($user_id)
                                    {
                                        $q->where('user_id',$user_id);
                                    }]);
                                    $q->withCount(['userTask AS sum_quantity' => function ($q) use($user_id)
                                    {
                                        $q->select(DB::raw("SUM(quantity) as sumquant"))->where('user_id',$user_id);
                                    }]);
                                })
                              ->withCount(['countUserTask' => function ($q) use($user_id)
                                {
                                    $q->where('user_id',$user_id);
                                }])
                            ->get();

        $tags = TaskTags::all();
        $allItems = collect([
            'daily_task'   => $daily_task,
            'weekly_task'  => $weekly_task,
            'monthly_task' => $monthly_task,
            'yearly_task'  => $yearly_task,
            'once_task'    => $once_task,
            'tags'         => $tags,
          ]);
        // return response()->json(['success' => $allItems], 200);
        return sendSuccess('User Tasks', $allItems);
    }
    public function updateUserTask(Request $request)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;

        $date = date('Y-m-d',strtotime($request->task_date));
        // $time = date('H:i:s',strtotime($request->task_time));

        // Condition to check if task is of Quran or other
        // In case of Quran we can add multiple entry with same Task Time ID
        $user_task = "";
        $chk_category = Task::with('category')->where('id',$request->task_id)->first();
        if($chk_category->category->name=='Quran'){
            $user_task = new UserTask();
            $user_task->task_time_id = $request->task_time_id;
            $user_task->task_date = $date;
            $user_task->quantity = $request->quantity;
            $user_task->user_id = $user_id;
            $user_task->save();
        }
        else{
            foreach($request->task_time_id as $time_id)
            {
                $chk_user_task_time = UserTask::where(['user_id'=>$user_id,'task_date'=>$date,'task_time_id'=>$time_id['taskId']])->get();
                if(count($chk_user_task_time)==0){
                    $user_task = new UserTask();
                    $user_task->task_time_id = $time_id['taskId'];
                    $user_task->task_date = $date;
                    $user_task->task_time = $time_id['Time'];
                    $user_task->quantity = $request->quantity;
                    $user_task->user_id = $user_id;
                    $user_task->save();
                }
            }
        }
        // return response()->json(['success' => 'Task updated successfully'], 200);
        return sendSuccess('Task updated successfully', []);
    }
    public function subCategories($id){
        $sub_categories = Category::with('parentCategory')->where('parent_id',$id)->get();
        // return response()->json(['success' => $sub_categories], 200);
        return sendSuccess('Sub Categories', $sub_categories);
    }
    public function allSubCategories()
    {
        $sub_categories = Category::whereNotNull('parent_id')->get();
        return sendSuccess('Sub Categories', $sub_categories);
    }
    public function createJob(Request $request)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;
        $user = User::find($user_id);
        // $date = date('Y-m-d',strtotime($request->due_date));

        $job = new Job();
        $job->created_by = $user_id;
        $job->title = $request->title;
        $job->category_id = $request->sub_category_id;
        $job->description = $request->description;
        $job->type = 'free';
        $job->max_assignee = $request->max_users;
        $job->quantity = $request->quantity;
        $job->community_id = $request->community_id;
        $job->save();

        if(isset($request->marhooms)){
            foreach($request->marhooms as $marhoom){
                $job_marhoom = new JobMarhoom();
                $job_marhoom->job_id = $job->id;
                $job_marhoom->user_marhoom_id = $marhoom;
                $job_marhoom->save();
            }
        }
        $auto_log_job = new UserJob();
        $auto_log_job->user_id = $user_id;
        $auto_log_job->job_id = $job->id;
        $auto_log_job->count_done = 0;
        $auto_log_job->save();

        $commun_members = CommunityMember::where('community_id',$request->community_id)->get();
        $community = Community::find($request->community_id);
        foreach($commun_members as $comm_member){
            $message = $user->name." has created a new job in ".$community->name;
            \OneSignal::sendNotificationUsingTags(
                $message, array(
            ["field" => "tag", "key" => "user_id", "relation" => "=", "value" => $comm_member->member_id]
                ), $url = null, $data = null, $buttons = null, $schedule = null
            );
            $notification = new Notification();
            $notification->user_id = $comm_member->member_id;
            $notification->notification = $message;
            $notification->type = "community_job";
            $notification->community_id = $request->community_id;
            $notification->save();
        }
        // return response()->json(['success' => 'Job created successfully'], 200);
        return sendSuccess('Job created successfully', []);
    }
    public function userCommunityJobs()
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;

        $jobs = Job::with('community','subCategory','subCategory.parentCategory')->withCount('userJob')->where(['created_by'=>$user_id,'is_deleted'=>0])->get();
        // return response()->json(['success' => $jobs], 200);
        return sendSuccess('User Community Jobs', $jobs);
    }
    public function communityJobDetail($id)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;

        $job = Job::with('community','jobMarhoom','subCategory','subCategory.parentCategory')
                    ->with(['userJob'=> function($q) {
                        $q->orderBy('count_done','DESC')->with('user');
                    }])
                    ->with(['jobKura'=> function($q) use($user_id) {
                        $q->where('user_id',$user_id)->with('jobKuraMarhoom');
                    }])
                    ->withCount(['userJob AS count_done' => function ($query) {
                        return $query->select(DB::raw('SUM(count_done)'));
                    }])
                    ->where('id',$id)
                    ->first();
        // return response()->json(['success' => $job], 200);
        return sendSuccess('Community Job Detail', $job);
    }
    public function updateCommunityJob(Request $request)
    {
        $job = Job::find($request->job_id);
        $job->title = $request->title;
        $job->description = $request->description;
        $job->quantity = $request->quantity;
        $job->max_assignee = $request->max_assignee;
        $job->community_id = $request->community_id;
        $job->category_id = $request->category_id;
        $job->save();
        if(isset($request->marhooms))
        {
            foreach($request->marhooms as $marhoom)
            {
                $job_marhoom = new JobMarhoom();
                $job_marhoom->job_id = $request->job_id;
                $job_marhoom->user_marhoom_id = $marhoom;
                $job_marhoom->save();
            }
        }
        return sendSuccess('Community Job updated successfully', []);
    }
    public function deleteCommunityJob(Request $request)
    {
        // dd('working');
        $job = Job::find($request->id);
        if($job)
        {
            $job->is_deleted = 1;
            $job->save();
            return sendSuccess('Community Job deleted successfully', []);
        }
        return sendError('Job not found', 400);

    }
    public function addSahadat(Request $request)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;
        $user = User::findOrFail($user_id);
        foreach($request->get('emails') as $userData){
            if($userData['email']==$user->email){
                // return response()->json(['error' => 'User cannot add sahadat of his email'], 401);
                return sendError('User cannot add sahadat of his email', 400);
            }
            $chk_email = Sahadat::where(['user_id'=>$user_id,'email'=>$userData['email'],'status'=>
            0])->get();
            if(count($chk_email)==1 || count($chk_email) > 1){
                // return response()->json(['error' => 'Email Address for sahadat already given.'], 401);
                return sendError('Email Address for sahadat already given', 400);
            }
            $sahadat = new Sahadat();
            $sahadat->user_id = $user_id;
            $sahadat->email = $userData['email'];
            $sahadat->gender = $userData['gender'];
            $sahadat->save();
        }
        if(count($request->emails) > 1)
        {
            $user_ids = User::whereRaw('email IN ("'.$request->emails[0]['email'].'","'.$request->emails[1]['email'].'")')->pluck('id');
        }
        else{
            $user_ids = User::whereRaw('email IN ("'.$request->emails[0]['email'].'"')->pluck('id');
        }
        $message = $user->name." has requested you for a shahadat.";
        foreach($user_ids as $id)
        {
            \OneSignal::sendNotificationUsingTags(
                $message, array(
            ["field" => "tag", "key" => "user_id", "relation" => "=", "value" => $id]
                ), $url = null, $data = null, $buttons = null, $schedule = null
            );
            $notify_user = User::find($id);
            $notification = new Notification();
            $notification->user_id = $notify_user->id;
            $notification->notification = $message;
            $notification->type = "sahadat";
            // $notification->community_id = $request->community_id;
            $notification->save();
        }

        // return response()->json(['success' => 'Request for shahadat created successfully'], 200);
        return sendSuccess('Sahadat request created successfully', []);
    }
    public function userSahadats()
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;

        $data['sahadats'] = Sahadat::where(['user_id' => $user_id])->whereIN('status',[0,1])->get();
        $data['count_male'] = Sahadat::with('user')
                                     ->where(['user_id' => $user_id,'status'=> 0,'gender'=>'male'])
                                     ->whereIN('status',[0,1])
                                     ->count();
        $data['count_female'] = Sahadat::with('user')
                                       ->where(['user_id' => $user_id,'status'=> 0,'gender'=>'female'])
                                       ->whereIN('status',[0,1])
                                       ->count();
        // return response()->json(['success' => $data], 200);
        return sendSuccess('User Sahadats', $data);
    }
    public function sahadatRequests()
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;
        $user = User::findOrFail($user_id);

        $sahadats = Sahadat::with('user')->where(['email' => $user->email,'status'=> 0])->get();
        // return response()->json(['success' => $sahadats], 200);
        return sendSuccess('Sahadat Requests', $sahadats);
    }
    public function approveSahadat(Request $request)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;
        $user = User::findOrFail($user_id);

        $sahadat = Sahadat::where(['email' => $user->email,'status'=> 0,'user_id' => $request->id])->first();
        if($sahadat){
            $sahadat->status = 1; //status for accept
            $sahadat->save();
        }
        else{
            // return response()->json(['error' => 'Sahadat not found'], 401);
            return sendError('Sahadat not found', 400);
        }

        $chk_sahadat = Sahadat::where(['user_id' => $request->id,'status'=>1])->get();
        $counts = array();
        if($chk_sahadat){
            foreach($chk_sahadat as $sahadats)
            {
                $counts[] = $sahadats->gender;
            }
        }
        $counts = array_count_values($counts);
        $female_count = 0;
        $male_count = 0;
        if(array_key_exists('female',$counts)){
           $female_count = $counts['female'];
        }
        if(array_key_exists('male',$counts)){
            $male_count = $counts['male'];
        }
        // Update its Provider Status if its Required Witness accept its request
        if($male_count >= 2){
            $user = User::findOrFail($request->id);
            $user->approved_provider = 1;
            $user->save();
            $chk_user_referal = Community::where('refer_email',$user->email)->get();
            foreach($chk_user_referal as $community_refer){
                $community_member = new CommunityMember();
                $community_member->member_id = $request->id;
                $community_member->community_id = $community_refer->id;
                $community_member->save();
            }
        }
        elseif($male_count >= 1 && $female_count >=2){
            $user = User::findOrFail($request->id);
            $user->approved_provider = 1;
            $user->save();
            $chk_user_referal = Community::where('refer_email',$user->email)->get();
            foreach($chk_user_referal as $community_refer){
                $community_member = new CommunityMember();
                $community_member->member_id = $request->id;
                $community_member->community_id = $community_refer->id;
                $community_member->save();
            }
        }
        elseif($female_count >= 4){
            $user = User::findOrFail($request->id);
            $user->approved_provider = 1;
            $user->save();
            $chk_user_referal = Community::where('refer_email',$user->email)->get();
            foreach($chk_user_referal as $community_refer){
                $community_member = new CommunityMember();
                $community_member->member_id = $request->id;
                $community_member->community_id = $community_refer->id;
                $community_member->save();
            }
        }
        $message = "Your sahadat request is accepted.";
        \OneSignal::sendNotificationUsingTags(
            $message, array(
        ["field" => "tag", "key" => "user_id", "relation" => "=", "value" => $request->id]
            ), $url = null, $data = null, $buttons = null, $schedule = null
        );
        $notification = new Notification();
        $notification->user_id = $request->id;
        $notification->notification = $message;
        $notification->type = "sahadat_accepted";
        $notification->save();
        // return response()->json(['success' => 'Sahadat approved successfully'], 200);
        return sendSuccess('Sahadat approved successfully', []);

    }
    public function rejectSahadat(Request $request){
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;
        $user = User::findOrFail($user_id);

        $sahadat = Sahadat::where(['email' => $user->email,'status'=> 0,'user_id' => $request->id])->first();
        if($sahadat){
            $sahadat->status = 2; //status for reject
            $sahadat->save();
        }
        else{
            // return response()->json(['error' => 'Sahadat not found'], 401);
            return sendError('Sahadat not found', 400);
        }

        $message = "Your sahadat request is rejected.";
        \OneSignal::sendNotificationUsingTags(
            $message, array(
        ["field" => "tag", "key" => "user_id", "relation" => "=", "value" => $request->id]
            ), $url = null, $data = null, $buttons = null, $schedule = null
        );
        $notification = new Notification();
        $notification->user_id = $request->id;
        $notification->notification = $message;
        $notification->type = "sahadat_rejected";
        $notification->save();
        // return response()->json(['success' => 'Sahadat rejected successfully'], 200);
        return sendSuccess('Sahadat rejected successfully', []);
    }
    // public function saveProviderCategories(Request $request)
    // {
    //     $headers = getallheaders();
    //     $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
    //     $user_id = $checksession->user_id;
    //     foreach($request->get('categories') as $providerCategory){
    //         $category = new ProviderCategory();
    //         $category->amaal_subcategory_id = $providerCategory['id'];
    //         $category->user_id = $user_id;
    //         $category->save();
    //     }
    //     return response()->json(['success' => 'Categories added successfully'], 200);
    // }
    // public function jobApply(Request $request)
    // {
    //     $headers = getallheaders();
    //     $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
    //     $user_id = $checksession->user_id;
    //     $user = User::findOrFail($user_id);
    //     $job = Job::findOrFail($request->id);

    //     // Check user already applied to job or not
    //     $chk_job_apply = JobsApplied::where(['job_id' => $request->id,'user_id'=>$user])->get();
    //     if(count($chk_job_apply)>0){
    //         return response()->json(['error' => 'Already applied for this Job.'], 401);
    //     }
    //     if((($user->account_type == 'free' || $user->account_type == 'paid') && $job->type=='free')
    //         || ($user->account_type == 'paid' && $job->type=='paid'))
    //     {
    //         $job_apply = new JobsApplied();
    //         $job_apply->job_id = $request->id;
    //         $job_apply->user_id = $user_id;
    //         $job_apply->save();
    //         return response()->json(['success' => 'Application for Job submitted successfully'], 200);
    //     }
    //     else{
    //         return response()->json(['error' => 'You must be subscribed to Paid account to apply for this Job'], 401);
    //     }
    // }
    // public function appliedJobs(){
    //     $headers = getallheaders();
    //     $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
    //     $user_id = $checksession->user_id;

    //     $applied_jobs = JobsApplied::where('status',0)->with('jobs', function($q) use($user_id){
    //                     $q->where('created_by', $user_id);
    //                 })->get();
    //     return response()->json(['success' => $applied_jobs], 200);
    // }
    // public function acceptJobApplication(Request $request){
    //     $chk_job_application = JobsApplied::where(['job_id'=>$request->job_id,'status'=>1])->get();
    //     if(count($chk_job_application)>0){
    //         return response()->json(['error' => 'Already assign this job to another User'], 401);
    //     }
    //     $job_application = JobsApplied::where(['job_id'=>$request->job_id,'user_id'=>$request->user_id])->first();
    //     $job_application->status = 1;
    //     $job_application->save();



    //     return response()->json(['success' => 'Application for Job accepted successfully'], 200);
    // }
    // public function rejectJobApplication(Request $request)
    // {
    //     $job_application = JobsApplied::where(['job_id'=>$request->job_id,'user_id'=>$request->user_id])->first();
    //     $job_application->status = 2; // reject application
    //     $job_application->save();
    //     return response()->json(['success' => 'Application for Job rejected successfully'], 200);
    // }
    // public function allJobs()
    // {
    //     //Show Jobs to Users that is already assigned to someone??
    // }
    public function blockUser(Request $request)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;

        $block = User::findOrFail($request->id);
        $block->is_blocked = 1;
        $block->blocked_by = $user_id;
        $block->save();
        // return response()->json(['success' => 'User blocked successfully'], 200);
        return sendSuccess('User blocked successfully', []);
    }
    public function unblockUser(Request $request)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;

        $un_block = User::where(['id'=> $request->id, 'is_blocked'=>1 , 'blocked_by'=>$user_id])->first();
        $un_block->is_blocked = 0;
        $un_block->blocked_by = null;
        $un_block->save();
        // return response()->json(['success' => 'User unblocked successfully'], 200);
        return sendSuccess('User unblocked successfully', []);
    }
    public function userMarhooms()
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;

        $marhooms = UserMarhoom::where('user_id',$user_id)->get();
        return sendSuccess('User Marhooms', $marhooms);
    }
    public function userMarhoom($id)
    {
        $marhoom = UserMarhoom::find($id);
        return sendSuccess('User Marhoom', $marhoom);
    }
    public function addMarhoom(Request $request){
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;

        $marhoom = new UserMarhoom();
        $marhoom->user_id = $user_id;
        $marhoom->name = $request->name;
        $marhoom->relation = $request->relation;
        $marhoom->georgian_birth_date = $request->georgian_birth_date;
        $marhoom->georgian_death_date = $request->georgian_death_date;
        // $marhoom->hijri_birth_date = $request->hijri_birth_date;
        // $marhoom->hijri_death_date = $request->hijri_death_date;
        $marhoom->save();
        // return response()->json(['success' => 'Marhoom created successfully'], 200);
        return sendSuccess('Marhoom added successfully', []);
    }
    public function editMarhoom(Request $request)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;

        $marhoom = UserMarhoom::find($request->id);
        $marhoom->name = $request->name;
        $marhoom->relation = $request->relation;
        $marhoom->georgian_birth_date = $request->georgian_birth_date;
        $marhoom->georgian_death_date = $request->georgian_death_date;
        $marhoom->save();
        return sendSuccess('Marhoom edited successfully', []);
    }
    public function deleteMarhoom(Request $request)
    {
        $marhoom = UserMarhoom::find($request->id)->delete();
        // return response()->json(['success' => 'Marhoom deleted successfully'], 200);
        return sendSuccess('Marhoom deleted successfully', []);
    }
    public function addCommunityRequest(Request $request)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;
        $user = User::findOrFail($user_id);
        if($user->approved_provider == 0){
            // return response()->json(['error' => 'Your must be approved provider to join the Community'], 401);
            return sendError("User must be approved provider to join the Community", 400);
        }
        $errors = 0;
        foreach($request->emails as $email){
            // 1) check he is the valid user or not
            // 2) check he is member of community or not
            // 3) If yes, then generate request otherwise send Error that members didn't exsist
            $chk_user = User::where('email',$email)->first();
            if($chk_user){
                $chk_member  = $user->communities()->where('community_id',12)->exists();
                if($chk_member){
                    $chk_request = CommunityRequest::where(['user_id'=>$user_id, 'request_to'=>$chk_user->id,'community_id'=>$request->community_id])->get();
                    if(count($chk_request)>=1){
                        $error= 'Already requested to member against this email('.$email.')';
                        // return response()->json(['error' => $error], 401);
                        return sendError($error, 400);
                        $errors++;
                    }
                }
                else{
                    $error = 'There is no member against this email('.$email.')';
                    // return response()->json(['error' => $response], 401);
                    return sendError($error, 400);
                    $errors++;
                }
            }
            elseif(!$chk_user){
                $error = 'User not exists against this email('.$email.')';
                // return response()->json(['error' => $response], 401);
                return sendError($error, 400);
                $errors++;
            }
        }
        if($errors == 0)
        {
            foreach($request->emails as $email){
                $chk_user = User::where('email',$email)->first();
                if($chk_user)
                {
                    $community_request = new CommunityRequest();
                    $community_request->user_id = $user_id;
                    $community_request->request_to = $chk_user->id;
                    $community_request->community_id = $request->community_id;
                    $community_request->save();
                    $message = $user->name." has sent you request to join community";
                    \OneSignal::sendNotificationUsingTags(
                        $message, array(
                    ["field" => "tag", "key" => "user_id", "relation" => "=", "value" => $chk_user->id]
                        ), $url = null, $data = null, $buttons = null, $schedule = null
                    );
                    $notification = new Notification();
                    $notification->user_id = $chk_user->id;
                    $notification->notification = $message;
                    $notification->type = "community_join_request";
                    $notification->community_id = $request->community_id;
                    $notification->save();
                }

            }

            return sendSuccess('Your request forward successfully', []);
        }
        // return response()->json(['success' => 'Your request to join community forward successfully to provided members.'], 200);
    }
    // User which make requests to join community
    public function userCommunityRequests(){
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;

        $community_requests = CommunityRequest::with('community')->where('user_id',$user_id)->get();
        // return response()->json(['success' => $community_requests], 200);
        return sendSuccess('User Communtiy Requests', $community_requests);
    }
    // Member of community to which another user send request to join community
    public function joinCommunityRequest()
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;

        $community_requests = CommunityRequest::with('community','user')->where(['request_to'=>$user_id,'status'=>0])->get();
        // return response()->json(['success' => $community_requests], 200);
        return sendSuccess('User Joined Communtiy Requests', $community_requests);
    }
    public function acceptCommunityRequest(Request $request)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;

        // Status: '0' for pending , '1' for accept & '2' for reject
        $community_requests = CommunityRequest::where(['id'=> $request->id])->first();
        if($community_requests){
            $community_requests->status = 1;
            $community_requests->save();
        }
        else{
            // return response()->json(["error" => "Request didn't exists."], 401);
            return sendError("Request didn't exists", 400);
        }


        $chk_requests = CommunityRequest::where(['user_id' => $community_requests->user_id,
                                                 'status' => 1,
                                                 'community_id' => $community_requests->community_id])
                                        ->get();
        if(count($chk_requests) > 1){
            $new_comm_member = new CommunityMember();
            $new_comm_member->member_id = $community_requests->user_id;
            $new_comm_member->community_id = $community_requests->community_id;
            $new_comm_member->save();
        }
         $community = Community::find($community_requests->community_id);
        $request_to = User::find($community_requests->request_to);
        $request_by = User::find($community_requests->user_id);
        $message = "Your request has been accepted to join ".$community->name." .";

        \OneSignal::sendNotificationUsingTags(
            $message, array(
        ["field" => "tag", "key" => "user_id", "relation" => "=", "value" => $community_requests->user_id]
            ), $url = null, $data = null, $buttons = null, $schedule = null
        );
        $notification = new Notification();
        $notification->user_id = $community_requests->user_id;
        $notification->notification = $message;
        $notification->type = "community_request_accepted";
        $notification->community_id = $community_requests->community_id;
        $notification->save();
        return sendSuccess('Request accepted successfully', []);
    }
    public function rejectCommunityRequest(Request $request)
    {
        $community_request = CommunityRequest::where(['id'=>$request->id,'status'=>0])->first();
        if($community_request){
            $community_request->status = 2;
            $community_request->save();
        }
        else{
            // return response()->json(["error" => "Request didn't exists."], 401);
            return sendError("Request didn't exists", 400);
        }
        $community = Community::find($community_request->community_id);
        $request_to = User::find($community_request->request_to);
        $request_by = User::find($community_request->user_id);
        $message = "Your request has been rejected to join ".$community->name." .";

        \OneSignal::sendNotificationUsingTags(
            $message, array(
        ["field" => "tag", "key" => "user_id", "relation" => "=", "value" => $community_request->user_id]
            ), $url = null, $data = null, $buttons = null, $schedule = null
        );
        $notification = new Notification();
        $notification->user_id = $community_request->user_id;
        $notification->notification = $message;
        $notification->type = "community_request_rejected";
        $notification->community_id = $community_request->community_id;
        $notification->save();

        return sendSuccess('Request rejected successfully', []);
    }
    public function allCommunities(Request $request)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;
        $user = User::find($user_id);
        // dd($user_id);
        // Communities of which user is not a member nor he applied
        $data['all_communities'] = Community::where('is_active',1)
            ->where('type','public')
            ->orWhere('type', 'restricted')
            ->orderBy('created_at', 'DESC')
            ->get();
        // Communities which user is member or which he applied & Draft communities
        // Active, Pending & Draft communities

        $data['User_details'] = User::with(['communities.users','communities.jobs' => function($query){
            $query->where('type',5);
        }])
            ->find($user_id);

        $totalMembers = User::with(['communities' => function($query){
            $query->withCount('users');
        }])
            ->find($user_id);

        $totalJobs = User::with(['communities' => function($query){
            $query->withCount('jobs');
        }])
            ->find($user_id);

        if($totalJobs != "")
        $data['total_jobs'] = $totalJobs->communities[0]->jobs_count;

        if($totalMembers != "")
        $data['total_members'] = $totalMembers->communities[0]->users_count;

        if(str_contains($user->address, 'Pakistan'))
        {
            $allCommunities = array();
            foreach ($data['all_communities'] as $community)
            {
                if(str_contains($community->address, 'India'))
                    continue;
                array_push($allCommunities, $community);
            }
            $data['all_communities'] = $allCommunities;
        }
        if(str_contains($user->address, 'India'))
        {
            $allCommunities = array();
            foreach ($data['all_communities'] as $community)
            {
                if(str_contains($community->address, 'Pakistan'))
                    continue;
                array_push($allCommunities, $community);
            }
            $data['all_communities'] = $allCommunities;
        }
        return sendSuccess('All Communities',$data);
    }
    public function communityTasks($id)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;
        $community_jobs = Job::where(['community_id'=>$id,'is_deleted'=>0])->with('subCategory')
                                ->withCount(['userJob AS count_done' => function ($query) {
                                    return $query->select(DB::raw('SUM(count_done)'));
                                 }])
                                 ->with('userJob', function($q) use($user_id){
                                    $q->where('user_id',$user_id);
                                })
                                ->orderBy('created_at', 'DESC')
                                 ->get();
        return sendSuccess('Community Tasks',$community_jobs);
    }
    public function logTask(Request $request)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;
        $job = Job::findOrFail($request->job_id);
        $job_max_assignee = $job->max_assignee;
        if($job_max_assignee != null and $job->type != 5) {
            $job_assignee_users = UserJob::where('job_id', $request->job_id)->get();
            if (count($job_assignee_users) >= $job_max_assignee) {
                return sendError("Task maximum users limit reached", 400);
            }
        }
        $user_job = new UserJob();
        $user_job->user_id = $user_id;
        $user_job->job_id = $request->job_id;
        $user_job->save();
        return sendSuccess('You have successfully joined the Group',[]);
    }
    public function updatelogTask(Request $request)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;

        $job = Job::findOrFail($request->job_id);
        $job_quant = $job->quantity;
        $job_quant_done = UserJob::where('job_id',$request->job_id)->sum('count_done');

        if(($job_quant_done + $request->count) > $job_quant) {
            // return response()->json(['error' => 'Cannot add more than Job Quantity'], 401);
            return sendError("Cannot add more than Job Quantity", 400);
        }

        $user_job = UserJob::where(['user_id'=>$user_id,'job_id'=>$request->job_id])->first();
        $count_done = $user_job->count_done + $request->count;
        $user_job->count_done = $count_done;
        $user_job->save();
        // return response()->json(['success' => 'Task update successfully'], 200);
        return sendSuccess('Task update successfully',[]);
    }
    public function userTaskDetails($id)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;

        $user_job = UserJob::where(['job_id'=>$id,'user_id'=>$user_id])->first();
        return sendSuccess('User Task Details',$user_job);
    }
    public function userlogTasks()
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;

        $job_task = UserJob::with('job')->where('user_id',$user_id)->orderBy('created_at', 'DESC')->get();
        // return response()->json(['success' => $job_task], 200);
        return sendSuccess('User Log Tasks',$job_task);
    }
    public function communitytaskUsers($id)
    {
        $job_users = UserJob::with('job','user')->where('job_id',$id)->get();
        // return response()->json(['success' => $job_users], 200);
        return sendSuccess('Community Task Users',$job_users);
    }

    public function createCommunity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'description' => 'required',
            // 'image' => 'required'
        ]);
        if ($validator->fails()) {
            // $errors = implode(', ', $validator->errors()->all());
            $errors = $validator->errors()->all();
            return sendError($errors, 400);
        }
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;
        $message = "";
        // Check that refferal Email is User of App or not
        $chk_refer = User::where(['email'=>$request->email,'type'=>'Customer','approved_provider'=>1])->first();

        $new_comm = new Community();
        $new_comm->name = $request->name;
        $new_comm->description = $request->description;
        $new_comm->address = $request->address;
        $new_comm->type = "restricted";
        if(is_null($chk_refer)){
            // "0"=> Disabled, "1"=>Active, "2"=>Pending,"3"=>Draft
            $new_comm->is_active = 3;
            $message = "Your request to create community is in Draft";
        }
        else{
            $new_comm->is_active = 2;
            $message = "Your request to create community is Pending";
        }
        $new_comm->created_by = $user_id;
        $new_comm->refer_email = $request->email;
        $new_comm->save();

        $new_comm->users()->attach($user_id);
        if(!is_null($chk_refer)){
            $new_comm->users()->attach($chk_refer->id);
        }
        return sendSuccess($message,[]);

    }
    public function userCommunities()
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;

        $communities = Community::where('created_by',$user_id)->get();
        // return response()->json(['success' => $communities], 200);
        return sendSuccess('User Communities',$communities);
    }
    public function taqleed()
    {
        $taqleed = Taqleed::all();
        // return response()->json(['success' => $taqleed], 200);
        return sendSuccess('Taqleeds',$taqleed);
    }
    public function getUserStats($id,$month,$year)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;
        // dd($date.'&&&'.$month.'year'.$year);
        // For Count of User Task in Specified Month
        $sub_category = Category::where('parent_id',$id)->pluck('id');
        $task = Task::where(['is_active' => 1])->whereIn('category_id',$sub_category)->pluck('id');

        //Weekly Tasks tasks
        $all_weekly_tasks = 0;
        $weekly_task = Task::with('taskDay')->where(['is_active'=>1,'recurring_type'=>'weekly'])->whereIn('category_id',$sub_category)->get();

            if(count($weekly_task) > 0)
            {
                foreach($weekly_task as $week_task)
                {
                    $week_task_count = $week_task->task_count;
                    if(count($week_task['taskDay']) > 0)
                    {
                        $days = 0;
                        foreach($week_task['taskDay'] as $task_day)
                        {
                            $total_days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
                            for($i=0;$i<=$total_days;$i++)
                            {
                                if(date('N',strtotime($year.'-'.$month.'-'.$i))==$task_day->number)
                                {
                                    $days++;
                                }
                            }
                        }
                        $all_weekly_tasks = $all_weekly_tasks + ($days * $week_task_count);
                    }
                }
            }
        // Monthly Tasks
        $all_monthly_tasks = 0;
        $monthly_task = Task::with('taskDay')->where(['is_active'=>1,'recurring_type'=>'monthly'])->whereIn('category_id',$sub_category)->get();

            if(count($monthly_task) > 0)
            {
                foreach($monthly_task as $month_task)
                {
                    $month_task_count = $month_task->task_count;
                    $count_month_days = count($month_task['taskDay']);
                    $all_monthly_tasks = $all_monthly_tasks + ($count_month_days * $month_task_count);
                }
            }
        // Daily Tasks
        $all_daily_tasks = 0;
        $daily_task = Task::where(['is_active'=>1,'recurring_type'=>'daily'])->whereIn('category_id',$sub_category)->get();
            if(count($daily_task) > 0)
            {
                $days_no_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                foreach($daily_task as $daly_task)
                {
                    $daily_task_count = $daly_task->task_count;
                    $all_daily_tasks = $all_daily_tasks + ($daily_task_count * $days_no_month);
                }
            }
        // Yearly Tasks
        $all_yearly_tasks = 0;
        $yearly_task = Task::where(['is_active'=>1,'recurring_type'=>'yearly'])->whereIn('category_id',$sub_category)->get();
            if(count($yearly_task) > 0)
            {
                foreach($yearly_task as $year_task)
                {
                    $all_yearly_tasks = $all_yearly_tasks + $year_task->task_count;
                }
            }
        // Once Tasks
        $all_once_tasks = 0;
        $once_tasks = Task::where(['is_active'=>1,'recurring_type'=>'once'])->whereIn('category_id',$sub_category)->get();
            if(count($once_tasks) > 0)
            {
                foreach($once_tasks as $once_task)
                {
                    $all_once_tasks = $all_once_tasks + $once_task->task_count;
                }
            }
        //Count All Tasks
        $total_tasks = $all_yearly_tasks + $all_monthly_tasks + $all_daily_tasks + $all_weekly_tasks + $all_once_tasks;

        $task_time_id = TaskTime::whereIn('task_id',$task)->pluck('id');
        $count_user_task = UserTask::where('user_id',$user_id)
                                   ->whereIn('task_time_id',$task_time_id)
                                   ->whereMonth('task_date', $month)
                                   ->whereYear('task_date', $year)
                                   ->sum('quantity');

        // Sum all of its Naiki Through out its first task
        $user_tasks = UserTask::where('user_id',$user_id)->whereIn('task_time_id',$task_time_id)->get();

        $total_naiki = 0;
        foreach($user_tasks as $user_task)
        {
            $quant = $user_task->quantity;
            $task_detail = TaskTime::with('task','task.category')->where('id',$user_task->task_time_id)->first();
            $task_naiki = $task_detail->task->category->naiki;
            $total_naiki = $total_naiki + ($quant * $task_naiki);
        }

        $allItems = collect([
            'count_user_task'   => $count_user_task,
            'total_naiki' => $total_naiki,
            'total_tasks' => $total_tasks,
        ]);
        // return response()->json(['success' => $allItems], 200);
        return sendSuccess('User Stats',$allItems);
    }
    public function saveCommunityMessage(Request $request)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;
        $user = User::find($user_id);

        $new_message = new CommunityMessage();
        $new_message->message = $request->message;
        $new_message->sender_id = $user_id;
        $new_message->community_id = $request->community_id;
        $new_message->save();

        $community = Community::find($request->community_id);
        $commun_members = $community->users()->get();
        foreach($commun_members as $comm_member){
            $message = $user->name." has sent a new message in ".$community->name;
            \OneSignal::sendNotificationUsingTags(
                $message, array(
            ["field" => "tag", "key" => "user_id", "relation" => "=", "value" => $comm_member->member_id]
                ), $url = null, $data = null, $buttons = null, $schedule = null
            );
            $notification = new Notification();
            $notification->user_id = $comm_member->member_id;
            $notification->notification = $message;
            $notification->type = "community_message";
            $notification->community_id = $request->community_id;
            $notification->save();
        }
        return sendSuccess('Message saved successfully',[]);
    }
    public function getCommunityChat($id)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;

        $community_chat = CommunityMessage::with('user')->where('community_id',$id)->orderBy('created_at', 'DESC')->get();
        return sendSuccess('Community Chat',$community_chat);
    }
    public function getUserCommunityChatCount($id)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;

        $count_message = CommunityMessage::where('community_id',$id)->whereNotIn('sender_id',[$user_id])
                        ->whereDoesntHave('read_message', function ($q) use($user_id) {
                            $q->where('user_id', $user_id);
                        })
                        ->count();
        return sendSuccess('User Unread Chat Count',$count_message);
    }
    public function readCommunityMessage(Request $request)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;

        $unread_msg_id = CommunityMessage::where('community_id',$request->id)->whereNotIn('sender_id',[$user_id])
                        ->whereDoesntHave('read_message', function ($q) use($user_id) {
                            $q->where('user_id', $user_id);
                        })
                        ->pluck('id');
        foreach($unread_msg_id as $msg_id)
        {
            $read_msg = new ReadMessage();
            $read_msg->community_message_id = $msg_id;
            $read_msg->user_id = $user_id;
            $read_msg->save();
        }
        return sendSuccess('All messages read successfully',[]);
    }
    public function notifications()
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;

        $notifications = Notification::with('community')->where(['user_id'=>$user_id])->orderBy('created_at', 'DESC')->get();
        return sendSuccess('Notifications',$notifications);
    }
    public function readNotification(Request $request)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;
        $notification = Notification::where(['user_id'=>$user_id,'is_read'=>0])->update([
            'is_read' =>  1,
        ]);
        return sendSuccess('Notifications read successfully',[]);
    }

    /////////////////////////// V2 Functions //////////////////////////////
    public function newCommunityJob(Request $request)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;
        $user = User::find($user_id);
        $due_date = date('Y-m-d',strtotime($request->due_date));
        $job = new Job();
        $job->user_id = $user_id;
        $job->title = $request->title;
        $job->category_id = $request->sub_category_id;
        $job->description = $request->description;
        $job->max_assignee = $request->max_users;
        $job->due_date = $due_date;
        if($request->type == 0)
        {
            $job->type = "kura";
            $job->winners = $request->winners;
        }
        elseif($request->type == 1)
        {
            $job->type = "donation";
        }
        elseif($request->type == 2)
        {
            $job->type = "majlis";
        }
        $job->quantity = $request->quantity;
        $job->community_id = $request->community_id;
        $job->save();

        if(isset($request->marhooms)){
            foreach($request->marhooms as $marhoom){
                $job_marhoom = new JobMarhoom();
                $job_marhoom->job_id = $job->id;
                $job_marhoom->user_marhoom_id = $marhoom;
                $job_marhoom->save();
            }
        }
        $auto_log_job = new UserJob();
        $auto_log_job->user_id = $user_id;
        $auto_log_job->job_id = $job->id;
        $auto_log_job->count_done = 0;
        $auto_log_job->save();

        $community = Community::find($request->community_id);
        $commun_members = $community->users()->get();
        foreach($commun_members as $comm_member){
            $message = $user->name." has created a new job in ".$community->name;
            \OneSignal::sendNotificationUsingTags(
                $message, array(
            ["field" => "tag", "key" => "user_id", "relation" => "=", "value" => $comm_member->member_id]
                ), $url = null, $data = null, $buttons = null, $schedule = null
            );
            $notification = new Notification();
            $notification->user_id = $comm_member->member_id;
            $notification->notification = $message;
            $notification->type = "community_job";
            $notification->community_id = $request->community_id;
            $notification->save();
        }
        // return response()->json(['success' => 'Job created successfully'], 200);
        return sendSuccess('Job created successfully', []);
    }
    public function updateCommunitylogTask(Request $request)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;

        $job = Job::findOrFail($request->job_id);
        $job_quant = $job->quantity;
        $job_quant_done = UserJob::where('job_id',$request->job_id)->sum('count_done');

        $due_date = date('Y-m-d',strtotime($job->due_date));
        $current_date = date('Y-m-d',strtotime(now()));

        if($current_date > $due_date){
            return sendError("Cannot add quantity after last date", 400);
        }

        if(($job_quant_done + $request->count) > $job_quant) {
            // return response()->json(['error' => 'Cannot add more than Job Quantity'], 401);
            return sendError("Cannot add more than Job Quantity", 400);
        }

        $user_job = UserJob::where(['user_id'=>$user_id,'job_id'=>$request->job_id])->first();
        $count_done = $user_job->count_done + $request->count;
        $user_job->count_done = $count_done;
        $user_job->save();
        // return response()->json(['success' => 'Task update successfully'], 200);
        return sendSuccess('Task update successfully',[]);
    }
    public function addCard(Request $request)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;
        $user = User::find($user_id);

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));


        try {
            //    --------------------------- 1st Attempt --------------------------------------
            // if(is_null($user->stripe_id))
            // {
            //     $create = $stripe->customers->create([
            //         'name' => $user->name,
            //         'email' => $user->email,
            //         'phone' => $user->phone,
            //     ]);
            //     $user->stripe_id = $create->id;
            //     $user->save();
            // }
            // $card = $stripe->tokens->create([
            //   'card' => [
            //     'name' => $request->name,
            //     'number' => $request->number,
            //     'exp_month' => $request->month,
            //     'exp_year' => $request->year,
            //     'cvc' => $request->cvc,
            //   ],
            // ]);

            // $new_card = new UserCard();
            // $new_card->name = $request->name;
            // $new_card->number = $request->number;
            // $new_card->cvc = $request->cvc;
            // $new_card->exp_month = $request->month;
            // $new_card->exp_year = $request->year;
            // $new_card->user_id = $user->id;
            // $new_card->token = $card->id;
            // $new_card->save();
        //    --------------------------- 2nd Attempt --------------------------------------
            // \Stripe\Stripe::setApiKey('sk_test_51IwMY9CaMlpdFmXbfaKpUQPDqpk4j3DHeNPhqlICDujpw0MKewTj27MEsw0XORPE1hbMEQLtvvjIvMG0xl3tY6Oa00ENbaYQCb');

            // $payment = \Stripe\PaymentIntent::create([
            // 'amount' => 1000,
            // 'currency' => 'usd',
            // 'payment_method_types' => ['card'],
            // ]);
            // return sendSuccess('Card added successfully',[$payment]);

            //    <<<--------------------------- 3rd Attempt -------------------------------------->>>
              // Use an existing Customer ID if this is a returning customer.
            // if(is_null($user->stripe_id))
            // {
            //     $customer = \Stripe\Customer::create();
            //     // $customer = $stripe->customers->create([
            //     //     'name' => $user->name,
            //     //     'email' => $user->email,
            //     //     'phone' => $user->phone,
            //     // ]);
            //     // $user->stripe_id = $customer->id;
            //     // $user->save();
            //     $customer_id = $customer->id;
            // }
            // else{
            //     $customer_id = $user->stripe_id;
            // }
            $customer_id = "";
            if(is_null($user->stripe_id) or !$user->stripe_id)
            {
                $customer = \Stripe\Customer::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                ]);
                $user->stripe_id = $customer->id;
                $user->save();
                $customer_id = $customer->id;
            }
            else{
                $customer_id = $user->stripe_id;
            }

            $ephemeralKey = \Stripe\EphemeralKey::create(
                ['customer' => $customer_id],
                ['stripe_version' => '2020-08-27']
            );
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => ($request->amount)*100,
//                'currency' => is_null($user->currency_code) ? 'usd' : $user->currency_code,
                'currency' => 'usd',
                'customer' => $customer_id
            ]);
            $transaction = new UserTransaction();
            $transaction->user_id = $user->id;
            $transaction->amount = $request->amount;
            $transaction->category = $request->category;
            $transaction->payment_id = $paymentIntent->client_secret;
            $transaction->transaction_type = 1;
            $transaction->save();

            return sendSuccess('Card added successfully',[
                'paymentIntent' => $paymentIntent->client_secret,
                'ephemeralKey' => $ephemeralKey->secret,
                'customer' => $customer_id
            ]);
        } catch(\Stripe\Exception\CardException $e) {
          // Since it's a decline, \Stripe\Exception\CardException will be caught
          echo 'Status is:' . $e->getHttpStatus() . '\n';
          echo 'Type is:' . $e->getError()->type . '\n';
          echo 'Code is:' . $e->getError()->code . '\n';
          // param is '' in this case
          echo 'Param is:' . $e->getError()->param . '\n';
          echo 'Message is:' . $e->getError()->message . '\n';
        } catch (\Stripe\Exception\RateLimitException $e) {
            return sendError('Card Error RateLimitException',[$e]);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return sendError('Card Error InvalidRequestException'.$e->getError()->message,[$e]);
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return sendError('Card Error AuthenticationException'.$e->getError()->message,[$e]);
          // (maybe you changed API keys recently)
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return sendError('Card Error ApiConnectionException'.$e->getError()->message,[$e]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return sendError('Card Error ApiErrorException'.$e->getError()->message,[$e]);
          // yourself an email
        } catch (Exception $e) {
            return sendError('Card Error Exception'.$e->getError()->message,[$e]);
        }


    }
    public function paymentSuccess(Request $request)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;
        $user = User::find($user_id);

        $transaction = UserTransaction::where(['user_id'=>$user->id,'payment_id'=>$request->token])->first();
        $transaction->is_success = 1;
        $transaction->save();
        return sendSuccess('Payment Success',[]);
    }
    public function userTransactions()
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;
        $user = User::find($user_id);

        $user_transaction = UserTransaction::where(['user_id'=>$user->id,'is_success'=>1])->orderBy('created_at','DESC')->get();
        return sendSuccess('User Transactions',$user_transaction);
    }
    public function addTransaction(Request $request)
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;
        $transaction_category = VUserTransaction::select('*')->where('user_id',$user_id)->orderBy('category','ASC')->get();
        if($transaction_category[0]['balance'] < $request->amount)
        {
            return sendError('Cannot add more than wallet amount',[]);
        }
        // 1st One
        $new_credit_trans = new UserTransaction();
        $new_credit_trans->user_id = $user_id;
        $new_credit_trans->amount = $request->amount;
        $new_credit_trans->category = 0;
        $new_credit_trans->transaction_type = 2;
        $new_credit_trans->is_success = 1;
        $new_credit_trans->save();
        // 2nd One
        $new_credit_trans = new UserTransaction();
        $new_credit_trans->user_id = $user_id;
        $new_credit_trans->amount = $request->amount;
        $new_credit_trans->category = $request->category;
        $new_credit_trans->transaction_type = 1;
        $new_credit_trans->is_success = 1;
        $new_credit_trans->save();
        // 3rd One
        $new_credit_trans = new UserTransaction();
        $new_credit_trans->user_id = $user_id;
        $new_credit_trans->amount = $request->amount;
        $new_credit_trans->category = $request->category;
        $new_credit_trans->transaction_type = 2;
        $new_credit_trans->is_success = 1;
        $new_credit_trans->save();
        // 4TH One
        $new_debit_trans = new UserTransaction();
        $new_debit_trans->user_id = 6;
        $new_debit_trans->amount = $request->amount;
        $new_debit_trans->category = $request->category;
        $new_debit_trans->transaction_type = 1;
        $new_debit_trans->is_success = 1;
        $new_debit_trans->ref_id = $user_id;
        $new_debit_trans->save();
        return sendSuccess('Transaction Successfull',[]);
    }
    public function allTransactions()
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;

        $transaction_category = VUserTransaction::select('*')->where('user_id',$user_id)->orderBy('category','ASC')->get();
        return sendSuccess('All Transactions',$transaction_category);
    }
    public function addKuraMarhoom(Request $request)
    {
        $kura_marhoom = new JobKuraMarhoom();
        $kura_marhoom->job_kura_id = $request->kura_id;
        $kura_marhoom->user_marhoom_id = $request->marhoom_id;
        $kura_marhoom->save();
        return sendSuccess('Naiki successfully assigned to Marhoom',[]);
    }
    public function unreadNotificationCount()
    {
        $headers = getallheaders();
        $checksession = LoginUser::where('session_key', $headers['session_token'])->first();
        $user_id = $checksession->user_id;

        $notification = Notification::where(['user_id'=>$user_id,'is_read'=>0])->count();
        return sendSuccess('Unread Notification Count',$notification);
    }
}
