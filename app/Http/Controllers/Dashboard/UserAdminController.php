<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Returns\ActionReturn;
use App\Library\Errors;
use App\Library\Messages;
use App\UserPrivilege;
use App\User;

class UserAdminController extends Controller
{
    public function index() {
        $lstUsers = User::where('is_active', true)->where('user_type_id', 1)->get();
        return View('dashboard.contents.users.admin.Index', ['lstUsers' => $lstUsers]);
    }

    public function create() {
        return View('dashboard.contents.users.admin.Create');
    }

    public function store(Request $request) {
        $request->validate([
            'txtName'           => 'required|string|max:255',
            'txtFirstName'      => 'required|string|max:50',
            'txtSecondName'     => 'required|string|max:50',
            'txtEmail'          => 'required|string|unique:users,email|max:80',
            'txtPassword'       => 'required|string|max:20'
        ],[
            'txtEmail.unique'   => 'El correo ingresado ya pertenece a otro usuario.'
        ]);

        $objReturn = new ActionReturn('dashboard/users-admins/create', 'dashboard/users-admins');

        $objUser                  = new User();
        $objUser->name            = $request->txtName;
        $objUser->first_name      = $request->txtFirstName;
        $objUser->second_name     = $request->txtSecondName;
        $objUser->user_type_id    = 1;
        $objUser->email           = $request->txtEmail;
        $objUser->password        = bcrypt($request->txtPassword);
        $objUser->is_active       = true;

        try {
            if($objUser->save()) {
                $privileges = array();
                  
                  for($i = 1; $i < 25; $i++) {
                      if($i != 22) {
                          array_push($privileges, array(
                            'user_id'         => $objUser->id,
                            'privilege_id'    => $i,
                          ));
                      }
                  }

                  UserPrivilege::create($privileges);

                $objReturn->setResult(true, Messages::USER_ADMIN_CREATE_TITLE, Messages::USER_ADMIN_CREATE_MESSAGE);
            } else {
                $objReturn->setResult(false, Errors::USER_CREATE_01_TITLE, Errors::USER_CREATE_01_MESSAGE);
            }
        } catch(Exception $exception) {
            $objReturn->setResult(false, Errors::getErrors($exception->getCode())['title'], Errors::getErrors($exception->getCode())['message']);
        }

        return $objReturn->getRedirectPath();
    }
}
