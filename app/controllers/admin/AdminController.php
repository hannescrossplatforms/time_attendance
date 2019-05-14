<?php

namespace admin;
use Input;
use DB;


// use BaseController;

class AdminController extends \BaseController {

    public function admin_showdashboard()
    {

        $data = array();
        $data['currentMenuItem'] = "Dashboard";


        return \View::make('admin.showdashboard')->with('data', $data);
    }

    public function admin_showrolesandpermissions()
    {

        $data = array();
        $data['currentMenuItem'] = "Roles and Permissions";


        return \View::make('admin.showrolesandpermissions')->with('data', $data);
    }

    // public function admin_showbrands()
    // {

    //     $data = array();
    //     $data['currentMenuItem'] = "Brands";


    //     return \View::make('admin.showbrands')->with('data', $data);
    // }

    ////////////////////////////////////////////////////////////
    // BEGIN BRANDS
    ////////////////////////////////////////////////////////////

    public function admin_showbrands($json = null)
    {
        error_log("showBrands");

        $data = array();
        $data['currentMenuItem'] = "Brand Management";
        $brand = new \Brand();
        $brands = $brand->getBrandsForUser(\Auth::user()->id);

        $data['brandsStruct'] = $brands;
        $brandsJason = json_encode($brands);
        $data['brandsJason'] = $brandsJason;

        $data['brands'] = $brands;
        // print_r($brands);
        $data['currentMenuItem'] = "Brand Management";

        if($json) {
            error_log("showDashboard : returning json" );
            return \Response::json($brandsJason);

        } else {
            return \View::make('admin.showbrands')->with('data', $data);
            
        }
    }


 public function admin_addBrand()
    {
        error_log('addBrand xxx');
        $data = array();
        $data['currentMenuItem'] = "Brand Management";
        $data['brand'] = array();
        $data['edit'] = false;

        $countries = \Countrie::All();
        $data['allcountries'] = $countries;

        $isps = \Isp::All();
        $data['allisps'] = $isps;

        $data['currentMenuItem'] = "Brand Management";
        $data['brand'] = new \Brand();

        return \View::make('admin.addbrand')->with('data', $data);
    }

    public function admin_addBrandSave()
    {

        error_log('addBrandSave');
        $brand = new \Brand();
        $input = \Input::all();

        $name = \Input::get("name");
        $exists = \Brand::where("name", "like", $name)->first();
        if(! is_null($exists)) {
            $exists->forceDelete();
        } 

        $messages = array(
            'name.required' => 'The brand name is required',
            'name.unique' => 'The brand name is already taken',
            'name.alpha_num_dash_spaces' => 'The full name can only contain letters, numbers, dashes and spaces',
            'code.required' => 'The brand code is required',
            'code.unique' => 'The brand code is already taken',
            'code.size' => 'The brand code must be 6 characters in length',
        );

        $rules = array(
            'name'          => 'required|alpha_num_dash_spaces|unique:brands',  
            'code'          => 'required|unique:brands|size:6',  
        );

        $validator = \Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return \Redirect::to('admin_addbrand')->withErrors($validator)->withInput();

        } else {        

            $brand = $this->constructBrandRecord($brand, $input, true);
            $brand->save();
            $user = \Auth::user();
            $user->brands()->attach($brand->id);

        }

