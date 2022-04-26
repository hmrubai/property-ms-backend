<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyMaster extends Model
{
    protected $table = 'property_masters';

    protected $guarded = [];

    protected $fillable = [
        "property_name",
        "property_name_jp",
        "location",
        "gross_floor_area_sm",
        "gross_floor_area_tsubo",
        "private_area_sm",
        "private_area_tsubo",
        "building_structure",
        "story",
        "underground_story",
        "date_of_completion",
        "owner_on_the_registry",
        "owner_address",
        "property_image",
        "is_active"
    ];

    public static $rules = [
        "property_name" => "required",
        "location" => "required",
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}

