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
use App\Box;
use DB;

class UserAdviserController extends Controller
{
    public function index() {
        $lstUsers = User::where('is_active', true)->where('user_type_id', 3)->get();
        return View('dashboard.contents.users.advisers.Index', ['lstUsers' => $lstUsers]);
    }

    public function create() {
        $lstOffices = Office::where('is_active', true)->orderBy('name', 'ASC')->get();
        $lstBoxes   = Box::where('is_active', true)->get();
        return View('dashboard.contents.users.advisers.Create', ["lstOffices" => $lstOffices, "lstBoxes" => $lstBoxes]);
    }

    public function store(Request $request) {
        $request->validate([
            'txtName'           => 'required|string|max:255',
            'txtFirstName'      => 'required|string|max:50',
            'txtSecondName'     => 'required|string|max:50',
            'cmbOffice'         => 'required|integer|exists:offices,id',
            'cmbBox'            => 'required|integer|exists:boxes,id',
            'txtEmail'          => 'required|string|unique:users,email|max:80',
            'txtPassword'       => 'required|string|max:20'
        ],[
            'txtEmail.unique'   => 'El correo ingresado ya pertenece a otro usuario.'
        ]);

        $objReturn = new ActionReturn('dashboard/users-advisers/create', 'dashboard/users-advisers');

        $objUser                  = new User();
        $objUser->name            = $request->txtName;
        $objUser->first_name      = $request->txtFirstName;
        $objUser->second_name     = $request->txtSecondName;
        $objUser->user_type_id    = 3;
        $objUser->email           = $request->txtEmail;
        $objUser->password        = bcrypt($request->txtPassword);
        $objUser->is_active       = true;

        try {
            DB::beginTransaction();
            if($objUser->save()) {
                $objUserOffice              = new UserOffice();
                $objUserOffice->user_id     = $objUser->id;
                $objUserOffice->office_id   = $request->cmbOffice;
                $objUserOffice->box_id      = $request->cmbBox;
                $objUserOffice->is_active   = true;
                $objUserOffice->save();

                $objPriv                = new UserPrivilege();
                $objPriv->user_id       = $objUser->id;
                $objPriv->privilege_id  = 1;
                $objPriv->save();

                $objReturn->setResult(true, Messages::USER_ADVISER_CREATE_TITLE, Messages::USER_ADVISER_CREATE_MESSAGE);
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
