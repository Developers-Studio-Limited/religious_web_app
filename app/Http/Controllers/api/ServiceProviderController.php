<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ServiceProviderController extends Controller
{
    public function add(Request $request)
    {
        if ($request->type == 'Independant') {

            $validated = Validator::make($request->all(), [
                "account_name" => "required",
                "account_title" => "required",
                "IBAN" => "required",
            ]);
        }

        if ($request->type == 'NGO' or $request->type == 'Non-Profit') {
            $validated = Validator::make($request->all(), [

                "account_name" => "required",
                "account_title" => "required",
                "IBAN" => "required",
//                "registration_document" => "required|mimes:jpeg,jpg,png",
                "member_ships"      => "required",
            ]);
        }

        if ($validated->fails()) {
            return response()->json([
                "status" => "false",
                "errorMessage" => $validated->errors()
            ], 400);
        }

        if (!is_null($request->registration_document)) {

//            $image_64 = base64_encode(file_get_contents($request->file('registration_document')));
            $file = base64_decode($request->registration_document);
            $file_name = time().getFileExtensionForBase64($file);
            Storage::disk('public')->put('/ServiceProvider/' . $file_name, $file);
            $documentImage = "/ServiceProvider/".$file_name;
        }

        $created = ServiceProvider::create([
            "account_name" => $request->account_name,
            "account_title" => $request->account_title,
            "IBAN" => $request->IBAN,
            "registration_document" => is_null($request->registration_document) ? "" : $documentImage,
            "type" => $request->type,
            "community_id" => $request->community_id,
        ]);

        if ($created) {
            if ($request->member_ships != "") {

                foreach ($request->member_ships as $member) {
                    Membership::create([
                        "user_id" => $member['user_id'],
                        "name" => $member['name'],
                        "service_provider_id" => $created->id,
                    ]);
                }
            }
            return sendSuccess('Information Saved Successfully! Your request is pending approval by an admin.',$created);
        }
        if(!$created)
            return sendError('Server Error! Some thing went wrong.',500);

    }
}
