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

        if($authorization != "m2kiy7j6BwnMTzLm4pxwN2tjcHaxLaA0pjB0VfELSDVkIfawym27TPozVMDg")
        {
            $resp[] = [
                'status' => "403",
                'message' => "Unauthorized"
            ];

            print_r($resp);
        }
        else {

            $user = \User::where('remember_token', '=', $authorization)->firstOrFail();

            $sensor = new \Sensor();
            $venue = new \Venue();

            $data['venuesasdf'] = $venue->getVenuesForUser('hipjam', null, null, null, "active", null, $user);





            $data = array();
            $vicinity = \Brand::where('name', '=', 'VICINITY')->firstOrFail();

            $data['vicinity'] = $vicinity;
            $data['vicinity']['users'] = $vicinity->users;
            $data['vicinity']['media'] = $vicinity->medias;
            $data['vicinity']['venues'] = $vicinity->venues;
            $data['vicinity']['isp'] = $vicinity->isp;
            $data['vicinity']['country'] = $vicinity->countrie;
            $data['user'] = $user;

            $json = json_encode($data);

            print_r($json);
        }


    }


}



// @foreach($data['venues'] as $venue)
// <tr>
//     <td id="venue{{$venue->id}}" idval="{{$venue->id}}" class="sensorlist">{{$venue->sitename}}
//         <ol id="sensors{{$venue->id}}"></ol>
//     </td>
//     <td id="status{{$venue->id}}" class="venuelist-{{$data['status'][$venue->id]}}" idval="{{$venue->id}}">
//         {{$data['status'][$venue->id]}}
//     </td>

// </tr>
// @endforeach
