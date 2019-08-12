<?php

use DB;
use Input;
use Validator;
use DateTime;
use Illuminate\Support\Facades\Redirect;
use Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Session;
use Route;
use Illuminate\Http\Request;

class ExportController extends Controller {

	public function exportVicinityAsJSON(){

        // $authorization = \Input::all();
        // $authorization = $request->all();
        $authorization = \Request::getContent();

        $data = array();
        $vicinity = \Brand::where('name', '=', 'VICINITY')->firstOrFail();

        $data['vicinity'] = $vicinity;
        $data['vicinity']['users'] = $vicinity->users;
        $data['vicinity']['media'] = $vicinity->medias;
        $data['vicinity']['venues'] = $vicinity->venues;
        $data['vicinity']['isp'] = $vicinity->isp;
        $data['vicinity']['country'] = $vicinity->countrie;
        $data['header'] = $authorization;

        $json = json_encode($data);

        print_r($json);
    }


}


