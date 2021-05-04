<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpecialityType extends Model
{
    protected $table    = "speciality_types";
    protected $fillable = ['name', 'description', 'class_icon', 'is_active'];

  
}
