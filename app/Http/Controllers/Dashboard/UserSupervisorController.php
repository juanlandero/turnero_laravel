<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Library\Returns\ActionReturn;
use App\Library\Errors;
use App\Library\Messages;
use App\User;
use App\UserOffice;
use App\UserPrivilege;
use App\Office;
use DB;

class UserSupervisorController extends Controller
{
    public function index() {
        $lstUsers = User::where('is_active', true)->where('user_type_id', 2)->get();
        return View('dashboard.contents.users.supervisors.Index', ['lstUsers' => $lstUsers]);
    }

    public function create() {
        $lstOffices = Office::where('is_active', true)->orderBy('name', 'ASC')->get();
        return View('dashboard.contents.users.supervisors.Create', ['lstOffices' => $lstOffices]);
    }

    public function store(Request $request) {
        $request->validate([
            'txtName'           => 'required|string|max:255',
            'txtFirstName'      => 'required|string|max:50',
            'txtSecondName'     => 'required|string|max:50',
            'cmbOffice'         => 'required|integer|exists:offices,id',
            'txtEmail'          => 'required|string|unique:users,email|max:80',
            'txtPassword'       => 'required|string|max:20'
        ],[
            'txtEmail.unique'   => 'El correo ingresado ya pertenece a otro usuario.'
        ]);

        $objReturn = new ActionReturn('dashboard/users-supervisors/create', 'dashboard/users-supervisors');

        $objUser                  = new User();
        $objUser->name            = $request->txtName;
        $objUser->first_name      = $request->txtFirstName;
        $objUser->second_name     = $request->txtSecondName;
        $objUser->user_type_id    = 2;
        $objUser->email           = $request->txtEmail;
        $objUser->password        = bcrypt($request->txtPassword);
        $objUser->is_active       = true;

        try {
            DB::beginTransaction();
            if($objUser->save()) {
                $objUserOffice              = new UserOffice();
                $objUserOffice->user_id     = $objUser->id;
                $objUserOffice->office_id   = $request->cmbOffice;
                $objUserOffice->box_id      = 1;
                $objUserOffice->is_active   = true;
                $objUserOffice->save();

                $privileges = [2,3,4,5,6,19,20,21,22,27,28];
                foreach ($privileges as $key => $value) {
                    $objPriv = new UserPrivilege();
                    $objPriv->user_id       = $objUser->id;
                    $objPriv->privilege_id  = $value;
                    $objPriv->save();
                }

                $objReturn->setResult(true, Messages::USER_SUPERVISOR_CREATE_TITLE, Messages::USER_SUPERVISOR_CREATE_MESSAGE);
                DB::commit();
            } else {
                $objReturn->setResult(false, Errors::USER_CREATE_01_TITLE, Errors::USER_CREATE_01_MESSAGE);
                DB::rollBack();
            }
        } catch(Exception $exception) {
            $objReturn->setResult(false, Errors::getErrors($exception->getCode())['title'], Errors::getErrors($exception->getCode())['message']);
            DB::rollBack();
        }

        return $objReturn->getRedirectPath();
    }

    public function edit($idUser) {
        $objUser    = User::where('id', $idUser)->first();
        $lstOffices = Office::where('is_active', true)->orderBy('name', 'ASC')->get();

        if(!is_null($objUser))
            return View('dashboard.contents.users.supervisors.Edit', ["objUser" => $objUser, "lstOffices" => $lstOffices]);

        return redirect()->to('/dashboard/users-supervisors');
    }

    public function update(Request $request) {
        $request->validate([
            'txtName'           => 'required|string|max:255',
            'txtFirstName'      => 'required|string|max:50',
            'txtSecondName'     => 'required|string|max:50',
            'cmbOffice'         => 'required|integer|exists:offices,id',
            'email'             => ['required','string','max:80',Rule::unique('users')->ignore($request->hddIdUser)],
            'txtPassword'       => 'nullable|string|max:20'
        ],[
            'email.unique'   => 'El correo ingresado ya pertenece a otro usuario.'
        ]);

        $objReturn  = new ActionReturn('dashboard/users-supervisors/edit/'.$request->hddIdUser, 'dashboard/users-supervisors');
        $objUser    = User::where('id', $request->hddIdUser)->first();

        if(!is_null($objUser)) {
            try {
                $objUser->name          = $request->txtName;
                $objUser->first_name    = $request->txtFirstName;
                $objUser->second_name   = $request->txtSecondName;
                $objUser->email         = $request->email;

                if(isset($request->txtPassword))
                    $objUser->password        = bcrypt($request->txtPassword);

                DB::beginTransaction();
                if($objUser->save()) {
                    $objUserOffice              = UserOffice::where('user_id', $objUser->id)->first();
                    $objUserOffice->office_id   = $request->cmbOffice;
                    $objUserOffice->save();

                    $objReturn->setResult(true, Messages::USER_SUPERVISOR_EDIT_TITLE, Messages::USER_SUPERVISOR_EDIT_MESSAGE);
                    DB::commit();
                } else {
                    $objReturn->setResult(false, Errors::USER_EDIT_02_TITLE, Errors::USER_EDIT_02_MESSAGE);
                    DB::rollBack();
                }
            } catch(Exception $exception) {
                $objReturn->setResult(false, Errors::getErrors($exception->getCode())['title'], Errors::getErrors($exception->getCode())['message']);
                DB::rollBack();
            }
        } else {
            $objReturn->setResult(false, Errors::USER_EDIT_01_TITLE, Errors::USER_EDIT_01_MESSAGE);
            DB::rollBack();
        }

        return $objReturn->getRedirectPath();
    }
}
