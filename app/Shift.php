<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    //

    public function specialityType() {
        return $this->belongsTo(SpecialityType::class);
    }
}
