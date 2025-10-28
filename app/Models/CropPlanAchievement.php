<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CropPlanAchievement extends Model
{
    //
    use HasFactory;
    protected $table = 'crop_plan_achievements';
    protected $fillable = [
        'maqaa_midhaanii',
        'kblg', 'kblb', 'kbla',
        'rblg', 'rblb', 'rbla',
        'kog', 'kob', 'koa',
        'rog', 'rob', 'roa',
        'year',
        'ikbl', 'iKo', 'irbl', 'iro',
        'this_year_gana_productivity',
        'this_year_bona_productivity',
        'this_year_arfasa_productivity',
        'crop_type_id',
    ];

    public function croptype()
    {
        return $this->belongsTo(CropType::class,'name');
    }

    public function crop()
{
    return $this->belongsTo(Crop::class, 'maqaa_midhaanii');
}

   
}