        return \Redirect::route('admin_showbrands');
    }

    public function admin_editBrand($id)
    {
        error_log('editBrand');
        $data = array();
        $data['currentMenuItem'] = "Brand Management";
        $data['brand'] = \Brand::find($id);

        if($data['brand']->hipwifi) $data['brand']->hipwifi = "checked";
        if($data['brand']->hiprm) $data['brand']->hiprm = "checked";
        if($data['brand']->hipjam) $data['brand']->hipjam = "checked";
        if($data['brand']->hipengage) $data['brand']->hipengage = "checked";
        if($data['brand']->userdatabtn) $data['brand']->userdatabtn = "checked";
        if($data['brand']->logindatabtn) $data['brand']->logindatabtn = "checked";

        $countries = \Countrie::All();
        $data['allcountries'] = $countries;

        $data['currentMenuItem'] = "Brand Management";
        $data['edit'] = true;

        return \View::make('admin.addbrand')->with('data', $data);
    }

    public function admin_editBrandSave()
    {
        $id = \Input::get('id');
        $brand = \Brand::find($id);

        $input = \Input::all();
        $input["servercount"]= sizeof(\Input::get('server_ids'));

        $messages = array(
            'name.required' => 'The brand name is required',
            'name.unique' => 'The brand name is already taken',
            'name.alpha_num_dash_spaces' => 'The full name can only contain letters, numbers, dashes and spaces',
            'code.required' => 'The brand code is required',
            'code.unique' => 'The brand code is already taken',
            'code.size' => 'The brand code must be 6 characters in length',
        );

        $rules = array(
            'name'      => 'required|alpha_num_dash_spaces|unique:brands,name,'.$id,  
            'code'      => 'required|size:6|unique:brands,code,'.$id,   
            // 'servercount'    => 'array_not_null',                       
        );

        $validator = \Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();

            return \Redirect::to('admin_editbrand/'.$id)->withErrors($validator)->withInput();

        } else { 

            $brand = $this->constructBrandRecord($brand, $input, false);
            $brand->save();

        }



        // Update brand status in hipengage
        $engagebrand = \Engagebrand::where('code', 'like', $brand->code)->first();

        if($engagebrand) {
            $engagebrand->active = $brand->hipengage;
        } else {
            $brand->auth_token = exec("uuidgen -r");
            $engagebrand = new \Engagebrand();
            $engagebrand->name = $brand->name;
            $engagebrand->code = $brand->code;
            $engagebrand->auth_token = $brand->auth_token;
            $engagebrand->active = $brand->hipengage;
        }
        
        $engagebrand->save();

        return \Redirect::route('admin_showbrands');
    }

    public function admin_deleteBrand($id)
    {
        error_log("deleteBrand");
        $brand = \Brand::find($id);
        $connection = $brand->remotedb->dbconnection;

        if($brand) {

            $brand->users()->detach();

            $brand->medias()->delete();

            $brand->venues()->delete();

            \Engagebrand::where('code', 'like', $brand->code)->delete();

            $brand->delete(); 

            // Delete the nastypes and naslookups in hipwifi
            \DB::connection($connection)->table("nastype")->where('type', 'like' , "%" . $brand->code)->delete();
            \DB::connection($connection)->table("naslookup")->where('location', 'like' , "___" . $brand->code . "%")->delete();
        }

        return \Redirect::route('admin_showbrands', ['json' => 1]);
    }

        public function constructBrandRecord($brand, $input, $newrecord)
    {

        if($newrecord) {
            $brand->isp_id = \Input::get('isp_id');
            $brand->remotedb_id = \Input::get('remotedb_id');
        }

        if(!$brand->auth_token) {
            $brand->auth_token = exec("uuidgen -r");
        }
        
        error_log("constructBrandRecord : auth_token : " . $brand->auth_token);

        $brand->name = \Input::get('name');
        $brand->code = \Input::get('code');
        $brand->countrie_id = \Input::get('countrie_id');

        if(\Input::get('hipwifi') == "on") { $brand->hipwifi = 1; } else { $brand->hipwifi = 0; }
        if(\Input::get('hiprm') == "on") { $brand->hiprm = 1; } else { $brand->hiprm = 0; }
        if(\Input::get('hipjam') == "on") { $brand->hipjam = 1; } else { $brand->hipjam = 0; }
        if(\Input::get('hipengage') == "on") { $brand->hipengage = 1; } else { $brand->hipengage = 0; }
        if(\Input::get('userdatabtn') == "on") { $brand->userdatabtn = 1; } else { $brand->userdatabtn = 0; }
        if(\Input::get('logindatabtn') == "on") { $brand->logindatabtn = 1; } else { $brand->logindatabtn = 0; }

        return $brand;
    }

    //////////////////////////////////////////////////////////////////
    // END BRANDS
    //////////////////////////////////////////////////////////////////


    public function admin_showvenues($json = null)
    {

        // $data = array();
        // $data['currentMenuItem'] = "Venues";

        error_log("admin_showvenues");

        $data = array();
        $data['currentMenuItem'] = "Venue Management";
        // $venues = \Venue::all();
        $venue = new \Venue();
        $venues = $venue->getVenuesForUser(null, 1);
        // error_log("admin_showvenues : " . print_r($venues, 1));

        $data['venuesJason'] = json_encode($venues);
        // error_log("admin_showvenues : venuesJason = " . $data['venuesJason']);

        $data['currentMenuItem'] = "Venue Management";

        if($json) {
            error_log("admin_showvenues : returning json" );
            return \Response::json($data['venuesJason']);

        } else {
            error_log("showDashboard : returning NON json" );
            return \View::make('admin.showvenues')->with('data', $data);
            
        }

    }




    public function admin_addVenue()
    {
        error_log("admin_addVenue");

        // $response = file_get_contents('http://dev.doteleven.co/kauai_seapoint');
        // error_log("TESTING API : $response");

        $data = array();
        $data['currentMenuItem'] = "Venue Management";
        $data['edit'] = false;
        $data['adminssidpresent'] = 0; 
        $data['numadminwifi'] = 0;
        $data['submitbutton'] = '';
        $data['bypass'] = array();
        $data['adminssid1'] = '';
        $data['adminssid2'] = '';
        $data['adminssid3'] = '';

        $data['venue'] = new \Venue();

        $othervars = array("name" => "Other", "selected" => "selected=\"selected\"");
        $mikvars = array("name" => "Mikrotik", "selected" => "");
        $data["device_types"] = array($othervars, $mikvars);

        $countries = \Countrie::All();
        $data['allcountries'] = $countries;

        $isps = \Isp::All();
        $data['allisps'] = $isps;

        $brand = new \Brand();
        $data['brands'] = $brand->getBrandsForProduct('hipwifi');

        $data['ap_active_checked'] = "checked";
        $data['ap_inactive_checked'] = "";

        return \View::make('admin.addvenue')->with('data', $data);
    }

    public function admin_addVenueSave()
    {
        $input = \Input::all();
        $brand_id = \Input::get('brand_id');
        $brand_name = \Brand::find($brand_id)->name;
        $sitename = \Input::get('sitename');
        $sitename = $brand_name . " " . $sitename;
        $macaddress = \Input::get('macaddress');
        $input['sitename'] = $sitename;
        
        $sitename_exists = \Venue::where("sitename", "like", $sitename)->first();
        if(! is_null($sitename_exists)) {
            $sitename_exists->forceDelete();
        } 

        $messages = array('', );
        $rules = array(
            'sitename'       => 'required|alpha_num_dash_spaces|unique:venues',                        
        );

        $validator = \Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();

            return \Redirect::to('admin_addvenue')->withErrors($validator)->withInput();

        } else {
            $utils = new \Utils();
            
            $venue = new \Venue();
            $venue->sitename = $input['sitename'];
            $venue->location = $input['location'];
            $venue->countrie_id = $input['countrie_id'];
            $venue->province_id = $input['province_id'];
            $venue->citie_id = $input['citie_id'];
            $venue->brand_id = $input['brand_id'];
            $venue->isp_id = \Brand::find($venue->brand_id)->isp_id;
            $venue->latitude = $input['latitude'];
            $venue->longitude = $input['longitude'];
            $venue->address = $input['address'];
            $venue->contact = $input['contact'];
            $venue->notes = $input['notes'];
            $venue->save();

        }

        return \Redirect::route('admin_showvenues');
    }


    public function admin_editVenue($id)
    {
error_log("admin_editVenue 10");
        $data = array();
        $data['currentMenuItem'] = "Venue Management";
        $data['edit'] = true;
        $data['venue'] = \Venue::find($id);
 
        $data['submitbutton'] = 'on';

        $data['old_sitename'] = $data['venue']["sitename"];
        $data['venue']["sitename"] = preg_replace("/(.*) (.*$)/", "$2", $data['venue']["sitename"]); 
        foreach($data['venue'] as $key => $value) { error_log("TTT : $key => $value"); };
        
        $encoded = json_encode($data);
        return \View::make('admin.addvenue')->with('data', $data);
    }

