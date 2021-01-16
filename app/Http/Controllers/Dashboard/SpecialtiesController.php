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

class SpecialtiesController extends Controller
{
    public function index() {
        $lstSpecialties = SpecialityType::where('is_active', true)->get();
        return View('dashboard.contents.specialties.Index', ['lstSpecialties' => $lstSpecialties]);
    }

    public function create() {
        return View('dashboard.contents.specialties.Create');
    }

    public function store(Request $request) {
        $request->validate([
            'txtName'           => 'required|string|max:255|unique:speciality_types,name',
            'txtDescription'    => 'nullable|string|max:255',
            'txtIcon'           => 'required|string|max:50',
        ],[
            'txtName.unique'   => 'La especialidad ingresada ya se encuentra registrada.'
        ]);

        $objReturn = new ActionReturn('dashboard/specialties/create', 'dashboard/specialties');

        $objSpecialty               = new SpecialityType();
        $objSpecialty->name         = $request->txtName;
        $objSpecialty->description  = $request->txtDescription;
        $objSpecialty->class_icon   = $request->txtIcon;
        $objSpecialty->is_active    = true;

        try {
            if($objSpecialty->save()) {
                $objReturn->setResult(true, Messages::SPECIALTY_CREATE_TITLE, Messages::SPECIALTY_CREATE_MESSAGE);
            } else {
                $objReturn->setResult(false, Errors::SPECIALTY_CREATE_01_TITLE, Errors::SPECIALTY_CREATE_01_MESSAGE);
            }
        } catch(Exception $exception) {
            $objReturn->setResult(false, Errors::getErrors($exception->getCode())['title'], Errors::getErrors($exception->getCode())['message']);
        }

        return $objReturn->getRedirectPath();
    }

    public function edit($idSpecialty) {
        $objSpecialty = SpecialityType::where('id', $idSpecialty)->first();

        if(!is_null($objSpecialty))
            return View('dashboard.contents.specialties.Edit', ['objSpecialty' => $objSpecialty]);

        return redirect()->to('/dashboard/specialties');
    }

    public function update(Request $request) {
        $request->validate([
            'name'             => ['required','string','max:255',Rule::unique('speciality_types')->ignore($request->hddIdSpecialty)],
            'txtDescription'    => 'nullable|string|max:255',
            'txtIcon'           => 'required|string|max:50',
        ],[
            'name.unique'   => 'La especialidad ingresada ya se encuentra registrada.'
        ]);

        $objReturn = new ActionReturn('dashboard/specialties/edit/'.$request->hddIdSpecialty, 'dashboard/specialties');
        $objSpecialty    = SpecialityType::where('id', $request->hddIdSpecialty)->first();

        if(!is_null($objSpecialty)) {
            try {
                $objSpecialty->name         = $request->name;
                $objSpecialty->description  = $request->txtDescription;
                $objSpecialty->class_icon   = $request->txtIcon;

                if($objSpecialty->save()) {
                    $objReturn->setResult(true, Messages::SPECIALTY_EDIT_TITLE, Messages::SPECIALTY_EDIT_MESSAGE);
                } else {
                    $objReturn->setResult(false, Errors::SPECIALTY_EDIT_02_TITLE, Errors::SPECIALTY_EDIT_02_MESSAGE);
                }
            } catch(Exception $exception) {
                $objReturn->setResult(false, Errors::getErrors($exception->getCode())['title'], Errors::getErrors($exception->getCode())['message']);
            }
        } else {
            $objReturn->setResult(false, Errors::SPECIALTY_EDIT_01_TITLE, Errors::SPECIALTY_EDIT_01_MESSAGE);
        }

        return $objReturn->getRedirectPath();
    }

    public function delete($idSpeciality){
        $speciality = SpecialityType::where('id', $idSpeciality)->first();

        $specialityTypeUser = SpecialityTypeUser::Where('speciality_type_id', $speciality->id)->get();
        $objReturn = new ActionReturn('dashboard/specialties/delete', 'dashboard/specialties');
        // return $idSpeciality;

        if (sizeof($specialityTypeUser) > 0) {
            $objReturn->setResult(true, Errors::SPECIALTY_DELETE_01_TITLE, Errors::SPECIALTY_DELETE_01_MESSAGE);
        } else {
            try {
                if($speciality->delete()) {
                    $objReturn->setResult(true, Messages::SPECIALTY_DELETE_TITLE, Messages::SPECIALTY_DELETE_MESSAGE);
                } else {
                    $objReturn->setResult(false, Errors::SPECIALTY_DELETE_02_TITLE, Errors::SPECIALTY_DELETE_02_MESSAGE);
                }
            } catch(Exception $exception) {
                $objReturn->setResult(false, Errors::getErrors($exception->getCode())['title'], Errors::getErrors($exception->getCode())['message']);
            }
            
        }

        return $objReturn->getRedirectPath();
    }
}
