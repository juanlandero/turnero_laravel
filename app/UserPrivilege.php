<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPrivilege extends Model
{
    protected $table        = "users_privileges";
    public $timestamps      = false;
    protected $primaryKey   = 'user_id';

    protected $fillable = ['user_id', 'privilege_id'];

    public static function getPrivilegesMenu(User $objUser) {
        $return = array();

        $lstPrivilegesCategories = PrivilegeCategory::orderBy('menu_order', 'asc')->orderBy('id', 'asc')->get();

        foreach ($lstPrivilegesCategories as $item) {
            $lstPrivileges = $objUser->privileges
                    ->where('privilege_category_id', $item->id)
                    ->where('menu', true)
                    ->where('is_active', true)
                    ->sortBy('menu_order');
            if(sizeof($lstPrivileges) > 0 ) {
                array_push($return, array(
                    'category'         => $item,
                    'privileges'       => $lstPrivileges
                ));
            }
        }

        return $return;
    }
}
