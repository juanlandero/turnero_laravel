<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Returns\ActionReturn;
use App\Library\Errors;
use App\Library\Messages;
use App\User;

class UserSupervisorController extends Controller
{
    public function index() {
        $lstUsers = User::where('is_active', true)->where('user_type_id', 2)->get();
        return View('dashboard.contents.users.supervisors.Index', ['lstUsers' => $lstUsers]);
    }

    public function create() {
        return View('dashboard.contents.users.supervisors.Create');
    }
}
