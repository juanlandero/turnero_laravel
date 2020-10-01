<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\ShiftScreenMsg;

class MessagesController extends Controller
{
    public function generatorToScreen(){

        $text = "hooka";
        $channel = "test";


        event(new ShiftScreenMsg($text, $channel));


        return true;
    }
}
