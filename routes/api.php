<?php

use App\Http\Controllers\api\DisputeController;
use App\Http\Controllers\api\ServiceJobController;
use App\Http\Controllers\api\ServiceProviderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => ['checkAppKey','midheaders']], function(){
	// Route::get('/home', [UserController::class, 'index'])->name('home');
	Route::post('login', [UserController::class,'login']);
	Route::post('register', [UserController::class,'register']);
	Route::get('verify-phone', [UserController::class,'verifyPhone']);
	Route::post('social-media/register',[UserController::class,'socialMediaRegister']);
	Route::post('forget-password',[UserController::class,'forgetPassword']);
	Route::get('taqleed', [UserController::class,'taqleed']);
	Route::group(['middleware' => 'auth:api'], function(){
	Route::post('details', [UserController::class,'details']);

});
Route::group(['middleware' => 'checkSession'], function(){
	Route::get('profile', [UserController::class,'userProfile']);
	Route::post('edit-profile', [UserController::class,'editProfile']);
	Route::post('complete-profile', [UserController::class,'completeProfile']);
	Route::post('update-image', [UserController::class,'updateImage']);
	Route::post('banners', [UserController::class,'banners']);
	Route::post('categories', [UserController::class,'categories']);
	Route::get('sub-categories/{id}', [UserController::class,'subCategories']);
	Route::get('subcategories', [UserController::class,'allSubCategories']);
	Route::post('add-task', [UserController::class,'addTask']);
	Route::post('user-tasks', [UserController::class,'userTasks']);
	Route::post('update/user-tasks', [UserController::class,'updateUserTask']);
    Route::post('user-address/add', [UserController::class,'addUserAddress']);
    Route::post('user-address/get', [UserController::class,'getUserAddresses']);


	// Community Jobs API's
//	Route::post('create/community-job', [UserController::class,'createCommunityJob']);
	Route::get('user/community-jobs', [UserController::class,'userCommunityJobs']);
	Route::post('add-sahadat', [UserController::class,'addSahadat']);
	Route::get('sahadat-requests', [UserController::class,'sahadatRequests']);
	Route::get('user/sahadat-requests', [UserController::class,'userSahadats']);
	Route::post('approve-sahadat', [UserController::class,'approveSahadat']);
	Route::post('reject-sahadat', [UserController::class,'rejectSahadat']);
	Route::post('block-user', [UserController::class,'blockUser']);
	Route::post('unblock-user', [UserController::class,'unblockUser']);

    // Services Jobs API's
    Route::post('services/job/create', [ServiceJobController::class,'create']);
    Route::post('services/job/{id}/delete', [ServiceJobController::class,'delete']);
    Route::get('services/job/{id}/edit', [ServiceJobController::class,'edit']);
    Route::post('services/job/{id}/update', [ServiceJobController::class,'update']);
    Route::get('services/job/{id}/detail', [ServiceJobController::class,'getDetail']);
    Route::get('services/jobs', [ServiceJobController::class,'index']);
    Route::get('services/user-jobs/{id}', [ServiceJobController::class,'userJobs']);
    Route::get('services/job/{id}/complete', [ServiceJobController::class,'completeJob']);


    //Community Module API's
	Route::get('user-marhooms', [UserController::class,'userMarhooms']);
	Route::get('user-marhoom/{id}', [UserController::class,'userMarhoom']);
	Route::post('add-marhoom', [UserController::class,'addMarhoom']);
	Route::post('edit-marhoom', [UserController::class,'editMarhoom']);
	Route::post('delete-marhoom', [UserController::class,'deleteMarhoom']);
	Route::get('communities', [UserController::class,'allCommunities']);//ok
	Route::get('community/{id}/members/get', [UserController::class,'getCommunityMembers']);//ok
	Route::get('community/{communityId}/member/{userId}/leave', [UserController::class,'leaveCommunity']);//ok
	Route::post('add/community-request', [UserController::class,'addCommunityRequest']);//ok
	Route::get('user/community-requests', [UserController::class,'userCommunityRequests']);//ok
	Route::get('community/join-requests', [UserController::class,'joinCommunityRequest']);//ok
	Route::post('accept/community-request', [UserController::class,'acceptCommunityRequest']);//ok
	Route::post('reject/community-request', [UserController::class,'rejectCommunityRequest']);//ok
	Route::get('community-jobs/{id}', [UserController::class,'communityTasks']); //ok
	Route::get('community/job-detail/{id}', [UserController::class,'communityJobDetail']);
	Route::post('update/community-job', [UserController::class,'updateCommunityJob']);
	Route::post('delete/community-job', [UserController::class,'deleteCommunityJob']);
	Route::post('log-task', [UserController::class,'logTask']);
	Route::post('update/log-task', [UserController::class,'updatelogTask']);
	Route::get('user/task-details/{id}', [UserController::class,'userTaskDetails']);
	Route::get('user/log-tasks', [UserController::class,'userlogTasks']);
	Route::get('community/task-users/{id}', [UserController::class,'communitytaskUsers']);

	// User Create Community
	Route::post('create-community', [UserController::class,'createCommunity']);
	Route::get('user-communities', [UserController::class,'userCommunities']);
	// Messages API
	Route::post('save/community-message', [UserController::class,'saveCommunityMessage']);
	Route::get('community-chat/{id}', [UserController::class,'getCommunityChat']);
	Route::get('user/community-chat/count/{id}', [UserController::class,'getUserCommunityChatCount']);
	Route::post('read/community-messages', [UserController::class,'readCommunityMessage']);
	Route::get('notifications', [UserController::class,'notifications']);
	Route::post('read-notifications', [UserController::class,'readNotification']);
	// Statistics
	Route::get('user-tasks/stats/{id}/{month}/{year}', [UserController::class,'getUserStats']);

	/////////////////////////// V2 Routes //////////////////////////////
	Route::group(["prefix" => "v2"], function(){
		Route::post('create/community-job', [UserController::class,'newCommunityJob']);
		Route::post('update/log-task', [UserController::class,'updateCommunitylogTask']);
	});
	// Payment Routes
	Route::post('add-card', [UserController::class,'addCard']);
	Route::post('payment-success', [UserController::class,'paymentSuccess']);
	Route::get('user-transactions', [UserController::class,'userTransactions']);
	Route::post('add-transaction', [UserController::class,'addTransaction']);
	Route::get('all-transactions', [UserController::class,'allTransactions']);

	Route::post('add-kura-marhoom', [UserController::class,'addKuraMarhoom']);
	Route::get('unread-notification-count', [UserController::class,'unreadNotificationCount']);

    //service providers
    Route::post('service-provider/add', [ServiceProviderController::class,'add']);

    //Disputes
    Route::post('service/job/dispute/create', [DisputeController::class,'create']);
    Route::post('service/job/{id}/dispute/get', [DisputeController::class,'getDispute']);

});

});
