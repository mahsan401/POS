<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VariationLocationDetails extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    
    
  public function product(){
        return $this->belongsTo('App\Product');
    }
    
      public function location(){
        return $this->belongsTo('\App\BusinessLocation');
    }
     public function variation(){
        return $this->belongsTo('\App\Variation');
    }
}
