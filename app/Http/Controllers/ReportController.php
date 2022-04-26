<?php

namespace App\Http\Controllers;

use Auth;
use JWTAuth;
use Validator;
use App\User;
use App\Models\ConditionReport;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    public function generateReportNo(){
        $existing_report_no = ConditionReport::count();

        $new_sequence = $existing_report_no+1;
        $new_no = str_pad($new_sequence, 3, '0', STR_PAD_LEFT);
        $new_report_no = "CR-" . date('y') . "-" . $new_no;
        $new_reference_no = date('d') . "-" . date('m') . "-" . $new_no;
        $data = ['reference_no' => $new_reference_no, 'report_no' => $new_report_no];

        return response()->json(['success' => true, 'data' => $data, 'message' => 'Successful!']); 
    }

    public function ReporCount(Request $request)
    {
        $all_reports = ConditionReport::all();

        $total = $all_reports->count();
        $pending = $all_reports->where('stage_no', 1)->count();
        $completed = $all_reports->where('stage_no', 2)->count();
        $total_users = User::all()->count();

        $reports = [
            "total_cr" => $total,
            "pending" => $pending,
            "completed" => $completed,
            "total_users" => $total_users,
        ];

        return response()->json(['success' => true, 'data' => $reports, 'message' => 'Successful!']); 
    }

    public function conditionReporList(Request $request)
    {
        $status = 0;
        if($request->status != "null"){
            $status = $request->status ? $request->status : 0;
        }

        $reports = ConditionReport::when($status, function ($query, $status) {
            return $query->where('stage_no', $status);
        })
        ->orderby('id', 'desc')->get();
        return response()->json(['success' => true, 'data' => $reports, 'message' => 'Successful!']); 
    }

    public function pendingConditionReporList(Request $request)
    {
        $status = 1;

        $reports = ConditionReport::when($status, function ($query, $status) {
            return $query->where('stage_no', $status);
        })
        ->orderby('id', 'desc')->get();
        return response()->json(['success' => true, 'data' => $reports, 'message' => 'Successful!']); 
    }

    public function completedConditionReporList(Request $request)
    {
        $status = 2;

        $reports = ConditionReport::when($status, function ($query, $status) {
            return $query->where('stage_no', $status);
        })
        ->orderby('id', 'desc')->get();
        return response()->json(['success' => true, 'data' => $reports, 'message' => 'Successful!']); 
    }

    public function saveReport(Request $request)
    {
        $formData = json_decode($request->data, true);

        // $signature_thumbnail = '';
        // if($request->file){
        //     $signature_thumbnail  =  'signature_' .time().'.'.$request->file->getClientOriginalExtension();
        //     $request->file->move('uploads/signatures/', $signature_thumbnail);
        // }

        $user = User::where('id', $formData['signature_second_user_id'])->first();

        $user_signature = null;
        if(!empty($user)){
            if($user->signature){
                $user_signature = $user->signature;
            }
        }
        
        $add_new_cr = ConditionReport::create([
            "reference_no" => $formData['reference_no'],
            "report_no" => $formData['report_no'],
            "associated_ncr" => $formData['associated_ncr'],
            "assigned_to" => $formData['assigned_to'],
            "responsible_dept" => $formData['responsible_dept'],
            "target_close_date" => $formData['target_close_date'] ? date('Y-m-d', strtotime($formData['target_close_date'])) : NULL,
            "validated_by" => $formData['validated_by'],
            "validated_date" => $formData['validated_date'] ? date('Y-m-d', strtotime($formData['validated_date'])) : NULL,
            "location" => $formData['location'],
            "supplier_name" => $formData['supplier_name'],
            "po" => $formData['po'],
            "client_project" => $formData['client_project'],
            "so" => $formData['so'],
            "job_asm" => $formData['job_asm'],
            "system_description" => $formData['system_description'],
            "pn" => $formData['pn'],
            "dwg" => $formData['dwg'],
            "qty" => $formData['qty'],
            "is_hold_tag_applied" => $formData['is_hold_tag_applied'],
            "no_hold_tag_applied" => $formData['no_hold_tag_applied'],
            "signature_first_user_id" => $formData['signature_second_user_id'],
            "signature_first_person" => $user_signature,
            "signatory_title_first_person" => $formData['signatory_title_second_person'],
            "creation_date" =>  $formData['second_signature_date'] ? date('Y-m-d', strtotime($formData['second_signature_date'])) : NULL,
            "cr_level" => $formData['cr_level'],
            "first_10cfr_21_potentially_reportable" => $formData['first_10cfr_21_potentially_reportable'],
            "first_10cfr_21_potentially_reportable_date" => $formData['first_10cfr_21_potentially_reportable_date'] ? date('Y-m-d', strtotime($formData['first_10cfr_21_potentially_reportable_date'])) : NULL,
            "first_paaa_applicable" => $formData['first_paaa_applicable'],
            "first_paaa_applicable_date" =>  $formData['first_paaa_applicable_date'] ? date('Y-m-d', strtotime($formData['first_paaa_applicable_date'])) : NULL,
            "significant_condition_adverse_to_quality" => $formData['significant_condition_adverse_to_quality'],
            "significant_condition_adverse_to_quality_car_no" => $formData['significant_condition_adverse_to_quality_car_no'],
            "non_conformance_report_validated" => $formData['non_conformance_report_validated'],
            "extent_of_condition" => $formData['extent_of_condition'],
            "signature_second_user_id" => $formData['signature_second_user_id'],
            "signature_second_person" => $user_signature,
            "signatory_title_second_person" => $formData['signatory_title_second_person'],
            "second_signature_date" =>  $formData['second_signature_date'] ? date('Y-m-d', strtotime($formData['second_signature_date'])) : NULL,
            "stage_no" => 1
        ]);

        return response()->json(['success' => true, 'data' => [], 'message' => 'Condition Report Added Successful!']); 
    }

    public function updateCRReport(Request $request)
    {
        $formData = json_decode($request->data, true);

        if(!$formData['id']){
            return response()->json(['success' => false, 'data' => [], 'message' => 'Please, attach ID !']); 
        }

        // $signature_thumbnail = '';
        // if($request->file){
        //     $signature_thumbnail  =  'signature_' .time().'.'.$request->file->getClientOriginalExtension();
        //     $request->file->move('uploads/signatures/', $signature_thumbnail);
        // }

        $user = User::where('id', $formData['corrective_action_user_id'])->first();

        $user_signature = null;
        if(!empty($user)){
            if($user->signature){
                $user_signature = $user->signature;
            }
        }
        
        $add_new_cr = ConditionReport::where('id', $formData['id'])->update([
            "corrective_action" => $formData['corrective_action'],
            "corrective_action_10cfr_21_potentially_reportable" => $formData['corrective_action_10cfr_21_potentially_reportable'],
            "corrective_action_paaa_applicable" => $formData['corrective_action_paaa_applicable'],
            "corrective_action_scheduled_completion_date" => $formData['corrective_action_scheduled_completion_date'] ? date('Y-m-d', strtotime($formData['corrective_action_scheduled_completion_date'])) : NULL,
            "corrective_action_user_id" => $formData['corrective_action_user_id'],
            "corrective_action_signature" => $user_signature,
            "corrective_action_signatory_title" => $formData['corrective_action_signatory_title'],
            "corrective_action_signature_date" => $formData['corrective_action_signature_date'] ? date('Y-m-d', strtotime($formData['corrective_action_signature_date'])) : NULL,

            "cause_of_occurrence" => $formData['cause_of_occurrence'],
            "cause_of_occurrence_user_id" => $formData['cause_of_occurrence_user_id'],
            "cause_of_occurrence_signature" => $user_signature,
            "cause_of_occurrence_signatory_title" => $formData['cause_of_occurrence_signatory_title'],
            "cause_of_occurrence_signature_date" => $formData['cause_of_occurrence_signature_date'] ? date('Y-m-d', strtotime($formData['cause_of_occurrence_signature_date'])) : NULL,

            "prevent_recurrence" => $formData['prevent_recurrence'],
            "prevent_recurrence_scheduled_completion_date" => $formData['prevent_recurrence_scheduled_completion_date'] ? date('Y-m-d', strtotime($formData['prevent_recurrence_scheduled_completion_date'])) : NULL,
            "prevent_recurrence_user_id" => $formData['prevent_recurrence_user_id'],
            "prevent_recurrence_signature" => $user_signature,
            "prevent_recurrence_signatory_title" => $formData['prevent_recurrence_signatory_title'],
            "prevent_recurrence_signature_date" => $formData['prevent_recurrence_signature_date'] ? date('Y-m-d', strtotime($formData['prevent_recurrence_signature_date'])) : NULL,

            "concurrence_department" => $formData['concurrence_department'],
            "concurrence_user_id" => $formData['concurrence_user_id'],
            "concurrence_signature" => $user_signature,
            "concurrence_remark" => $formData['concurrence_remark'],
            "concurrence_comments" => $formData['concurrence_comments'],
            "concurrence_signature_date" => $formData['concurrence_signature_date'] ? date('Y-m-d', strtotime($formData['concurrence_signature_date'])) : NULL,

            "verification_of_corrective_action" => $formData['verification_of_corrective_action'],
            "hold_tags_removed" => $formData['hold_tags_removed'],
            "global_comments" => $formData['global_comments'],
            "verification_signature" => $user_signature,
            "verification_user_id" => $formData['verification_user_id'],
            "verification_signatory_title" => $formData['verification_signatory_title'],
            "verification_signature_date" => $formData['verification_signature_date'] ? date('Y-m-d', strtotime($formData['verification_signature_date'])) : NULL,
            "stage_no" => 2
        ]);

        return response()->json(['success' => true, 'data' => [], 'message' => 'Condition Report Updated Successful!']); 
    }

    public function reportDetailsById(Request $request)
    {
        $report = ConditionReport::where('id', $request->report_id)->first();

        return response()->json(['success' => true, 'data' => $report, 'message' => 'Successful!']); 
    }
}
