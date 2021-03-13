<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Request\OfficeValidator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Library\Returns\ActionReturn;
use App\Library\Errors;
use App\Library\Messages;
use App\Municipality;
use App\UserOffice;
use App\Office;

class OfficeController extends Controller
{
    /* protected $validator;

    public function __construct(OfficeValidator $validator)
    {
        $this->validator = $validator;
    } */

    public function index() {
        $lstOffices = Office::where('is_active', true)->get();
        return View('dashboard.contents.offices.Index', ['lstOffices' => $lstOffices]);
    }

    public function create() {
        $lstMunicipalities = Municipality::orderBy('name', 'ASC')->get();
        return View('dashboard.contents.offices.Create', ['lstMunicipalities' => $lstMunicipalities]);
    }

    public function store(Request $request) {
        $request->validate([
            'txtName'           => 'required|string|max:255',
            'txtPhone'          => 'nullable|string|max:50',
            'txtAddress'        => 'required|string',
            'txtChannel'        => 'required|string|unique:offices,menu_channel|max:80',
            'txtOfficeKey'      => 'required|string|unique:offices,office_key|max:20',
            'cmbMunicipality'   => 'required|integer|exists:municipalities,id'
        ],[
            'txtChannel.unique'     => 'El canal ingresado ya pertenece a otra sucursal.',
            'txtOfficeKey.unique'   => 'Este código de sucursal no está disponible.'
        ]);

        $objReturn = new ActionReturn('dashboard/offices/create', 'dashboard/offices');

        $objOffice                  = new Office();
        $objOffice->name            = $request->txtName;
        $objOffice->address         = $request->txtAddress;
        $objOffice->phone           = $request->txtPhone;
        $objOffice->menu_channel    = "menu-".trim($request->txtChannel)."-".Str::random(4);
        $objOffice->panel_channel   = Str::random(4)."-".trim($request->txtChannel)."-panel";
        $objOffice->user_channel    = "user@".trim($request->txtChannel)."-".Str::random(4);
        $objOffice->office_key      = $request->txtOfficeKey;
        $objOffice->municipality_id = $request->cmbMunicipality;
        $objOffice->is_active       = true;

        try {
            if($objOffice->save()) {
                $objReturn->setResult(true, Messages::OFFICE_CREATE_TITLE, Messages::OFFICE_CREATE_MESSAGE);
            } else {
                $objReturn->setResult(false, Errors::OFFICE_CREATE_01_TITLE, Errors::OFFICE_CREATE_01_MESSAGE);
            }
        } catch(Exception $exception) {
            $objReturn->setResult(false, Errors::getErrors($exception->getCode())['title'], Errors::getErrors($exception->getCode())['message']);
        }

        return $objReturn->getRedirectPath();
    }

    public function edit($idOffice) {
        $objOffice = Office::where('id', $idOffice)->first();

        if(!is_null($objOffice)) {
            $lstMunicipalities = Municipality::orderBy('name', 'ASC')->get();
            return View('dashboard.contents.offices.Edit', ['lstMunicipalities' => $lstMunicipalities, 'objOffice' => $objOffice]);
        }

        return redirect()->to('/dashboard/offices');
    }

    public function update(Request $request) {
        $request->validate([
            'txtName'           => 'required|string|max:255',
            'txtPhone'          => 'nullable|string|max:50',
            'txtAddress'        => 'required|string',
            'office_key'        => ['required','string','max:20',Rule::unique('offices')->ignore($request->hddIdOffice)],
            'cmbMunicipality'   => 'required|integer|exists:municipalities,id'
        ],[
            'txtChannel.unique'     => 'El canal ingresado ya pertenece a otra sucursal.',
            'txtOfficeKey.unique'   => 'Este código de sucursal no está disponible.'
        ]);

        $objReturn = new ActionReturn('dashboard/offices/edit/'.$request->hddIdOffice, 'dashboard/offices');
        $objOffice = Office::where('id', $request->hddIdOffice)->first();

        if(!is_null($objOffice)) {
            $objOffice->name            = $request->txtName;
            $objOffice->address         = $request->txtAddress;
            $objOffice->phone           = $request->txtPhone;
            $objOffice->office_key      = $request->office_key;
            $objOffice->municipality_id = $request->cmbMunicipality;
    
            try {
                if($objOffice->save()) {
                    $objReturn->setResult(true, Messages::OFFICE_EDIT_TITLE, Messages::OFFICE_EDIT_MESSAGE);
                } else {
                    $objReturn->setResult(false, Errors::OFFICE_EDIT_02_TITLE, Errors::OFFICE_EDIT_02_MESSAGE);
                }
            } catch(Exception $exception) {
                $objReturn->setResult(false, Errors::getErrors($exception->getCode())['title'], Errors::getErrors($exception->getCode())['message']);
            }
        } else {
            $objReturn->setResult(false, Errors::OFFICE_EDIT_01_TITLE, Errors::OFFICE_EDIT_01_MESSAGE);
        }

        return $objReturn->getRedirectPath();
    }

    public function delete($idOffice){
        $office = Office::where('id', $idOffice)->first();

        $userOffice = UserOffice::Where('office_id', $office->id)->get();
        $objReturn = new ActionReturn('dashboard/offices/delete', 'dashboard/offices');

        if (sizeof($userOffice) > 0) {
            $office->is_active = false;

            try {
                if($office->save()) {
                    $objReturn->setResult(true, Messages::OFFICE_DELETE_TITLE, Messages::OFFICE_DELETE_MESSAGE);
                } else {
                    $objReturn->setResult(false, Errors::OFFICE_DELETE_03_TITLE, Errors::OFFICE_DELETE_03_MESSAGE);
                }
            } catch(Exception $exception) {
                $objReturn->setResult(false, Errors::getErrors($exception->getCode())['title'], Errors::getErrors($exception->getCode())['message']);
            }
        } else {
            try {
                if($office->delete()) {
                    $objReturn->setResult(true, Messages::OFFICE_DELETE_TITLE, Messages::OFFICE_DELETE_MESSAGE);
                } else {
                    $objReturn->setResult(false, Errors::OFFICE_DELETE_03_TITLE, Errors::OFFICE_DELETE_03_MESSAGE);
                }
            } catch(Exception $exception) {
                $objReturn->setResult(false, Errors::getErrors($exception->getCode())['title'], Errors::getErrors($exception->getCode())['message']);
            }
            
        }

        return $objReturn->getRedirectPath();
    }
    
}
