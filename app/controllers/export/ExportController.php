<?php

namespace hippnp;
use DB;
use Input;
use Validator;
use DateTime;
use Illuminate\Support\Facades\Redirect;
use Response;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Session;
use Route;
use \EngagePicknPayBeacon;

class ExportController extends \BaseController {

	public static function exportVicinityAsJSON(){

        // brand where vicinity

        $data = array();
        $vicinity = \Brand::where('name', '=', 'VICINITY')->firstOrFail();

        $data['vicinity'] = $vicinity;



        // $allCategories = \Picknpay::fetchAllCategories($period, $start, $end);

        $json = json_encode($data);
    }


}


