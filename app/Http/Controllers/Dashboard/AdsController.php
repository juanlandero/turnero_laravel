<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Library\Returns\ActionReturn;
use App\Library\Errors;
use App\Library\Messages;
use App\UserOffice;
use App\Ad;

class AdsController extends Controller
{
    public function index(){
        $officeId = UserOffice::where('user_offices.user_id', Auth::id())->first();

        $objAds = Ad::where([
            ['office_id', $officeId->office_id],
            ['is_active', 1]
        ])
        ->orderBy('order', 'asc')
        ->get();

        return view('dashboard.contents.carousel.Index', ['ads' => $objAds]);
    }

    public function create(){
        return view('dashboard.contents.carousel.Create');
    }

    public function store(Request $request){
        // $request->validate([
        //     'txtName'           => 'required|string|max:255',
        //     'txtPhone'          => 'nullable|string|max:50',
        //     'txtAddress'        => 'required|string',
        //     'txtChannel'        => 'required|string|unique:offices,channel|max:80',
        //     'txtOfficeKey'      => 'required|string|unique:offices,office_key|max:20',
        //     'cmbMunicipality'   => 'required|integer|exists:municipalities,id'
        // ],[
        //     'txtChannel.unique'     => 'El canal ingresado ya pertenece a otra sucursal.',
        //     'txtOfficeKey.unique'   => 'Este código de sucursal no está disponible.'
        // ]);

        /* $file = $request->imgAd;
        $path = $file->store('carousel'); */

        $file       = $request->file('imgAd');
        $extension  = $file->getClientOriginalExtension();
        $fileName   = time() . '_image_carousel.' . $extension;
        $url        = '/carousel/' . $fileName;

        $request->imgAd->storeAs('carousel', $fileName);

        $officeId = UserOffice::where('user_id', Auth::id())->select('office_id')->first();

        $objReturn = new ActionReturn('dashboard/ads/create', 'dashboard/ads');


        $objAd = new Ad();
        $objAd->name        = $request->txtName;
        $objAd->path        = $url;
        $objAd->order       = $request->intOrder;
        $objAd->is_first    = (($request->intOrder == 1)? "active":"");
        $objAd->duration    = ($request->intDuration*1000);
        $objAd->office_id   = $officeId->office_id;
        $objAd->is_active   = true;

        try {
            if( $objAd->save()) {
                $objReturn->setResult(true, Messages::AD_CREATE_TITLE, Messages::AD_CREATE_MESSAGE);
            } else {
                $objReturn->setResult(false, Errors::AD_CREATE_01_TITLE, Errors::AD_CREATE_01_MESSAGE);
            }
        } catch(Exception $exception) {
            $objReturn->setResult(false, Errors::getErrors($exception->getCode())['title'], Errors::getErrors($exception->getCode())['message']);
        }

        // return redirect()->route('ads.index');
        return $objReturn->getRedirectPath();
    }

    public function delete($idAd){
        $adDelete = Ad::find($idAd);
        $objReturn = new ActionReturn('dashboard/ads/delete', 'dashboard/ads');
      
        try {
            if($adDelete->delete()) {
                $objReturn->setResult(true, Messages::AD_DELETE_TITLE, Messages::AD_DELETE_MESSAGE);
            } else {
                $objReturn->setResult(false, Errors::AD_DELETE_02_TITLE, Errors::AD_DELETE_02_MESSAGE);
            }
        } catch(Exception $exception) {
            $objReturn->setResult(false, Errors::getErrors($exception->getCode())['title'], Errors::getErrors($exception->getCode())['message']);
        }
    
        return $objReturn->getRedirectPath();
    }
}
