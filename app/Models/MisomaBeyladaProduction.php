<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MisomaBeyladaProduction extends Model
{
     



 protected $table = 'misoma_beylada_productions';

 protected $fillable = [
     
     'year',
     'Jabbii_ai',
     'killee_oomishame',
     'lukkuu_foonii',
     'damma_oomishame',
     'qurxummii_gabaaf',
     'aannan_elmame',
     'omisha_beylada',
     'kebele_id',
     'woreda_id',
     'zone_id',
     'region_id',
     'created_by',
     'phone',
 ];

 // Relationships

  

 public function kebele()
 {
     return $this->belongsTo(Kebele::class);
 }

 public function woreda()
 {
     return $this->belongsTo(Woreda::class);
 }

 public function zone()
 {
     return $this->belongsTo(Zone::class);
 }

 public function region()
 {
     return $this->belongsTo(Region::class);
 }

 public function creator()
 {
     return $this->belongsTo(User::class, 'created_by');
 }

}
