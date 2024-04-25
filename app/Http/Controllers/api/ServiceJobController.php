<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;

use App\Models\Job;
use App\Models\ServiceJob;
use App\Models\UserTransaction;
use App\Models\VUserTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceJobController extends Controller
{
    public function create(Request $request)
    {
        if(!is_null($request->amount))
        {
        $walletAmount = VUserTransaction::select('*')->where('user_id', auth()->user()->id)->orderBy('category', 'ASC')->get();

        if($walletAmount == "" or $walletAmount == null)
            $walletAmount = 0;

        if ($walletAmount[0]['balance'] < $request->amount) {
            return sendError('Low wallet amount. Please topup your wallet.',422);
         }
        }
            $validated = Validator::make($request->all(), [
//
                "title" => "string|required",
                "category_id" => "integer|required",
                "quantity" => "integer|required",
                "description" => "required",
                "due_date" => "required",
            ]);

        if($validated->fails())
        {
            return sendError('Bad Request',400,$validated->errors());
        }

       $created = Job::create([
            "title" => $request->title,
            "category_id" => $request->category_id,
            "quantity" => $request->quantity,
            "type"  => 5,//5 for service providers
            "hadiya"  => $request->amount,
            "description"  => $request->description,
            "status"  => 'pending',
            "due_date"  => $request->due_date,
            "user_id"  => $request->user()->id,
            "payment_type"  => $request->payment_type,
        ]);

        if($created)
        {
            if($request->payment_type == 'paid')
            {
               $userTransaction = UserTransaction::create([
                    "user_id" => $request->user()->id,
                    "amount" => $request->amount,
                    "category" => 5,//5 for service provider
                    "is_success" => 1,
//                    "payment_id" => ,
                    "transaction_type" =>1,//1 for credit
//                    "ref_id" => ,
                ]);
                $userTransaction = UserTransaction::create([
                    "user_id" => $request->user()->id,
                    "amount" => $request->amount,
                    "category" => 0, //0 for wallet
                    "is_success" => 1,
//                    "payment_id" => ,
                    "transaction_type" =>2,//2 for debit
//                    "ref_id" => ,
                ]);
            }
            return sendSuccess('Job created successfully.', $created);
        }
        if(!$created)
        {
            return sendError('Error in creating job.Please try again.',500);
        }
    }

    public function index()
    {
       $serviceJobs = Job::orderBy('created_at', 'desc')->where('type', 5)->simplePaginate(10); //5 for services
       if(!is_null($serviceJobs))
       {
           return sendSuccess('All Service Jobs',$serviceJobs);
       }
    }
    public function userJobs($id)
    {
        $userJobs = Job::orderBy('created_at', 'desc')->where('user_id', $id)->where('type', 5)->paginate(10);
        if(!is_null($userJobs))
        {
            return sendSuccess('My Jobs',$userJobs);
        }
    }

    public function delete($id)
    {
       $job = Job::find($id);
       if(is_null($job))
       {
           return sendError('No Job exist againt this id',404);
       }
      $deleted = $job->delete();
      if($deleted)
      {
          return sendSuccess('Job Deleted successfully!',[]);
      }
      if(!$deleted)
      {
            return sendError('Error in Deleted job.',500);
      }
    }

    public function edit($id)
    {
       $data['job'] = Job::find($id);

       if($data['job']->community_id != null)
       {
           return sendError('This job can not be edited. It is assigned.',500);
       }
        if(is_null($data['job']))
        {
            return sendError('No Job exist againt this id',404);
        }
        $data['community'] = $data['job']->community;
        return sendSuccess('Job',$data);

    }

    public function getDetail($id)
    {
        $serviceProvider = null;
        $userRole = "Undefine";
        $memberShips = null;

       $job = Job::with('subCategory', 'userJob','community.srevice_providers.memberships')->find($id);
        if(is_null($job))
        {
            return sendError('No Job exist againt this id',404);
        }
       $community = $job->community;
        if(!is_null($community))
         $serviceProvider = $community->srevice_providers[0];

        if(!is_null($serviceProvider))
            $memberShips = $serviceProvider[0]->memberships;

        if(!is_null($memberShips) and $memberShips != "") {
            foreach ($memberShips as $memberShip) {
                if ($memberShip->user_id == auth()->user()->id) {
                    $userRole = $memberShip->name;
                }
            }
        }
       $data['job'] = $job;
       $data['currentUserRole'] = $userRole;

        return sendSuccess('Job',$data);
    }

    public function update($id, Request $request)
    {
        $validated = Validator::make($request->all(), [
//
            "title" => "string|required",
            "category_id" => "integer|required",
            "quantity" => "integer|required",
            "description" => "required",
            "due_date" => "required",
        ]);

        if($validated->fails())
        {
            return sendError('Bad Request',400,$validated->errors());
        }

        $job = Job::find($id);
        if(is_null($job))
        {
            return sendError('No job found.',404);
        }
         $updated = $job->update([
            "title" => $request->title,
            "category_id" => $request->category_id,
            "quantity" => $request->quantity,
            "description"  => $request->description,
            "due_date"  => $request->due_date,
        ]);

        if($updated)
        {
            return sendSuccess('Job updated successfully.', $updated);
        }
        if(!$updated)
        {
            return sendError('Error in updating job.Please try again.',500);
        }
    }

    public function completeJob($id, Request $request)
    {
       $job = Job::find($id);
       if(is_null($job))
           return sendError('No Job exist',404);

       if($job->status != "complete")
       {
           $job->update([
               "status" => "complete"
           ]);
       }
       if($job->amount == 'paid') {
           $userTransaction = UserTransaction::create([
               "user_id" => $request->user()->id,
               "amount" => $job->amount,
               "category" => 5,//5 for service provider
               "is_success" => 1,
//                    "payment_id" => ,
               "transaction_type" => 2,//1 for credit //2 for debit
//                    "ref_id" => ,
           ]);
           $userTransaction = UserTransaction::create([
               "user_id" => $request->user()->id,
               "amount" => $job->amount,
               "category" => 5,//5 for service provider
               "is_success" => 1,
//                    "payment_id" => ,
               "transaction_type" => 2,//1 for credit //2 for debit
//                    "ref_id" => ,
           ]);
       }

    }
}
