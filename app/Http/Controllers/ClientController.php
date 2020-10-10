<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;

class ClientController extends Controller
{
    public function verifyClient(Request $r){
        $clientNumber = $r['client'];

        $client = Client::where([
                                ['client_number', $clientNumber],
                                ['is_active', 1]
                            ])
                            ->first();

        if ($client != null) {
            return ['success' => 'true', 'client' => $client];
        } else {
            return ['success' => 'false', 'text' => 'No existe ese número de cliente'];
        }

    }
}