public function admin_editVenueSave()
    {
error_log("admin_editVenueSave 10");

        $input = \Input::all();
        $brand_id = \Input::get('brand_id');
        $brand_name = \Brand::find($brand_id)->name;
        $sitename = \Input::get('sitename');
        $sitename = $brand_name . " " . $sitename;
        $input['sitename'] = $sitename;
        $macaddress = \Input::get('macaddress');
        $connection = \Brand::find($brand_id)->remotedb->dbconnection;
        
        $utils = new \Utils();
        
        $id = \Input::get('id');
        $venue = \Venue::find($id);
        $venue->sitename = $input['sitename'];
        $venue->location = $input['location'];
        $venue->countrie_id = $input['countrie_id'];
        $venue->province_id = $input['province_id'];
        $venue->citie_id = $input['citie_id'];
        $venue->brand_id = $input['brand_id'];
        $venue->isp_id = \Brand::find($venue->brand_id)->isp_id;
        $venue->latitude = $input['latitude'];
        $venue->longitude = $input['longitude'];
        $venue->address = $input['address'];
        $venue->contact = $input['contact'];
        $venue->notes = $input['notes'];
        $venue->save();

        return \Redirect::route('admin_showvenues');
    }

    public function admin_deleteVenue($id)
    {
        error_log("admin_deleteVenue id = $id");
        $venue = \Venue::find($id);
        $brand_id = $venue->brand_id;
        $remotedb_id = \Brand::find($brand_id)->remotedb_id;
        $media = new \Media();

        if($venue) {
            error_log("admin_deleteVenue - deleting venue - id = $id");
            $venue->delete();  
            $venue->deleteVenueInRadius($venue, $remotedb_id);
            error_log("admin_deleteVenue 20");
            $media->where("venue_id", "=", $id)->delete();
            error_log("admin_deleteVenue 30");
            $mikrotik = new \Mikrotik();
            $mikrotik->deleteVenue($venue);
        }

        return \Redirect::route('admin_showvenues', ['json' => 1]);
    }
    
