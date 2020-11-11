<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Library\Returns\ActionReturn;
use App\Library\Errors;
use App\Library\Messages;
use App\Box;

class BoxesController extends Controller
{
    public function index() {
        $lstBoxes = Box::where('is_active', true)->get();
        return View('dashboard.contents.boxes.Index', ['lstBoxes' => $lstBoxes]);
    }

    public function create() {
        return View('dashboard.contents.boxes.Create');
    }

    public function store(Request $request) {
        $request->validate([
            'txtName'           => 'required|string|max:30|unique:boxes,box_name',
        ],[
            'txtName.unique'   => 'La caja ingresada ya se encuentra registrada.'
        ]);

        $objReturn = new ActionReturn('dashboard/boxes/create', 'dashboard/boxes');

        $objBox              = new Box();
        $objBox->box_name    = $request->txtName;
        $objBox->is_active   = true;

        try {
            if($objBox->save()) {
                $objReturn->setResult(true, Messages::BOX_CREATE_TITLE, Messages::BOX_CREATE_MESSAGE);
            } else {
                $objReturn->setResult(false, Errors::BOX_CREATE_01_TITLE, Errors::BOX_CREATE_01_MESSAGE);
            }
        } catch(Exception $exception) {
            $objReturn->setResult(false, Errors::getErrors($exception->getCode())['title'], Errors::getErrors($exception->getCode())['message']);
        }

        return $objReturn->getRedirectPath();
    }

    public function edit($idBox) {
        $objBox = Box::where('id', $idBox)->first();

        if(!is_null($objBox))
            return View('dashboard.contents.boxes.Edit', ['objBox' => $objBox]);

        return redirect()->to('/dashboard/boxes');
    }

    public function update(Request $request) {
        $request->validate([
            'box_name'             => ['required','string','max:30',Rule::unique('boxes')->ignore($request->hddIdBox)],
        ],[
            'box_name.unique'   => 'La caja ingresada ya se encuentra registrada.'
        ]);

        $objReturn      = new ActionReturn('dashboard/boxes/edit/'.$request->hddIdBox, 'dashboard/boxes');
        $objBox          = Box::where('id', $request->hddIdBox)->first();

        if(!is_null($objBox)) {
            try {
                $objBox->box_name = $request->box_name;

                if($objBox->save()) {
                    $objReturn->setResult(true, Messages::BOX_EDIT_TITLE, Messages::BOX_EDIT_MESSAGE);
                } else {
                    $objReturn->setResult(false, Errors::BOX_EDIT_02_TITLE, Errors::BOX_EDIT_02_MESSAGE);
                }
            } catch(Exception $exception) {
                $objReturn->setResult(false, Errors::getErrors($exception->getCode())['title'], Errors::getErrors($exception->getCode())['message']);
            }
        } else {
            $objReturn->setResult(false, Errors::BOX_EDIT_01_TITLE, Errors::BOX_EDIT_01_MESSAGE);
        }

        return $objReturn->getRedirectPath();
    }
}
