<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractsMaster extends Model
{
    protected $table = 'contracts_masters';

    protected $guarded = [];

    protected $fillable = [
        "property_id",
        "room_id",
        "tenant_id",
        "contract_type",
        "unit_rent_sm",
        "unit_rent_tsubo",
        "monthly_rent",
        "date_of_rent_occurrence",
        "initial_month_rent",
        "final_month_rent",
        "unit_common_fees_sm",
        "unit_common_fees_tsubo",
        "monthly_common_fees",
        "date_of_common_fee_occurrence",
        "initial_month_common_fee",
        "final_month_common_fee",
        "unit_of_total_rent_sm",
        "unit_of_total_rent_tsubo",
        "total_rent",
        "total_initial_month_rent",
        "total_final_month_rent",
        "number_of_deposit_months",
        "deposit",
        "date_of_contract_start",
        "date_of_contract_end",
        "automatic_extention",
        "month_of_automatic_extention",
        "period_of_cancel_notice",
        "date_of_received_application_form_for_moving_in",
        "date_of_contract_signed",
        "document_delivery_date",
        "date_of_noticing_contract_termination",
        "cancellation_date",
        "remarks_on_furniture_and_equipment",
        "other_remarks",
        "is_active"
    ];

    public static $rules = [
        "property_id" => "required",
        "room_id" => "required",
        "tenant_id" => "required",
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'automatic_extention' => 'boolean'
    ];
}

