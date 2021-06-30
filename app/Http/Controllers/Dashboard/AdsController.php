<?php

namespace App\Http\Controllers\Dashboard;

use App\Ad;
use Exception;
use App\UserOffice;
use App\Library\Errors;
use App\Library\Messages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Library\Returns\ActionReturn;

class AdsController extends Controller
{
    public function index(){
        $officeId = UserOffice::where('user_offices.user_id', Auth::id())->first();

        if(is_null($officeId)) {
            $objAds = Ad::where('is_active', 1)
                        ->orderBy('order', 'asc')
                        ->get();
        } else {
            $objAds = Ad::where([
                            ['office_id', $officeId->office_id],
                            ['is_active', 1]
                        ])
                        ->orderBy('order', 'asc')
                        ->get();
        }

        return view('dashboard.contents.carousel.Index', ['ads' => $objAds]);
    }

    public function create() {
        return view('dashboard.contents.carousel.Create');
    }

    public function store(Request $request){
        try {
            $objReturn  = new ActionReturn('dashboard/ads/create', 'dashboard/ads');

            if(Auth::user()->user_type_id == 1) {
                $objReturn->setResult(false, Errors::AD_CREATE_02_TITLE, Errors::AD_CREATE_02_MESSAGE);
                return $objReturn->getRedirectPath();
            }

            $file       = $request->file('imgAd');
            $extension  = $file->getClientOriginalExtension();
            $fileName   = time() . '_image_carousel.' . $extension;
            $url        = '/carousel/' . $fileName;

            $request->imgAd->storeAs('carousel', $fileName);

            $officeId   = UserOffice::where('user_id', Auth::id())->select('office_id')->first();

            $objAd = new Ad();
            $objAd->name        = $request->txtName;
            $objAd->path        = $url;
            $objAd->order       = $request->intOrder;
            $objAd->is_first    = (($request->intOrder == 1)? "active":"");
            $objAd->duration    = ($request->intDuration*1000);
            $objAd->office_id   = $officeId->office_id;
            $objAd->is_active   = true;

            if( $objAd->save()) {
                $objReturn->setResult(true, Messages::AD_CREATE_TITLE, Messages::AD_CREATE_MESSAGE);
            } else {
                $objReturn->setResult(false, Errors::AD_CREATE_01_TITLE, Errors::AD_CREATE_01_MESSAGE);
            }
        } catch(Exception $exception) {
            $objReturn->setResult(false, Errors::getErrors($exception->getCode())['title'], Errors::getErrors($exception->getCode())['message']);
        }

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
