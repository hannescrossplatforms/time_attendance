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
            if (!$this->authenticateRequest($request['Authorization'])){
                return $this->unauthorizedResponse();
            }
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
            // If something happened render plain text of the error
            return $this->errorResponse($e->getMessage());
        }
    }

    /* #region VALIDATION */

    public function authenticateRequest($key) {
        $user = \User::where('remember_token', '=', $key)->first();
        if ($user) {
            $vicinity_brands = $user->brands()->where('id','=', 165)->count();
            if ($vicinity_brands != 0)
                return true;
            else
                return false;
        } else
            return false;
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
