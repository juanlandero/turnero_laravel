<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;

class ClientController extends Controller
{
    public function verifyClient(Request $r){
        $clientNumber = $r['client'];

        $client = Client::where('client_number', $clientNumber)->first();

        if ($client != null) {
            return ['client' => $client];
        } else {
            return ['text' => 'No existe ese número de cliente'];
        }

    }
}
