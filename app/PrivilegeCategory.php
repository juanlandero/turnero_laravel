<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrivilegeCategory extends Model
{
    protected $table    = "privileges_categories";
    public $timestamps  = false;

    protected $fillable = ['privilege_category', 'menu_order', 'icon'];

    /* RELATIONSHIP - INICIO */
    public function privileges() {
        return $this->hasMany(Privileges::class);
    }
    /* RELATIONSHIP - FIN */
}
