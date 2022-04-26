<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConditionReport extends Model
{
    protected $table = 'condition_reports';

    protected $guarded = [];

    protected $fillable = [
        "reference_no",
        "report_no",
        "associated_ncr",
        "assigned_to",
        "responsible_dept",
        "target_close_date",
        "validated_by",
        "validated_date",
        "location",
        "supplier_name",
        "po",
        "client_project",
        "so",
        "job_asm",
        "system_description",
        "pn",
        "dwg",
        "qty",
        "is_hold_tag_applied",
        "no_hold_tag_applied",
        "signature_first_user_id",
        "signature_first_person",
        "signatory_title_first_person",
        "creation_date",
        "cr_level",
        "first_10cfr_21_potentially_reportable",
        "first_10cfr_21_potentially_reportable_date",
        "first_paaa_applicable",
        "first_paaa_applicable_date",
        "significant_condition_adverse_to_quality",
        "significant_condition_adverse_to_quality_car_no",
        "non_conformance_report_validated",
        "extent_of_condition",
        "signature_second_user_id",
        "signature_second_person",
        "signatory_title_second_person",
        "second_signature_date",
        "corrective_action",
        "corrective_action_10cfr_21_potentially_reportable",
        "corrective_action_paaa_applicable",
        "corrective_action_scheduled_completion_date",
        "corrective_action_user_id",
        "corrective_action_signature",
        "corrective_action_signatory_title",
        "corrective_action_signature_date",
        "cause_of_occurrence",
        "cause_of_occurrence_user_id",
        "cause_of_occurrence_signature",
        "cause_of_occurrence_signatory_title",
        "cause_of_occurrence_signature_date",
        "prevent_recurrence",
        "prevent_recurrence_scheduled_completion_date",
        "prevent_recurrence_user_id",
        "prevent_recurrence_signature",
        "prevent_recurrence_signatory_title",
        "prevent_recurrence_signature_date",
        "concurrence_department",
        "concurrence_user_id",
        "concurrence_signature",
        "concurrence_signature_date",
        "concurrence_remark",
        "concurrence_comments",
        "verification_of_corrective_action",
        "hold_tags_removed",
        "global_comments",
        "verification_user_id",
        "verification_signature",
        "verification_signatory_title",
        "verification_signature_date",
        "is_active",
        "stage_no"
    ];

    public static $rules = [
        "post_id" => "required",
        "user_id" => "required",
        "comment" => "required",
    ];

    // protected $casts = [
    //     'is_active' => 'boolean',
    // ];
}

