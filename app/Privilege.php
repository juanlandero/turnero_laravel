<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Privilege extends Model
{
    protected $table        = "privileges";
    public $timestamps      = false;

    protected $fillable = ['privilege_category_id', 'privilege', 'description', 'menu', 'menu_order', 'menu_url', 'is_active'];


    /* RELATIONSHIPS - INICIO */
    public function privilegeCategory() {
        return $this->belongsTo(PrivilegeCategory::class);
    }
    /* RELATIONSHIPS - FIN */
}
