<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Library\Returns\ActionReturn;
use App\Http\Controllers\Controller;
use App\Library\Messages;
use App\Library\Errors;
use App\UserOffice;
use App\Client;
use App\Shift;

class ClientsController extends Controller
{
    public function index(){
        // $officeId = UserOffice::where('user_id', Auth::id())->first();
        $objClients = Client::join('offices', 'clients.office_register_id', 'offices.id')
                            ->select(
                                'clients.id',
                                'clients.name',
                                'clients.first_name',
                                'clients.second_name',
                                'clients.client_number',
                                'clients.sex',
                                'clients.created_at',
                                'offices.name as office'
                            )
                            ->where('clients.is_active', true)
                            ->get();
        return view('dashboard.contents.clients.Index', ['lstClients' => $objClients]);
    }

    public function create(){
        return view('dashboard.contents.clients.Create');
    }

    public function store(Request $request) {
        $request->validate([
            'txtName'           => 'required|string|max:255',
            'txtFirstName'      => 'required|string|max:255',
            'txtSecondName'     => 'required|string|max:255',
            'raSex'             => 'required',
        ],[
            'txtNumberClient.unique' => 'El número de cliente ingresado ya se encuentra registrado.'
        ]);

        $objReturn = new ActionReturn('dashboard/clients/create', 'dashboard/clients');
        $office = UserOffice::where('user_id', Auth::id())->first();

        $objClient                          = new Client();
        $objClient->name                    = $request->txtName;
        $objClient->first_name              = $request->txtFirstName;
        $objClient->second_name             = $request->txtSecondName;
        $objClient->client_number           = $request->txtNumberClient;
        $objClient->sex                     = $request->raSex;
        $objClient->office_register_id      = $office->office_id;
        $objClient->is_active               = true;

        try {
            if($objClient->save()) {
                $objReturn->setResult(true, Messages::CLIENT_CREATE_TITLE, Messages::CLIENT_CREATE_MESSAGE);
            } else {
                $objReturn->setResult(false, Errors::CLIENT_CREATE_01_TITLE, Errors::CLIENT_CREATE_01_MESSAGE);
            }
        } catch(Exception $exception) {
            $objReturn->setResult(false, Errors::getErrors($exception->getCode())['title'], Errors::getErrors($exception->getCode())['message']);
        }

        return $objReturn->getRedirectPath();
    }

    public function edit($idClient) {
        $objClient = Client::join('offices', 'clients.office_register_id', 'offices.id')
                            ->select(
                                'clients.id',
                                'clients.name',
                                'clients.first_name',
                                'clients.second_name',
                                'clients.client_number',
                                'clients.sex',
                                'clients.created_at',
                                'offices.name as office'
                            )
                            ->where('clients.id', $idClient)
                            ->first();

        if(!is_null($objClient))
            return View('dashboard.contents.clients.Edit', ['objClient' => $objClient]);

        return redirect()->to('/dashboard/clients');
    }

    public function update(Request $request) {
        $request->validate([
            'txtName'           => 'required|string|max:255',
            'txtFirstName'      => 'required|string|max:255',
            'txtSecondName'     => 'required|string|max:255',
            'raSex'             => 'required',
        ],[
            'txtNumberClient.unique' => 'El número de cliente ingresado ya se encuentra registrado.'
        ]);

        $objReturn = new ActionReturn('dashboard/clients/create', 'dashboard/clients');
        $objClient   = Client::where('id', $request->idClient)->first();

        if(!is_null($objClient)) {
            try {
                $objClient->name                    = $request->txtName;
                $objClient->first_name              = $request->txtFirstName;
                $objClient->second_name             = $request->txtSecondName;
                $objClient->client_number           = $request->txtNumberClient;
                $objClient->sex                     = $request->raSex;

                if($objClient->save()) {
                    $objReturn->setResult(true, Messages::CLIENT_EDIT_TITLE, Messages::CLIENT_EDIT_MESSAGE);
                } else {
                    $objReturn->setResult(false, Errors::CLIENT_EDIT_02_TITLE, Errors::CLIENT_EDIT_02_MESSAGE);
                }
            } catch(Exception $exception) {
                $objReturn->setResult(false, Errors::getErrors($exception->getCode())['title'], Errors::getErrors($exception->getCode())['message']);
            }
        } else {
            $objReturn->setResult(false, Errors::CLIENT_EDIT_01_TITLE, Errors::CLIENT_EDIT_01_TITLE);
        }

        return $objReturn->getRedirectPath();
    }

    
    public function delete($idClient){
        $client = Client::where('id', $idClient)->first();

        $shifts = Shift::Where('number_client', $client->client_number)->get();
        $objReturn = new ActionReturn('dashboard/clients/delete', 'dashboard/clients');

        if (sizeof($shifts) > 0) {
            $client->is_active = false;

            try {
                if($client->save()) {
                    $objReturn->setResult(true, Messages::CLIENT_DELETE_TITLE, Messages::CLIENT_DELETE_MESSAGE);
                } else {
                    $objReturn->setResult(false, Errors::CLIENT_DELETE_03_TITLE, Errors::CLIENT_DELETE_03_MESSAGE);
                }
            } catch(Exception $exception) {
                $objReturn->setResult(false, Errors::getErrors($exception->getCode())['title'], Errors::getErrors($exception->getCode())['message']);
            }
        } else {
            try {
                if($client->delete()) {
                    $objReturn->setResult(true, Messages::CLIENT_DELETE_TITLE, Messages::CLIENT_DELETE_MESSAGE);
                } else {
                    $objReturn->setResult(false, Errors::CLIENT_DELETE_03_TITLE, Errors::CLIENT_DELETE_03_MESSAGE);
                }
            } catch(Exception $exception) {
                $objReturn->setResult(false, Errors::getErrors($exception->getCode())['title'], Errors::getErrors($exception->getCode())['message']);
            }
            
        }

        return $objReturn->getRedirectPath();
    }
}
