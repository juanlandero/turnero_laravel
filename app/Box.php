<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    protected $table    = "boxes";
    protected $fillable = ['box_name', 'is_active'];

    /* RELATIONSHIPS - INICIO */
    public function userOffice() {
        return $this->hasMany(UserOffice::class);
    }
    /* RELATIONSHIPS - FIN */
}
