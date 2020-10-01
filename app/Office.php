<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $table    = "offices";
    protected $fillable = ['name', 'address', 'phone', 'channel', 'office_key', 'municipality_id', 'is_active'];

    /* RELATIONSHIPS - INICIO */
    public function municipality() {
        return $this->belongsTo(Municipality::class);
    }

    public function userOffice() {
        return $this->hasMany(UserOffice::class);
    }
    /* RELATIONSHIPS - FIN */
}
