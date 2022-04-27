<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $table = 'tenants';

    protected $guarded = [];

    protected $fillable = [
        "company_name",
        "company_name_jp",
        "email",
        "address",
        "type_of_industry",
        "number_of_employee",
        "representative_name",
        "establishment_date",
        "profile_image",
        "market_capitalization",
        "revenue",
        "is_active"
    ];

    public static $rules = [
        "company_name" => "required",
        "email" => "required",
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}

