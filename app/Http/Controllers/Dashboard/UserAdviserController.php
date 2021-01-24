<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Library\Returns\ActionReturn;
use App\Library\Errors;
use App\Library\Messages;
use App\SpecialityTypeUser;
use App\SpecialityType;
use App\UserPrivilege;
use App\UserOffice;
use App\Office;
use App\User;
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

    public function edit($idUser) {
        $objUser    = User::where('id', $idUser)->first();

        if(!is_null($objUser)) {
            $lstOffices = Office::where('is_active', true)->orderBy('name', 'ASC')->get();
            $lstBoxes   = Box::where('is_active', true)->get();

            return View('dashboard.contents.users.advisers.Edit', ["lstOffices" => $lstOffices, "lstBoxes" => $lstBoxes, "objUser" => $objUser]);
        }

        return redirect()->to('/dashboard/users-advisers');
    }

    public function update(Request $request) {
        $request->validate([
            'txtName'           => 'required|string|max:255',
            'txtFirstName'      => 'required|string|max:50',
            'txtSecondName'     => 'required|string|max:50',
            'cmbOffice'         => 'required|integer|exists:offices,id',
            'cmbBox'            => 'required|integer|exists:boxes,id',
            'email'             => ['required','string','max:80',Rule::unique('users')->ignore($request->hddIdUser)],
            'txtPassword'       => 'nullable|string|max:20'
        ],[
            'txtEmail.unique'   => 'El correo ingresado ya pertenece a otro usuario.'
        ]);

        $objReturn  = new ActionReturn('dashboard/users-advisers/edit/'.$request->hddIdUser, 'dashboard/users-advisers');
        $objUser    = User::where('id', $request->hddIdUser)->first();

        if(!is_null($objUser)) {
            try {
                $objUser->name            = $request->txtName;
                $objUser->first_name      = $request->txtFirstName;
                $objUser->second_name     = $request->txtSecondName;
                $objUser->email           = $request->email;
    
                if(isset($request->txtPassword))
                    $objUser->password        = bcrypt($request->txtPassword);
                
                DB::beginTransaction();
                if($objUser->save()) {
                    $objUserOffice              = UserOffice::where('user_id', $objUser->id)->first();
                    $objUserOffice->office_id   = $request->cmbOffice;
                    $objUserOffice->box_id      = $request->cmbBox;
                    $objUserOffice->save();

                    $objReturn->setResult(true, Messages::USER_ADVISER_EDIT_TITLE, Messages::USER_ADVISER_EDIT_MESSAGE);
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

    public function delete($idAdviser){
        $userAdviser = User::where('id', $idAdviser)->first();
        $userAdviser->is_active = 0;

        $objReturn = new ActionReturn('dashboard/users-advisers/delete', 'dashboard/users-advisers');
    
        try {
            if($userAdviser->save()) {
                $objReturn->setResult(true, Messages::USER_ADVISER_DELETE_TITLE, Messages::USER_ADVISER_DELETE_MESSAGE);
            } else {
                $objReturn->setResult(false, Errors::USER_ADVISER_03_TITLE, Errors::USER_ADVISER_03_MESSAGE);
            }
        } catch(Exception $exception) {
            $objReturn->setResult(false, Errors::getErrors($exception->getCode())['title'], Errors::getErrors($exception->getCode())['message']);
        }

        return $objReturn->getRedirectPath();
    }


    public function speciality($userId){
        $objUser = User::join('user_types', 'users.user_type_id', 'user_types.id')
                        ->where('users.id', $userId)
                        ->select(
                            'users.id',
                            'users.name',
                            'users.email',
                            'users.first_name',
                            'users.second_name',
                            'user_types.user_type'
                        )
                        ->first();

        $objUserSpecialities = SpecialityTypeUser::join('speciality_types', 'speciality_type_users.speciality_type_id', 'speciality_types.id')
                        ->where('speciality_type_users.user_id', $objUser->id)
                        ->select(
                            'speciality_type_users.id',
                            'speciality_type_users.speciality_type_id',
                            'speciality_types.name'
                        )
                        ->get();

        $objSpecialities = SpecialityType::all();

        return view('dashboard.contents.users.advisers.Speciality', [
                                                                        'user'              => $objUser,
                                                                        'specialities'      => $objUserSpecialities,
                                                                        'specialityType'    => $objSpecialities
                                                                    ]);
    }

    public function storeSpeciality(Request $request){
        $countSpeciality = SpecialityType::where('is_active', 1)->count();

        for ($i=1; $i < $countSpeciality+1; $i++) { 
            if (isset($request->$i)) {
                $speciality = new SpecialityTypeUser();

                $speciality->speciality_type_id = $i;
                $speciality->user_id = $request->user_id;
                $speciality->save();
            }
        }

        return redirect()->route('user-advisers.index');
    }

    public function deleteSpeciality($specialityId){
        $specialiyUser = SpecialityTypeUser::where('id', $specialityId)->first();

        $userId = $specialiyUser->user_id;

        $specialiyUser->delete();

        // return $specialiyUser;
        return redirect('dashboard/users-advisers/speciality/'.$userId);
    }
}