///////////////////////////////////////////////////////////////

// Name: roleedit

// Purpose:  To load edit role page and fetch all details for the edit role page.

// It fetch all details of a particular role from database and load edit role view page

// Created at 22-11-2016 by Anusha

// Last updated at 06-12-2016 by Prajeesh

//////////////////////////////////////////////////////////////

    public function roleedit( $roleId = '' )
    {
//        echo $roleId;die("here");
        $data = array();
        $data['currentMenuItem'] = "Venues";
        $data['product'] = \Product::get(); 
        //$data['permission'] =  \Permission::get();
        $data['roleDetails'] =  \Role::where('id',$roleId)->get();    
            
        $data['permission'] = DB::table('permissions')->
            whereNotIn('id' , function( $permission ) use ( $roleId ) { 
            $permission->select( 'permission_id' )->from( 'permission_role' )->
            where( 'role_id' , '=' , $roleId );
        })->get();  
        
        $data['added_permission'] = DB::table('permissions')->
            whereIn('id' , function( $permissionrole ) use ( $roleId ) { 
            $permissionrole->select( 'permission_id' )->from( 'permission_role' )->
            where( 'role_id' , '=' , $roleId );
        })->get();       

        
        return \View::make('admin.editrole')->with('data', $data);
    }
    
///////////////////////////////////////////////////////////////

// Name: getAvailableProducts

// Purpose:  To populate admin permission tab and role tabe dropdown

// It fetch all available product list from the table product to populate admin  
// permission tab and role tabe dropdown.

// Created at 24-11-2016 by Prajeesh

// Last updated at 24-11-2016 by Prajeesh

////////////////////////////////////////////////////////////// 
    
    public function getAvailableProducts()
    {
        $product = \Product::get();        
        return \Response::json($product);
    }
    
///////////////////////////////////////////////////////////////

// Name: addPermission

// Purpose:  To add permiision to the table  permissions

// It receive content from the form submission in the permission tab page, insert it to 
// the table permissions.

// Created at 24-11-2016 by Prajeesh

// Last updated at 24-11-2016 by Prajeesh

