<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConditionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('condition_reports', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no');
            $table->string('report_no');
            $table->string('associated_ncr')->nullable();
            $table->string('assigned_to');
            $table->string('responsible_dept')->nullable();
            $table->date('target_close_date')->nullable();
            $table->string('validated_by')->nullable();
            $table->date('validated_date')->nullable();
            $table->string('location')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('po')->nullable();
            $table->string('client_project')->nullable();
            $table->string('so')->nullable();
            $table->string('job_asm')->nullable();
            $table->string('system_description')->nullable();
            $table->string('pn')->nullable();
            $table->string('dwg')->nullable();
            $table->string('qty')->nullable();
            $table->string('is_hold_tag_applied')->nullable();
            $table->string('no_hold_tag_applied')->nullable();
            $table->string('signature_first_user_id')->nullable();
            $table->string('signature_first_person')->nullable();
            $table->string('signatory_title_first_person')->nullable();
            $table->date('creation_date')->nullable();
            $table->string('cr_level')->nullable();
            $table->string('first_10cfr_21_potentially_reportable')->nullable();
            $table->string('first_10cfr_21_potentially_reportable_date')->nullable();
            $table->string('first_paaa_applicable')->nullable();
            $table->string('first_paaa_applicable_date')->nullable();
            $table->string('significant_condition_adverse_to_quality')->nullable();
            $table->string('significant_condition_adverse_to_quality_car_no')->nullable();
            $table->string('non_conformance_report_validated')->nullable();
            $table->string('extent_of_condition')->nullable();
            $table->string('signature_second_user_id')->nullable();
            $table->string('signature_second_person')->nullable();
            $table->string('signatory_title_second_person')->nullable();
            $table->date('second_signature_date')->nullable();
            $table->text('corrective_action')->nullable();
            $table->string('corrective_action_10cfr_21_potentially_reportable')->nullable();
            $table->string('corrective_action_paaa_applicable')->nullable();
            $table->date('corrective_action_scheduled_completion_date')->nullable();
            $table->string('corrective_action_user_id')->nullable();
            $table->string('corrective_action_signature')->nullable();
            $table->string('corrective_action_signatory_title')->nullable();
            $table->date('corrective_action_signature_date')->nullable();
            $table->text('cause_of_occurrence')->nullable();
            $table->string('cause_of_occurrence_user_id')->nullable();
            $table->string('cause_of_occurrence_signature')->nullable();
            $table->string('cause_of_occurrence_signatory_title')->nullable();
            $table->date('cause_of_occurrence_signature_date')->nullable();
            $table->text('prevent_recurrence')->nullable();
            $table->date('prevent_recurrence_scheduled_completion_date')->nullable();
            $table->string('prevent_recurrence_user_id')->nullable();
            $table->string('prevent_recurrence_signature')->nullable();
            $table->string('prevent_recurrence_signatory_title')->nullable();
            $table->date('prevent_recurrence_signature_date')->nullable();
            $table->string('concurrence_user_id')->nullable();
            $table->string('concurrence_department')->nullable();
            $table->string('concurrence_signature')->nullable();
            $table->date('concurrence_signature_date')->nullable();
            $table->enum('concurrence_remark', ['Accepted', 'Rejected', 'AcceptedWithComments'])->default('Accepted');
            $table->text('concurrence_comments')->nullable();
            $table->string('verification_of_corrective_action')->nullable();
            $table->string('hold_tags_removed')->nullable();
            $table->string('global_comments')->nullable();
            $table->string('verification_user_id')->nullable();
            $table->string('verification_signature')->nullable();
            $table->string('verification_signatory_title')->nullable();
            $table->date('verification_signature_date')->nullable();
            $table->boolean('is_active')->default(1);
            $table->integer('stage_no')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('condition_reports');
    }
}
