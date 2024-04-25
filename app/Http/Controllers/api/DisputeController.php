<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DisputeController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id' => 'required|integer',
            'user_id' => 'required|integer',
            'message' => 'required|string',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return sendError($errors, 400);
        }

    $created = Dispute::create([
        "job_id" => $request->job_id,
        "user_id" => $request->user_id,
        "message" => $request->message,
    ]);
    if($created)
    {
        $job = Job::find($request->job_id);
        $jobUpdated = $job->update([
            "status" => "dispute"
        ]);
       if($jobUpdated)
        return sendSuccess('Message submitted successfully.',$created);
    }
    if(!$created)
        return sendError('Error! Failed to add message.',500);
    }

    public function getDispute($jobId)
    {
       $job = Job::find($jobId);
       if(is_null($job))
       {
           return sendError('No Job exist.',404);
       }
        $disputes = $job->disputes()->get();
        if(is_null($disputes))
        {
            return sendError('No Dispute exist.',404);
        }
        return sendSuccess('disputes',$disputes);
    }

}
