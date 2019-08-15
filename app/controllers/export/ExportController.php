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

    public function venues() {
        try {
            // Set data variables
            $data = array();
            $venue_array = array();
            $venue_json = array();
            // Validate the API key
            $request = \Request::all();
            if (!$this->authenticateRequest($request['Authorization']))
                return $this->unauthorizedResponse();
            // Get all Vicinity venues
            $venues = \Brand::where('name', '=', 'VICINITY')->firstOrFail()->venues;
            // Loop through each venue
            foreach ($venues as $venue) {
                // Build JSON data as $data
                $venue_json["id"] = $venue->id;
                $venue_json["name"] = $venue->sitename;
                array_push($venue_array, $venue_json);
             }
             $data['venues']  = $venue_array;
             // Send JSON
             return $this->successResponse($data);
        }
        catch (Exception $e) {
            // If something happened render default error JSON with exception message
            return $this->errorResponse($e->getMessage());
        }
    }

    public function rollup($id, $from, $to) {
        try {
            // Set data variables
            $data = array();
            // Validate the API key
            $request = \Request::all();
            if (!$this->authenticateRequest($request['Authorization']))
                return $this->unauthorizedResponse();
            // Validate the id
            $venue = \Venue::where('id', '=', $id)->first();
            if (!$venue)
                return $this->notFoundResponse('Venue with id '.$id.' not found');
            // http://tracks03.hipzone.co.za/aggregate/1376/custom/2019-08-01/2019-08-31
            $data['data'] = json_decode(file_get_contents('http://tracks03.hipzone.co.za/aggregate/'.$id.'/custom/'.$from.'/'.$to));
            return $this->successResponse($data);
        }
        catch (Exception $e) {
            // If something happened render default error JSON with exception message
            return $this->errorResponse($e->getMessage());
        }
    }

    /* #region VALIDATION */

    public function authenticateRequest($key) {
        $user = \User::where('remember_token', '=', $key)->first();
        if ($user) {
            $vicinity_brands = $user->brands()->where('brands.id','=', 165)->count();
            if ($vicinity_brands != 0) 
                return true;
            else 
                return false;
        } else 
            return false;
    }

    public function validateParameters($params) {

    }

    /* #endregion */

    /* #region RESPONSES */

    public function successResponse($content) {
        $content['status'] = 'success';
        $content['code'] = 200;
        return $this->respondJSON($content);
    }

    public function unauthorizedResponse() {
        $error = array();
        $error['code'] = 403;
        $error['status'] = 'failed';
        $error['message'] = 'API key is missing or is not authorized to access this data';
        return $this->respondJSON($error);
    }

    public function notFoundResponse($message) {
        $error = array();
        $error['code'] = 404;
        $error['status'] = 'not_found';
        $error['message'] = $message;
        return $this->respondJSON($error);
    }

    public function errorResponse($message) {
        $error = array();
        $error['code'] = 500;
        $error['status'] = 'failed';
        $error['message'] = $message;
        return $this->respondJSON($error);
    }

    /* #endregion */

    public function respondJSON($obj) {
        $response = Response::json($obj);
        $response->header('Content-Type', 'application/json;charset=utf-8');
        return $response;
    }


}
