<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomMaster extends Model
{
    protected $table = 'room_masters';

    protected $guarded = [];

    protected $fillable = [
        "property_id",
        "room_number",
        "room_number_jp",
        "floor_no",
        "room_area_sm",
        "room_area_tsubo",
        "uses",
        "is_active"
    ];

    public static $rules = [
        "room_number" => "required",
        "property_id" => "required",
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}