////////////////////////////////////////////////////////////// 
    
    public function addPermission()    
    {
        
        $objData = Input::all();        
        $objPermission = new \Permission(); 
        $objPermission->code = $objData['permission_code'];
        $objPermission->name = $objData['permission_name'];
        $objPermission->description = $objData['permission_description'];
        $objPermission->product_id = $objData['permission_product_id']; 
        $objPermission->save();
        
        $lastInsertedID =$objPermission->id;
        
        $permissionJson =  \Permission::where('id',$lastInsertedID)->get();
        $product = \Product::get();
        $rows='';
        
        foreach ($permissionJson as $value){
            
            $rows =$rows.'<tr>\
                          <td class="tnastafftd_name" style="width:19%;"> <input id="permission_name_'. $value->id .'" class="form-control no-radius" placeholder="Name"  type="text" value="' . $value->name . '"> </td>\
                          <td class="tnastafftd_name" style="width:23%;"> <input id="permission_code_'. $value->id .'" class="form-control no-radius" placeholder="Code" type="text" value="' .$value->code . '"> </td>\
                          <td class="tnastafftd_name" style="width:19%;"> <input id="permission_description_'. $value->id .'" class="form-control no-radius" placeholder="Description" type="text" value="' . $value->description . '"> </td>\
                          <td class="tnastafftd_name" style="width:19.5%;"> <select id="permission_product_id_'. $value->id .'" class="form-control no-radius product_code"><option>Product Code</option>';
            
            foreach($product as $product_value) {
                if($value->product_id == $product_value->id) {
                    
                    $option_selected =  'selected="selected"'; 
                    
                } else {
                    
                    $option_selected     =   '';
                }
              $rows = $rows.'<option value="' . $product_value->id . '"' . $option_selected . '>'.$product_value->name . '</option>';
            }; 
                              

$rows =$rows.'</select></td>\
                          <td class="tnastafftd_add_update" style="width:17%;"> <a id="updatePermissionDetails_'. $value->id .'" class="btn btn-default btn-delete btn-sm" onclick="updatePermissionDetails('. $value->id .');">Update</a> <a id="deletePermissionDetails_'. $value->id .'" class="btn btn-default btn-delete btn-sm" onclick="deletePermissionDetails('. $value->id .');">Delete</a></td>\
                          <td></td>\
                        </tr>\
                    ';
        }
        $data = array('row'=>$rows);
        print_r(json_encode($data)); 
    }
    
///////////////////////////////////////////////////////////////

// Name: showAvailablePermission

// Purpose:  To show all available permission in permission tab view page.

// It fetch all available permission from the permissions table to list it on permission
// tab view page..

// Created at 29-11-2016 by Prajeesh

// Last updated at 29-11-2016 by Prajeesh

////////////////////////////////////////////////////////////// 
    
    public function  showAvailablePermission()  
    {
        $permissionJson =  \Permission::orderBy('created_at','DESC')
                                                ->get();
        return \Response::json($permissionJson);
    } 
    
///////////////////////////////////////////////////////////////

// Name: deletePermission

// Purpose:  To delete available permission in permission tab view page.

// It recieve permission id via ajax and delete permission from the permissions table on delete 
// button click.

// Created at 29-11-2016 by Prajeesh

// Last updated at 29-11-2016 by Prajeesh

////////////////////////////////////////////////////////////// 
    
    public function  deletePermission()  
    {
        
        $objData = Input::all(); 
        $objDeletePermission = \Permission::find($objData['id']);
        $objDeletePermission->delete();

        $data = array( 'id' => $objData['id'] );
        print_r(json_encode($data));
        
    }
    
///////////////////////////////////////////////////////////////

// Name: updatePermission

// Purpose:  To update permission in permission tab view page.

// It fetch all details from the form via ajax and update permissions table row have that
// particular permission_id.

// Created at 30-11-2016 by Prajeesh

// Last updated at 30-11-2016 by Prajeesh

////////////////////////////////////////////////////////////// 
    
    public function  updatePermission()  
    {
        
        $objData = Input::all();  
        $objUpdatePermission = \Permission::find($objData['permission_id']);        
        $objUpdatePermission->code = $objData['permission_code'];
        $objUpdatePermission->name = $objData['permission_name'];
        $objUpdatePermission->description = $objData['permission_description'];
        $objUpdatePermission->product_id = $objData['permission_product_id'];            
        $objUpdatePermission->save(); 
        
    }
    
