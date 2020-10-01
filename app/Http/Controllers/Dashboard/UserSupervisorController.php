<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Returns\ActionReturn;
use App\Library\Errors;
use App\Library\Messages;
use App\User;
use App\UserOffice;
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
}
