<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserOffice extends Model
{
    protected $table    = "user_offices";
    protected $fillable = ['user_id', 'office_id', 'box_id', 'is_active'];

    /* RELATIONSHIPS - INICIO */
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function office() {
        return $this->belongsTo(Office::class);
    }

    public function box() {
        return $this->belongsTo(Box::class);
    }
    /* RELATIONSHIPS - FIN */
}
