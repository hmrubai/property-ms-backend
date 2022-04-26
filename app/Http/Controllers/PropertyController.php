<?php

namespace App\Http\Controllers;

use Auth;
use JWTAuth;
use Validator;
use App\User;
use App\Models\PropertyMaster;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PropertyController extends Controller
{
    public function home(){
        return response()->json(['success' => true, 'data' => [], 'message' => 'Welcome to Property Management System! Unauthorised access!'], 200); 
    }

    public function adminIUserList(){
        $users = User::where('user_type', 'Administrator')->get();
        return response()->json(['success' => true, 'data' => $users, 'message' => 'Moderator List'], 200); 
    }

    public function saveOrUpdateProperty (Request $request) 
    {
        $formData = json_decode($request->data, true);
        try {
            if($formData['id']){
                $property_image = '';
                if($request->file){
                    $property_image  =  'property_image_' .time().'.'.$request->file->getClientOriginalExtension();
                    $request->file->move('uploads/property_image/', $property_image);
                }

                $property = PropertyMaster::where('id', $formData['id'])->update([
                    "property_name"         => $formData['property_name'],
                    "property_name_jp"      => $formData['property_name_jp'],
                    "location"              => $formData['location'],
                    "gross_floor_area_sm"   => $formData['gross_floor_area_sm'],
                    "gross_floor_area_tsubo"=> $formData['gross_floor_area_tsubo'],
                    "private_area_sm"       => $formData['private_area_sm'],
                    "private_area_tsubo"    => $formData['private_area_tsubo'],
                    "building_structure"    => $formData['building_structure'],
                    "story"                 => $formData['story'],
                    "underground_story"     => $formData['underground_story'],
                    "date_of_completion"    => $formData['date_of_completion'] ? date("Y-m-d H:i:s", strtotime($formData['date_of_completion'])) : null,
                    "owner_on_the_registry" => $formData['owner_on_the_registry'],
                    "owner_address"         => $formData['owner_address'],
                    "is_active"             => $formData['is_active']
                ]);

                if($property_image){
                    $property->update([
                        "property_image" => url('/')."/uploads/property_image/".$property_image
                    ]);
                }
                return response()->json(['success' => true, 'data' => $property, 'message' => 'Property information updated successful!'], 200); 
            } else {
                $isExist = PropertyMaster::where('property_name', $request->property_name)->where('location', $request->location)->first();
                if (empty($isExist)) {

                    $property_image = '';
                    if($request->file){
                        $property_image  =  'property_image_' .time().'.'.$request->file->getClientOriginalExtension();
                        $request->file->move('uploads/property_image/', $property_image);
                    }

                    $property = PropertyMaster::create([
                        "property_name"         => $formData['property_name'],
                        "property_name_jp"      => $formData['property_name_jp'],
                        "location"              => $formData['location'],
                        "gross_floor_area_sm"   => $formData['gross_floor_area_sm'],
                        "gross_floor_area_tsubo"=> $formData['gross_floor_area_tsubo'],
                        "private_area_sm"       => $formData['private_area_sm'],
                        "private_area_tsubo"    => $formData['private_area_tsubo'],
                        "building_structure"    => $formData['building_structure'],
                        "story"                 => $formData['story'],
                        "underground_story"     => $formData['underground_story'],
                        "date_of_completion"    => $formData['date_of_completion'],
                        "owner_on_the_registry" => $formData['owner_on_the_registry'],
                        "owner_address"         => $formData['owner_address'],
                        "is_active"             => $formData['is_active']
                    ]);

                    if($property_image){
                        $property->update([
                            "property_image" => url('/')."/uploads/property_image/".$property_image
                        ]);
                    }

                    return response()->json(['success' => true, 'data' => $property, 'message' => 'Property information added successful!'], 200); 
                }else{
                    return response()->json(['success' => false, 'data' => [], 'message' => 'Property information already exist!'], 200);
                }
            }

        } catch (Exception $e) {
            return response()->json(['success' => false, 'data' => [], 'message' => $e->getMessage()], 200);
        }
    }

    public function PropertyList(){
        $list = PropertyMaster::orderby('property_name', 'ASC')->get();
        return response()->json(['success' => true, 'data' => $list, 'message' => 'Property List'], 200); 
    }
}
