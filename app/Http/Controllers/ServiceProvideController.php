<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;

class ServiceProvideController extends Controller
{
    public function index()
    {
        $title = "Service Provider";
        $serviceProviders = ServiceProvider::with('community')->orderBy('created_at', 'desc')->get();
        return view('admin.service-provider.index', ["serviceProviders" => $serviceProviders, "title" => $title]);
    }

    public function approve(Request $request)
    {
       $serviceProvider = ServiceProvider::find($request->id);
      $approved = $serviceProvider->update([
           "is_approve" => 1
       ]);
      $community = Community::find($serviceProvider->community_id);
        $community->update([
            "is_serviceProvider" => 1
        ]);

      if($approved)
       return "Approved";
      if(!$approved)
          return "Not Approved";
    }
}
