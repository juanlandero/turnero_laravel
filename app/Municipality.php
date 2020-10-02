<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    protected $table    = "municipalities";
    protected $fillable = ['name'];

    /* RELATIONSHIPS - INICIO */
    public function offices() {
        return $this->hasMany(Office::class);
    }
    /* RELATIONSHIPS - FIN */
}
