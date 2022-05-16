<?php

namespace App\Http\Controllers;

use Auth;
use JWTAuth;
use Validator;
use App\User;
use App\Models\Tenant;
use App\Models\RoomMaster;
use App\Models\PropertyMaster;
use App\Models\ContractsMaster;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContractController extends Controller
{
    public function saveOrUpdateContract (Request $request){
        $formData = json_decode($request->data, true);
        try {
            if($formData['id']){
                
                $property = ContractsMaster::where('id', $formData['id'])->update([
                    "property_id"                   => $formData['property_id'],
                    "room_id"                       => $formData['room_id'],
                    "tenant_id"                     => $formData['tenant_id'],
                    "contract_type"                 => $formData['contract_type'],
                    "unit_rent_sm"                  => $formData['unit_rent_sm'],
                    "unit_rent_tsubo"               => $formData['unit_rent_tsubo'],
                    "monthly_rent"                  => $formData['monthly_rent'],
                    "date_of_rent_occurrence"       => $formData['date_of_rent_occurrence'] ? date("Y-m-d", strtotime($formData['date_of_rent_occurrence'])) : null,
                    "initial_month_rent"            => $formData['initial_month_rent'],
                    "final_month_rent"              => $formData['final_month_rent'],
                    "unit_common_fees_sm"           => $formData['unit_common_fees_sm'],
                    "unit_common_fees_tsubo"        => $formData['unit_common_fees_tsubo'],
                    "monthly_common_fees"           => $formData['monthly_common_fees'],
                    "date_of_common_fee_occurrence" => $formData['date_of_common_fee_occurrence'] ? date("Y-m-d", strtotime($formData['date_of_common_fee_occurrence'])) : null,
                    "initial_month_common_fee"      => $formData['initial_month_common_fee'],
                    "final_month_common_fee"        => $formData['final_month_common_fee'],
                    "unit_of_total_rent_sm"         => $formData['unit_of_total_rent_sm'],
                    "unit_of_total_rent_tsubo"      => $formData['unit_of_total_rent_tsubo'],
                    "total_rent"                    => $formData['total_rent'],
                    "total_initial_month_rent"      => $formData['total_initial_month_rent'],
                    "total_final_month_rent"        => $formData['total_final_month_rent'],
                    "number_of_deposit_months"      => $formData['number_of_deposit_months'],
                    "deposit"                       => $formData['deposit'],
                    "date_of_contract_start"        => $formData['date_of_contract_start'] ? date("Y-m-d", strtotime($formData['date_of_contract_start'])) : null,
                    "date_of_contract_end"          => $formData['date_of_contract_end'] ? date("Y-m-d", strtotime($formData['date_of_contract_end'])) : null,
                    "automatic_extention"           => $formData['automatic_extention'],
                    "month_of_automatic_extention"  => $formData['month_of_automatic_extention'],
                    "period_of_cancel_notice"       => $formData['period_of_cancel_notice'],
                    "date_of_received_application_form_for_moving_in"   => $formData['date_of_received_application_form_for_moving_in'] ? date("Y-m-d", strtotime($formData['date_of_received_application_form_for_moving_in'])) : null,
                    "date_of_contract_signed"       => $formData['date_of_contract_signed'] ? date("Y-m-d", strtotime($formData['date_of_contract_signed'])) : null,
                    "document_delivery_date"        => $formData['document_delivery_date'] ? date("Y-m-d", strtotime($formData['document_delivery_date'])) : null,
                    "date_of_noticing_contract_termination" => $formData['date_of_noticing_contract_termination'] ? date("Y-m-d", strtotime($formData['date_of_noticing_contract_termination'])) : null,
                    "cancellation_date"             => $formData['cancellation_date'] ? date("Y-m-d", strtotime($formData['cancellation_date'])) : null,
                    "remarks_on_furniture_and_equipment"    => $formData['remarks_on_furniture_and_equipment'],
                    "other_remarks"                 => $formData['other_remarks'],
                    "is_active"                     => $formData['is_active']
                ]);

                return response()->json(['success' => true, 'data' => $property, 'message' => 'Contract information updated successful!'], 200); 
            } else {
                $isExist = ContractsMaster::where('property_id', $formData['property_id'])
                    ->where('room_id', $formData['room_id'])
                    ->where('tenant_id', $formData['tenant_id'])
                    ->where('date_of_contract_start', date("Y-m-d", strtotime($formData['date_of_contract_start'])))
                    ->first();
                if (empty($isExist)) {

                    $property = ContractsMaster::create([
                        "property_id"                   => $formData['property_id'],
                        "room_id"                       => $formData['room_id'],
                        "tenant_id"                     => $formData['tenant_id'],
                        "contract_type"                 => $formData['contract_type'],
                        "unit_rent_sm"                  => $formData['unit_rent_sm'],
                        "unit_rent_tsubo"               => $formData['unit_rent_tsubo'],
                        "monthly_rent"                  => $formData['monthly_rent'],
                        "date_of_rent_occurrence"       => $formData['date_of_rent_occurrence'] ? date("Y-m-d", strtotime($formData['date_of_rent_occurrence'])) : null,
                        "initial_month_rent"            => $formData['initial_month_rent'],
                        "final_month_rent"              => $formData['final_month_rent'],
                        "unit_common_fees_sm"           => $formData['unit_common_fees_sm'],
                        "unit_common_fees_tsubo"        => $formData['unit_common_fees_tsubo'],
                        "monthly_common_fees"           => $formData['monthly_common_fees'],
                        "date_of_common_fee_occurrence" => $formData['date_of_common_fee_occurrence'] ? date("Y-m-d", strtotime($formData['date_of_common_fee_occurrence'])) : null,
                        "initial_month_common_fee"      => $formData['initial_month_common_fee'],
                        "final_month_common_fee"        => $formData['final_month_common_fee'],
                        "unit_of_total_rent_sm"         => $formData['unit_of_total_rent_sm'],
                        "unit_of_total_rent_tsubo"      => $formData['unit_of_total_rent_tsubo'],
                        "total_rent"                    => $formData['total_rent'],
                        "total_initial_month_rent"      => $formData['total_initial_month_rent'],
                        "total_final_month_rent"        => $formData['total_final_month_rent'],
                        "number_of_deposit_months"      => $formData['number_of_deposit_months'],
                        "deposit"                       => $formData['deposit'],
                        "date_of_contract_start"        => $formData['date_of_contract_start'] ? date("Y-m-d", strtotime($formData['date_of_contract_start'])) : null,
                        "date_of_contract_end"          => $formData['date_of_contract_end'] ? date("Y-m-d", strtotime($formData['date_of_contract_end'])) : null,
                        "automatic_extention"           => $formData['automatic_extention'],
                        "month_of_automatic_extention"  => $formData['month_of_automatic_extention'],
                        "period_of_cancel_notice"       => $formData['period_of_cancel_notice'],
                        "date_of_received_application_form_for_moving_in"   => $formData['date_of_received_application_form_for_moving_in'] ? date("Y-m-d", strtotime($formData['date_of_received_application_form_for_moving_in'])) : null,
                        "date_of_contract_signed"       => $formData['date_of_contract_signed'] ? date("Y-m-d", strtotime($formData['date_of_contract_signed'])) : null,
                        "document_delivery_date"        => $formData['document_delivery_date'] ? date("Y-m-d", strtotime($formData['document_delivery_date'])) : null,
                        "date_of_noticing_contract_termination" => $formData['date_of_noticing_contract_termination'] ? date("Y-m-d", strtotime($formData['date_of_noticing_contract_termination'])) : null,
                        "cancellation_date"             => $formData['cancellation_date'] ? date("Y-m-d", strtotime($formData['cancellation_date'])) : null,
                        "remarks_on_furniture_and_equipment"    => $formData['remarks_on_furniture_and_equipment'],
                        "other_remarks"                 => $formData['other_remarks'],
                        "is_active"                     => $formData['is_active']
                    ]);

                    return response()->json(['success' => true, 'data' => $property, 'message' => 'Contract information added successful!'], 200); 
                }else{
                    return response()->json(['success' => false, 'data' => [], 'message' => 'Contract information already exist!'], 200);
                }
            }

        } catch (Exception $e) {
            return response()->json(['success' => false, 'data' => [], 'message' => $e->getMessage()], 200);
        }
    }

    public function ContractList(Request $request)
    {
        $property_id = $request->property_id ? $request->property_id : 0;
        $tenant_id = $request->tenant_id ? $request->tenant_id : 0;

        $list = ContractsMaster::select('contracts_masters.*', 
            'property_masters.property_name', 
            'property_masters.property_name_jp',
            'room_masters.room_number',
            'tenants.company_name',
            'tenants.company_name_jp',
            'tenants.email as tenant_email',
            'tenants.address as tenant_address',
            )
        ->when($property_id, function ($query, $property_id) {
            return $query->where('contracts_masters.property_id', $property_id);
        })
        ->when($tenant_id, function ($query, $tenant_id) {
            return $query->where('contracts_masters.tenant_id', $tenant_id);
        })
        ->leftJoin('property_masters', 'property_masters.id', 'contracts_masters.property_id')
        ->leftJoin('room_masters', 'room_masters.id', 'contracts_masters.room_id')
        ->leftJoin('tenants', 'tenants.id', 'contracts_masters.tenant_id')
        ->orderby('contracts_masters.created_at', 'ASC')
        ->get();

        return response()->json(['success' => true, 'data' => $list, 'message' => 'Contract List'], 200); 
    }

    public function ContractDetails(Request $request)
    {
        $data = ContractsMaster::select('contracts_masters.*', 
            'property_masters.property_name', 
            'property_masters.property_name_jp',
            'room_masters.room_number',
            'tenants.company_name',
            'tenants.company_name_jp',
            'tenants.email as tenant_email',
            'tenants.address as tenant_address',
        )
        ->leftJoin('property_masters', 'property_masters.id', 'contracts_masters.property_id')
        ->leftJoin('room_masters', 'room_masters.id', 'contracts_masters.room_id')
        ->leftJoin('tenants', 'tenants.id', 'contracts_masters.tenant_id')
        ->where('contracts_masters.id', $request->contract_id)
        ->first();

        return response()->json(['success' => true, 'data' => $data, 'message' => 'Contract Details'], 200); 
    }

    public function filterRentRollContractList(Request $request)
    {
        $property_id = $request->property_id ? $request->property_id : 0;
        $filter_date = $request->filter_date ? $request->filter_date : 0;

        $list = ContractsMaster::select('contracts_masters.*', 
            'property_masters.property_name', 
            'property_masters.property_name_jp',
            'room_masters.room_number',
            'room_masters.floor_no',
            'room_masters.uses',
            'room_masters.room_area_sm',
            'room_masters.room_area_tsubo',
            'tenants.company_name',
            'tenants.company_name_jp',
            'tenants.email as tenant_email',
            'tenants.address as tenant_address',
            )
        ->when($property_id, function ($query, $property_id) {
            return $query->where('contracts_masters.property_id', $property_id);
        })
        ->when($filter_date, function ($query, $filter_date) {
            return $query->whereDate('contracts_masters.date_of_contract_start', '<=', date("Y-m-d", strtotime($filter_date)));
        })
        ->when($filter_date, function ($query, $filter_date) {
            return $query->whereDate('contracts_masters.date_of_contract_end', '>=', date("Y-m-d", strtotime($filter_date)));
        })
        ->leftJoin('property_masters', 'property_masters.id', 'contracts_masters.property_id')
        ->leftJoin('room_masters', 'room_masters.id', 'contracts_masters.room_id')
        ->leftJoin('tenants', 'tenants.id', 'contracts_masters.tenant_id')
        ->orderby('contracts_masters.created_at', 'ASC')
        ->get();

        return response()->json(['success' => true, 'data' => $list, 'message' => 'Rent Roll List'], 200); 
    }

    public function filterStackingContractList(Request $request)
    {
        $property_id = $request->property_id ? $request->property_id : 0;
        $filter_date = $request->filter_date ? $request->filter_date : 0;
        
        $room_list =  RoomMaster::select('room_masters.*', 'property_masters.property_name', 'property_masters.property_name_jp')
            ->leftJoin('property_masters', 'property_masters.id', 'room_masters.property_id')
            ->where('room_masters.property_id', $property_id)
            ->orderby('room_masters.room_number', 'ASC')
            ->get();
        
        $stacking_list = [];
        foreach ($room_list as $room) 
        {
            $room_id = $room->id;
            $is_contract_exist = ContractsMaster::select('contracts_masters.*',
                'tenants.company_name',
                'tenants.company_name_jp',
                'tenants.email as tenant_email',
                'tenants.address as tenant_address',
            )
            ->when($room_id, function ($query, $room_id) {
                return $query->where('contracts_masters.room_id', $room_id);
            })
            ->when($filter_date, function ($query, $filter_date) {
                return $query->whereDate('contracts_masters.date_of_contract_start', '<=', date("Y-m-d", strtotime($filter_date)));
            })
            ->when($filter_date, function ($query, $filter_date) {
                return $query->whereDate('contracts_masters.date_of_contract_end', '>=', date("Y-m-d", strtotime($filter_date)));
            })
            ->leftJoin('tenants', 'tenants.id', 'contracts_masters.tenant_id')
            ->first();

            if(!empty($is_contract_exist)){
                array_push($stacking_list, [
                    'room_number' => $room->room_number,
                    'floor_no' => $room->floor_no,
                    'room_area_sm' => $room->room_area_sm,
                    'room_area_tsubo' => $room->room_area_tsubo,
                    'uses' => $room->uses,
                    'tenant_name' => $is_contract_exist->company_name,
                    'tenant_email' => $is_contract_exist->tenant_email,
                    'tenant_address' => $is_contract_exist->tenant_address,
                    'date_of_contract_start' => $is_contract_exist->date_of_contract_start,
                    'date_of_contract_end' => $is_contract_exist->date_of_contract_end,
                    'is_booked' => true,
                ]);
            }else{
                array_push($stacking_list, [
                    'room_number' => $room->room_number,
                    'floor_no' => $room->floor_no,
                    'room_area_sm' => $room->room_area_sm,
                    'room_area_tsubo' => $room->room_area_tsubo,
                    'uses' => $room->uses,
                    'tenant_name' => null,
                    'tenant_email' => null,
                    'tenant_address' => null,
                    'date_of_contract_start' => null,
                    'date_of_contract_end' => null,
                    'is_booked' => false,
                ]); 
            }
        }

        $data = $this->group_by("floor_no", $stacking_list);
        $response_data = [];
        foreach ($data as $item) {
            array_push($response_data,
                [
                    'floor_no' => $item[0]['floor_no'],
                    'rooms' => $item
                ]);
        }

        return response()->json(['success' => true, 'data' => $response_data, 'message' => 'Stacking List'], 200); 
    }

    public function group_by($key, $data) {
        $result = [];
    
        foreach($data as $val) {
            if(array_key_exists($key, $val)){
                $result[$val[$key]][] = $val;
            }else{
                $result[""][] = $val;
            }
        }
        return $result;
    }

}
