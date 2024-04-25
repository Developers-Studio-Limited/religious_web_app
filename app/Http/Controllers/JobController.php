<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function assignJob(Request $request)
    {
       $job = Job::find($request->JobId);
       $updated = $job->update([
           "community_id" => $request->communityId,
           "status" => 'approved'
       ]);
       if($updated)
           return "Updated";
       if(!$updated)
           return "Failed";
    }
}
