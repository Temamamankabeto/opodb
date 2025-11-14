<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'institution_type_id'];

    public function type()
    {
        return $this->belongsTo(InstitutionType::class, 'institution_type_id');
    }
}
