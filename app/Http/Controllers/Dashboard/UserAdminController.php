<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
                  
                  for($i = 1; $i < 32; $i++) {
                      if($i != 22) {
                        $objPriv = new UserPrivilege();
                        $objPriv->user_id       = $objUser->id;
                        $objPriv->privilege_id  = $i;
                        $objPriv->save();
                      }
                  }

                $objReturn->setResult(true, Messages::USER_ADMIN_CREATE_TITLE, Messages::USER_ADMIN_CREATE_MESSAGE);
            } else {
                $objReturn->setResult(false, Errors::USER_CREATE_01_TITLE, Errors::USER_CREATE_01_MESSAGE);
            }
        } catch(Exception $exception) {
            $objReturn->setResult(false, Errors::getErrors($exception->getCode())['title'], Errors::getErrors($exception->getCode())['message']);
        }

        return $objReturn->getRedirectPath();
    }

    public function edit($idUser) {
        $objUser = User::where('id', $idUser)->first();

        if(!is_null($objUser))
            return View('dashboard.contents.users.admin.Edit', ["objUser" => $objUser]);

        return redirect()->to('/dashboard/users-admins');
    }

    public function update(Request $request) {
        $request->validate([
            'txtName'           => 'required|string|max:255',
            'txtFirstName'      => 'required|string|max:50',
            'txtSecondName'     => 'required|string|max:50',
            'email'             => ['required','string','max:80',Rule::unique('users')->ignore($request->hddIdUser)],
            'txtPassword'       => 'nullable|string|max:20'
        ],[
            'email.unique'   => 'El correo ingresado ya pertenece a otro usuario.'
        ]);

        $objReturn  = new ActionReturn('dashboard/users-admins/edit/'.$request->hddIdUser, 'dashboard/users-admins');
        $objUser    = User::where('id', $request->hddIdUser)->first();

        if(!is_null($objUser)) {
            try {
                $objUser->name            = $request->txtName;
                $objUser->first_name      = $request->txtFirstName;
                $objUser->second_name     = $request->txtSecondName;
                $objUser->email           = $request->email;

                if(isset($request->txtPassword))
                    $objUser->password        = bcrypt($request->txtPassword);

                if($objUser->save()) {
                    $objReturn->setResult(true, Messages::USER_ADMIN_EDIT_TITLE, Messages::USER_ADMIN_EDIT_MESSAGE);
                } else {
                    $objReturn->setResult(false, Errors::USER_EDIT_02_TITLE, Errors::USER_EDIT_02_MESSAGE);
                }
            } catch(Exception $exception) {
                $objReturn->setResult(false, Errors::getErrors($exception->getCode())['title'], Errors::getErrors($exception->getCode())['message']);
            }
        } else {
            $objReturn->setResult(false, Errors::USER_EDIT_01_TITLE, Errors::USER_EDIT_01_MESSAGE);
        }

        return $objReturn->getRedirectPath();
    }
}
