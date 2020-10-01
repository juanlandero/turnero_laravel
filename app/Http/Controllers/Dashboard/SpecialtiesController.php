<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Returns\ActionReturn;
use App\Library\Errors;
use App\Library\Messages;
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
}
