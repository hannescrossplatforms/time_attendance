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

        $request = \Request::all();
        $authorization = $request['Authorization'];

        if($authorization != "328149511491BC7764417BB3D29C8")
        {
            $resp[] = [
                'status' => "403",
                'message' => "Unauthorized"
            ];

            print_r($resp);
        }
        else {
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


}


