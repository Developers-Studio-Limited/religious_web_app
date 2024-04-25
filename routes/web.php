<?php

use App\Http\Controllers\JobController;
use App\Http\Controllers\ServiceProvideController;
use App\Http\Controllers\TaskTagsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('home');
});
// Route::get('/inf',function(){echo phpinfo();});
Auth::routes();

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('contact-us', [UserController::class, 'contactUs'])->name('contact-us');
Route::post('submit-contact', [UserController::class, 'submitContactUs'])->name('submit-contact');
Route::get('forget-password/{token}',[ForgetPasswordController::class,'forgetPassword'])->name('forget-password');
Route::post('submit/forget-password',[ForgetPasswordController::class,'submitForgetPassword'])->name('submit-forget-password');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/home', [HomeController::class, 'index'])->middleware('isAdmin')->name('home');
Route::get('/amaal', [HomeController::class, 'amaal'])->name('amaal');

Route::get('/providers', [HomeController::class, 'providers'])->name('providers');
Route::get('/customers', [HomeController::class, 'customers'])->name('customers');
Route::post('/create', [HomeController::class, 'create'])->name('create');
Route::get('/profile/{id}', [HomeController::class, 'profile'])->name('profile');
Route::get('/delete/{id}', [HomeController::class, 'delete'])->name('delete-user');

// Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/category', [HomeController::class, 'category'])->name('amaal');
Route::post('/add-category', [HomeController::class, 'createCategory'])->name('add-amaal');
Route::post('/delete-category', [HomeController::class, 'deleteCategory'])->name('delete-amal');
Route::get('/edit-category/{id}', [HomeController::class, 'editCategory'])->name('edit-amaal');
Route::post('/submit-category', [HomeController::class, 'submitCategory'])->name('submit-amal');
// SUb Category Routes
Route::get('/sub-category', [HomeController::class, 'subCategory'])->name('sub-category');
Route::post('add/sub-category', [HomeController::class, 'addSubCategory'])->name('add-sub-category');
Route::get('edit/sub-category/{id}', [HomeController::class, 'editSubCategory'])->name('edit-sub-category');
Route::post('update/sub-category', [HomeController::class, 'updateSubCategory'])->name('update-sub-category');

// Sub Amaal/Tasks Routes
Route::get('/tasks', [HomeController::class, 'tasks'])->name('tasks');
Route::get('/add/task', [HomeController::class, 'addTask'])->name('add-task');
Route::post('/create/task', [HomeController::class, 'createTask'])->name('create-task');
Route::get('/view/task/{id}', [HomeController::class, 'viewTask'])->name('view-task');
Route::get('/edit-task/{id}', [HomeController::class, 'editTask'])->name('edit-task');
Route::post('/update-task', [HomeController::class, 'updateTask'])->name('update-task');
Route::post('/enable-task-time', [HomeController::class, 'enableTaskTime'])->name('enable-task-time');
Route::post('/disable-task-time', [HomeController::class, 'disableTaskTime'])->name('disable-task-time');
Route::get('/disable/task/{id}', [HomeController::class, 'disableTask'])->name('disable-task');
Route::get('/enable/task/{id}', [HomeController::class, 'enableTask'])->name('enable-task');
// Route::get('/delete/sub-amal/{id}', [HomeController::class, 'deleteSubAmaal'])->name('delete-sub-amaal');
Route::post('category-detail', [HomeController::class, 'categoryDetail'])->name('category-detail');
Route::post('submit/edit-sub-amaal', [HomeController::class, 'submitSubAmaal'])->name('submit-edit-sub-amaal');

// Community Module Routes
Route::get('/communities',[HomeController::class,'communities'])->name('communities');
Route::get('/disable-community/{id}',[HomeController::class,'disableCommunity'])->name('disable-community');
Route::get('/enable-community/{id}',[HomeController::class,'enableCommunity'])->name('enable-community');
Route::get('/approve-community/{id}',[HomeController::class,'approveCommunity'])->name('approve-community');
Route::get('/create/community',[HomeController::class,'createCommunity'])->name('create-community');
Route::post('/add-community',[HomeController::class,'addCommunity'])->name('add-community');
Route::post('/update-community',[HomeController::class,'updateCommunity'])->name('update-community');
Route::get('/community/{id}',[HomeController::class,'viewCommunity'])->name('view-community');
Route::post('/add-member',[HomeController::class,'addCommunityMember'])->name('add-community-member');
Route::get('/job/{id}',[HomeController::class,'viewCommunityJob'])->name('community-job');
Route::get('/edit-community/{id}',[HomeController::class,'editCommunity'])->name('edit-community');
Route::get('/delete-community/{id}',[HomeController::class,'deleteCommunity'])->name('delete-community');
Route::get('/jobs', [HomeController::class, 'jobs'])->name('jobs');
Route::get('/awarded-jobs', [HomeController::class, 'awardedJobs'])->name('awarded-jobs');
Route::get('/applied-jobs', [HomeController::class, 'appliedJobs'])->name('applied-jobs');
Route::post('/job/assign', [JobController::class,'assignJob'])->name('job.assign');

Route::get('/image', [HomeController::class, 'imageView'])->name('view.image');
Route::post('/image-upload',[HomeController::class, 'imageUpload'])->name('image.upload');

// Daily Quotes Routes
Route::get('/quotes', [HomeController::class, 'dailyQuotes'])->name('quotes');
Route::get('/add-quote', [HomeController::class, 'addQuote'])->name('add-quote');
Route::post('submit/quote',[HomeController::class, 'submitQuote'])->name('submit-daily-quote');
Route::get('edit-quote/{id}',[HomeController::class, 'editQuote'])->name('edit-quote');
Route::post('update/quote',[HomeController::class, 'updateQuote'])->name('update-quote');
Route::get('delete-quote/{id}',[HomeController::class, 'deleteQuote'])->name('delete-quote');

// Kurra Module API
Route::post('generate-kura',[HomeController::class, 'generateKura'])->name('generate-kura');
Route::post('add-kura',[HomeController::class, 'addKura'])->name('add-kura');


Route::get('task-tags',[TaskTagsController::class, 'index'])->name('tags');

//Service Providers Route

Route::get('service-providers',[ServiceProvideController::class, 'index'])->name('service-providers');
Route::post('service-provider/approved',[ServiceProvideController::class, 'approve'])->name('service-provider.approve');