// Name: showAvailableRoles

// Purpose:  To show all available roles in role tab view page.

// It fetch all available roles from the roles table to list it on role
// tab view page..

// Created at 01-12-2016 by Prajeesh

// Last updated at 01-12-2016 by Prajeesh

////////////////////////////////////////////////////////////// 
    
    public function  showAvailableRoles()  
    {
        $roleJson =  \Role::orderBy('created_at','DESC')
                                                ->get();
        return \Response::json($roleJson);
    } 
    
    
///////////////////////////////////////////////////////////////

// Name: addRole

// Purpose:  To add role to the table  roles

// It receive content from the form submission in the role tab page, insert it to 
// the table roles.

// Created at 01-12-2016 by Prajeesh

// Last updated at 01-12-2016 by Prajeesh

////////////////////////////////////////////////////////////// 
    
    public function addRole()    
    {
        
        $objData = Input::all();        
        $objRole = new \Role(); 
        $objRole->name = $objData['role_name'];
        $objRole->description = $objData['role_description'];
        $objRole->product_id = $objData['role_product_id']; 
        $objRole->save();
        
        $lastInsertedID =$objRole->id;
        
        $roleJson =  \Role::where('id',$lastInsertedID)->get();
        $product = \Product::get();
        $rows='';
        
        foreach ($roleJson as $value){
            
            $href = 'http://localhost/prajeesh/hiphub/public/admin_roleedit/' . $value->id;
            $rows = $rows . '<tr>\
                <td>' . $value->name . '</td>\
                <td>' . $value->description . '</td> <td>';
                    
                   foreach($product as $product_value){
                        if($product_value->id == $value->product_id){
                            $product_name = $product_value->name;
                            
                        } else {
                            $product_name = '';
                            
                        }
                       $rows = $rows . $product_name ;
                    }                    
                
        $rows = $rows . '</td><td><a href="'. $href .'" class="btn btn-default btn-sm">Edit</a>
                    <a id="btn_delete_' . $value->id . '" class="btn btn-default btn-delete btn-sm" data-roleid = "' . $value->id . '" href="#">Delete</a>
                </td>'; 
        }
        $data = array('row'=>$rows);
        print_r(json_encode($data)); 
    }
    
    
///////////////////////////////////////////////////////////////

// Name: editRole

// Purpose:  To edit role details and to add permissions to the role

// It fetch all details from the form via ajax and update role details. Also it add permissions to
// the same role in permission role table.

// Created at 06-12-2016 by Prajeesh

// Last updated at 06-12-2016 by Prajeesh

////////////////////////////////////////////////////////////// 
    
    public function editRole()    
    {
        // edit role details
        
        $objData = Input::all();         
        $objUpdateRole = \Role::find($objData['role_id']);        
        $objUpdateRole->name = $objData['role_name'];
        $objUpdateRole->description = $objData['role_description'];
        $objUpdateRole->product_id = $objData['product_id']; 
        $objUpdateRole->save();
        
        //delete all permission for the particular role id        
        
        DB::table('permission_role')->where('role_id', $objData['role_id'])->delete();
        
        // add permission to role
        
        foreach ($objData['permission_id_arr'] as $permission_id) {
            $objPermissionRole = new \Permissionrole(); 
            $objPermissionRole->permission_id = $permission_id;
            $objPermissionRole->role_id = $objData['role_id'];       
            $objPermissionRole->save();
            
            $data = array('message'=>'success');
            
        }    
        
        print_r(json_encode($data));
        

    }
    
///////////////////////////////////////////////////////////////

// Name: deleteRole

// Purpose:  To delete available role in role tab view page.

// It recieve role id via ajax and delete role from the role table on delete 
// button click.

// Created at 02-12-2016 by Prajeesh

// Last updated at 02-12-2016 by Prajeesh

////////////////////////////////////////////////////////////// 
    
    public function  deleteRole()  
    {
        
        $objData = Input::all(); 
        $objDeleteRole = \Role::find($objData['id']);
        $objDeleteRole->delete();

        $data = array( 'id' => $objData['id'] );
        print_r(json_encode($data));
        
    }
    
    
}