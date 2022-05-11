<?php

namespace App\Http\Controllers;

use Auth;
use JWTAuth;
use Validator;
use App\User;
use App\Models\Tenant;
use App\Models\RoomMaster;
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

    public function saveOrUpdateProperty (Request $request){
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
                    "date_of_completion"    => $formData['date_of_completion'] ? $formData['date_of_completion'] : null,
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
                $isExist = PropertyMaster::where('property_name', $formData['property_name'])->where('location', $formData['location'])->first();
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

    public function PropertyDLList(){
        $list = PropertyMaster::select('id', 'property_name', 'property_name_jp', 'location', 'gross_floor_area_sm')->orderby('property_name', 'ASC')->get();
        return response()->json(['success' => true, 'data' => $list, 'message' => 'Property List'], 200); 
    }

    public function saveOrUpdateRoom (Request $request) 
    {
        $formData = json_decode($request->data, true);
        try {
            if($formData['id']){
                $room = RoomMaster::where('id', $formData['id'])->update([
                    "property_id"       => $formData['property_id'],
                    "room_number"       => $formData['room_number'],
                    "room_number_jp"    => $formData['room_number_jp'],
                    "floor_no"          => $formData['floor_no'],
                    "room_area_sm"      => $formData['room_area_sm'],
                    "room_area_tsubo"   => $formData['room_area_tsubo'],
                    "uses"              => $formData['uses'],
                    "is_active"         => $formData['is_active']
                ]);

                return response()->json(['success' => true, 'data' => $room, 'message' => 'Room information updated successful!'], 200); 
            } else {
                $isExist = RoomMaster::where('room_number', $formData['room_number'])->where('property_id', $formData['property_id'])->first();
                if (empty($isExist)) {

                    $room = RoomMaster::create([
                        "property_id"       => $formData['property_id'],
                        "room_number"       => $formData['room_number'],
                        "room_number_jp"    => $formData['room_number_jp'],
                        "floor_no"          => $formData['floor_no'],
                        "room_area_sm"      => $formData['room_area_sm'],
                        "room_area_tsubo"   => $formData['room_area_tsubo'],
                        "uses"              => $formData['uses'],
                        "is_active"         => $formData['is_active']
                    ]);

                    return response()->json(['success' => true, 'data' => $room, 'message' => 'Room information added successful!'], 200); 
                }else{
                    return response()->json(['success' => false, 'data' => [], 'message' => 'Room information already exist!'], 200);
                }
            }

        } catch (Exception $e) {
            return response()->json(['success' => false, 'data' => [], 'message' => $e->getMessage()], 200);
        }
    }

    public function RoomList(){
        $list = RoomMaster::select('room_masters.*', 'property_masters.property_name', 'property_masters.property_name_jp')
        ->leftJoin('property_masters', 'property_masters.id', 'room_masters.property_id')
        ->orderby('room_masters.room_number', 'ASC')
        ->get();

        return response()->json(['success' => true, 'data' => $list, 'message' => 'Room List'], 200); 
    }

    public function getRoomListByID(Request $request)
    {
        $property_id = $request->property_id ? $request->property_id : 0;
        $list = RoomMaster::select('room_masters.*', 'property_masters.property_name', 'property_masters.property_name_jp')
        ->leftJoin('property_masters', 'property_masters.id', 'room_masters.property_id')
        ->when($property_id, function ($query, $property_id) {
            return $query->where('room_masters.property_id', $property_id);
        })
        ->orderby('room_masters.room_number', 'ASC')
        ->get();

        return response()->json(['success' => true, 'data' => $list, 'message' => 'Room List'], 200); 
    }

    public function saveOrUpdateTenant (Request $request) 
    {
        $formData = json_decode($request->data, true);
        try {
            if($formData['id']){
                $profile_image = '';
                if($request->file){
                    $profile_image  =  'profile_image_' .time().'.'.$request->file->getClientOriginalExtension();
                    $request->file->move('uploads/profile_image/', $profile_image);
                }

                $tenant = Tenant::where('id', $formData['id'])->update([
                    "company_name"          => $formData['company_name'],
                    "company_name_jp"       => $formData['company_name_jp'],
                    "email"                 => $formData['email'],
                    "address"               => $formData['address'],
                    "type_of_industry"      => $formData['type_of_industry'],
                    "number_of_employee"    => $formData['number_of_employee'],
                    "representative_name"   => $formData['representative_name'],
                    "establishment_date"    => $formData['establishment_date'] ? $formData['establishment_date'] : null,
                    "market_capitalization" => $formData['market_capitalization'],
                    "revenue"               => $formData['revenue'],
                    "is_active"             => $formData['is_active']
                ]);

                if($profile_image){
                    $tenant->update([
                        "profile_image" => url('/')."/uploads/profile_image/".$profile_image
                    ]);
                }

                return response()->json(['success' => true, 'data' => $tenant, 'message' => 'Tenant information updated successful!'], 200); 
            } else {
                $isExist = Tenant::where('email', $formData['email'])->first();
                if (empty($isExist)) {

                    $profile_image = '';
                    if($request->file){
                        $profile_image  =  'profile_image_' .time().'.'.$request->file->getClientOriginalExtension();
                        $request->file->move('uploads/profile_image/', $profile_image);
                    }
                    $tenant = Tenant::create([
                        "company_name"          => $formData['company_name'],
                        "company_name_jp"       => $formData['company_name_jp'],
                        "email"                 => $formData['email'],
                        "address"               => $formData['address'],
                        "type_of_industry"      => $formData['type_of_industry'],
                        "number_of_employee"    => $formData['number_of_employee'],
                        "representative_name"   => $formData['representative_name'],
                        "establishment_date"    => $formData['establishment_date'] ? $formData['establishment_date'] : null,
                        "market_capitalization" => $formData['market_capitalization'],
                        "revenue"               => $formData['revenue'],
                        "is_active"             => $formData['is_active']
                    ]);

                    if($profile_image){
                        $tenant->update([
                            "profile_image" => url('/')."/uploads/profile_image/".$profile_image
                        ]);
                    }

                    return response()->json(['success' => true, 'data' => $tenant, 'message' => 'Tenant information added successful!'], 200); 
                }else{
                    return response()->json(['success' => false, 'data' => [], 'message' => 'Tenant already exist!'], 200);
                }
            }

        } catch (Exception $e) {
            return response()->json(['success' => false, 'data' => [], 'message' => $e->getMessage()], 200);
        }
    }

    public function TenantList(){
        $list = Tenant::orderby('company_name', 'ASC')->get();

        return response()->json(['success' => true, 'data' => $list, 'message' => 'Tenant List'], 200); 
    }
    
    public function TenantDLList(){
        $list = Tenant::select('id', 'company_name', 'company_name_jp', 'email')->orderby('company_name', 'ASC')->get();
        return response()->json(['success' => true, 'data' => $list, 'message' => 'Property List'], 200); 
    }
}
