<?php

namespace useradmin;
use DB;
// use BaseController;

class UseradminController extends \BaseController {

    public function showUsers($json = null)
    {

        $data = array();
        $userObj = new \User();
        $data["user"] = $userObj;
        $filter = \Input::get('filter');
        $usersStruct = array();

        $fullname = \Input::get('fullname');
        $fullname =  "%" . $fullname . "%";
        $email = \Input::get('email');
        $email =  "%" . $email . "%";

        error_log("showUsers : fullname : $fullname");
        error_log("showUsers : email : $email");

        $criteria = array('fullname'=>$fullname, 'email'=>$email);
        $usersStruct = $userObj->getUsersData(null, $criteria);

        $usersJason = json_encode($usersStruct);
        // echo $usersJason;
        $data['usersStruct'] = $usersStruct;
        $data['usersJason'] = $usersJason;

        $data['currentMenuItem'] = "User Admin";

        if($json) {
            return \Response::json($usersJason);

        } else {
            return \View::make('useradmin.showusers')->with('data', $data);
            
        }

    }

    public function addUser()
    {
        $data = array();
        $data['user'] =  $user = new \User();
        $data['edit'] = false;
        $data['currentMenuItem'] = "User Admin";

        $levels = \Level::All();
        $level_names = array();
        foreach($levels as $level) {
            $level_names[$level->code] = $level->name;
        }
        $data['level_names'] = $level_names;

        $brands = \Brand::All();
        $data['allbrands'] = $brands;
        $data['brandArray'] = array(); //TAKE THIS OUT WHEN DONE

        $data['products']['HipWIFI'] = false;
        $data['products']['HipRM'] = false;
        $data['products']['HipJAM'] = false;
        $data['products']['HipENGAGE'] = false;
        $data['products']['HipREPORTS'] = false;
        $data['products']['HipTnA'] = false;

        $data['permissions']['ques_rw'] = false;
        $data['permissions']['media_rw'] = false;
        $data['permissions']['uru_rw'] = false;
        $data['permissions']['rep_rw'] = false;

        $data['countries'] = \Countrie::All();

        $data['user']->level_code = "Brand Admin";

        foreach($data['allbrands'] as $brand) {
            error_log("============= " . $brand->name);
        }

        return \View::make('useradmin.adduser')->with('data', $data);
    }
    
    public function addUserSave()
    {
        $user = new \User();

        $input = \Input::all();
        $input["brandcount"]= sizeof(\Input::get('brand_ids'));
        $input["productcount"]= sizeof(\Input::get('product_ids'));
        $password = \Input::get('password');

        $messages = array(
            'email.email' => 'The email address is invalid',
            'fullname.alpha_num_dash_spaces' => 'The full name must only contain letters, numbers, dashes and spaces',
            'password.alpha_num' => 'The password must only contain letters and numbers',
            'password.min:6' => 'The password must contain at least 6 characters',
            'brandcount.array_not_null' => 'You must select at least one brand',
            'productcount.array_not_null' => 'You must select at least one product',
        );

        $rules = array(
            'fullname'      => 'required|alpha_num_dash_spaces',  
            'email'         => 'required|email|unique:users',  
            'password'      => 'alpha_num|min:6',                       
            'brandcount'    => 'array_not_null',                       
            'productcount'  => 'array_not_null',                       
        );

        $validator = \Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();

            return \Redirect::to('useradmin_add')->withErrors($validator)->withInput();

        } else {
            
            $user->fullname = \Input::get('fullname');
            $user->email = \Input::get('email');
            $user->level_code = \Input::get('level_code');
            $user->password = \Hash::make($password); 
            $user->save();

            $brand_ids = \Input::get('brand_ids');
            foreach($brand_ids as $brand_id) {
                $user->brands()->attach($brand_id);
            }

            $product_ids = \Input::get('product_ids');
            foreach($product_ids as $product_id) {
                $user->products()->attach($product_id);
            }
        }

        return \Redirect::route('useradmin_showusers');
    }

    public function editUser($id)
    {
        $data = array();
        $data['user'] = \User::find($id);
        $data['edit'] = true;
        $data['currentMenuItem'] = "User Admin";

        $levels = \Level::All();
        $level_names = array();
        foreach($levels as $level) {
            $level_names[$level->code] = $level->name;
        }
        $data['level_names'] = $level_names;

        $data['brandArray'] = array();
        $brands = \Brand::All();
        $data['allbrands'] = $brands;

        foreach ($data['user']->brands as $brand) {   
            $countrie_id = $brand->countrie_id;
            $countrie = \Countrie::find($countrie_id);
            $data['brandArray'][$brand->id]["name"] = $brand->name;   
            $data['brandArray'][$brand->id]["countrie"] = $countrie["name"];   
        }

        $data['products']['HipWIFI'] = false;
        $data['products']['HipRM'] = false;
        $data['products']['HipJAM'] = false;
        $data['products']['HipENGAGE'] = false;
        $data['products']['HipREPORTS'] = false;
        $data['products']['HipTnA'] = false;

        $data['permissions']['ques_rw'] = false;
        $data['permissions']['media_rw'] = false;
        $data['permissions']['uru_rw'] = false;
        $data['permissions']['rep_rw'] = false;

        $data['countries'] = \Countrie::All();

        foreach ($data['user']->products as $product) {
            error_log('editUser : ' . $product->name);
            $data['products'][$product->name] = true;
        }

        // foreach ($data['user']->permissions as $permission) {
        //     $data['permissions'][$permission->name] = true;
        // }

        return \View::make('useradmin.adduser')->with('data', $data);
    }

    public function editUserSave()
    {
        $id = \Input::get('id');
        $input = \Input::all();
        $input["brandcount"]= sizeof(\Input::get('brand_ids'));
        $input["productcount"]= sizeof(\Input::get('product_ids'));
        $password = \Input::get('password');
        $user = \User::find($id);

        $messages = array(
            'email.email' => 'The email address is invalid',
            'fullname.alpha_num_dash_spaces' => 'The full name can only contain letters, numbers, dashes and spaces',
            'brandcount.array_not_null' => 'You must select at least one brand',
            'productcount.array_not_null' => 'You must select at least one product',
        );

        $rules = array(
            'fullname'      => 'required|alpha_num_dash_spaces',  
            'email'         => 'required|email|unique:users,email,'.$id,
            'brandcount'    => 'array_not_null',                       
            'productcount'  => 'array_not_null',                       
        );

        if($password) {
            $messages['password.min:6'] = 'The password must contain at least 6 characters';
            $messages['password.alpha_num'] = 'The password must only contain letters and numbers';
            $rules['password'] = 'alpha_num|min:6';
        }

        $validator = \Validator::make($input, $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();

            return \Redirect::to('useradmin_edit/' . $id)->withErrors($validator)->withInput();

        } else {
     
            $user->fullname = \Input::get('fullname');
            $user->email = \Input::get('email');
            if($password) {
                $user->password = \Hash::make($password); 
            }
            $user->level_code = \Input::get('level_code');
            $user->save();

            $user->brands()->detach();
            $brand_ids = \Input::get('brand_ids');
            if($brand_ids) {   
                foreach($brand_ids as $brand_id) {
                    $user->brands()->attach($brand_id);
                }
            }

            $user->products()->detach();
            $product_ids = \Input::get('product_ids');
            if($product_ids) {
                foreach($product_ids as $product_id) {
                    error_log('editUserSave : product_id : ' . $product_id);
                    $user->products()->attach($product_id);
                }
            }

            // $user->permissions()->detach();
            // $permission_ids = \Input::get('permission_ids');
            // if($permission_ids) {
            //     foreach($permission_ids as $permission_id) {
            //         $user->permissions()->attach($permission_id);
            //         error_log("permission_ids " . $permission_id);
            //     }
            // }
        }

        return \Redirect::route('useradmin_showusers');
    }

    public function deleteUser($id)
    {
        error_log("deleteUser");
        $user = \User::find($id);

        if($user) {
            error_log("deleteUser 10");
            $user->brands()->detach();

            $user->products()->detach();

            // $user->permissions()->detach();

            $user->delete();  
        }

        return \Redirect::route('useradmin_showusers', ['json' => 1]);
    }

    public function getRolesForProduct() {   
        
        $roles = DB::table('roles')->where("product_id", "=", \Input::get('product_id'))->
        whereNotIn('id',function($role_user){ 
            $role_user->select('role_id')->from('role_user')->
            where('user_id','=',\Input::get('user_id'));
        })->get();
        
        return \Response::json($roles);

    }

    public function getRolesForUserandProduct() {

        $user_id = \Input::get('user_id');
        $product_id = \Input::get('product_id');
        $user = \User::find($user_id);
        error_log("getRolesForUserandProduct : " . print_r($user, true));
        $roles = $user->roles()->get();// ->where('product_id', '=', $product_id);
        return \Response::json($roles);

    }
    
///////////////////////////////////////////////////////////////

// Name: addRolesForUserandProduct

// Purpose:  To add roles for the user and product

// It receive user id and role id and add this to the table role_user
// 
// Created at 18-11-2016 by Prajeesh

// Last updated at 21-11-2016 by Prajeesh

////////////////////////////////////////////////////////////// 
    
    public function addRolesForUserandProduct() {  
        $role_id = \Input::get('role_id');
        $roleuser = new \Roleuser();
        $roleuser->user_id = \Input::get('user_id');
        $roleuser->role_id = \Input::get('role_id');         
        $roleuser->save();
        
        $reportJson =  \Role::where('id',\Input::get('role_id'))->get();
        
        foreach ($reportJson as $value) {
            $row = '\
                      <tr>\
                        <td> <input type="hidden" name="role_ids" id="role_id_' . $value->id . '"  value="' . $value->id . '">'  . $value->name . ' </td>\
                        <td> ' . $value->description . '</td>\
                        <td> <btn data-role-id="' . $value->id . '" class="btn btn-default btn-delete btn-delete-permission btn-sm"> delete </btn></td>\
                      </tr>\
                    ';       
       
        }
        
        $data = array('row'=>$row,'id' => $role_id);
        print_r(json_encode($data));

        

    }
    
///////////////////////////////////////////////////////////////

// Name: deleteRolesForUserandProduct

// Purpose:  To delete roles for the user and product

// It receive user id and role id and delete from the table role_user
// 
// Created at 22-11-2016 by Prajeesh

// Last updated at 22-11-2016 by Prajeesh

////////////////////////////////////////////////////////////// 
    
    public function deleteRolesForUserandProduct() {  
        $role_id = \Input::get('role_id');
        $whereArray = array('user_id' => \Input::get('user_id'),'role_id' => \Input::get('role_id'));
        $deleteRole = DB::table('role_user');
        foreach($whereArray as $field => $value) {
            $deleteRole->where($field, $value);
        }
        $deleteRole->delete();
        
        $reportJson =  \Role::where('id',$role_id)->get();
        
        foreach ($reportJson as $value) {
            $row = '<option id="tna_permission_id_' . $value->id . '" value="' . $value->id . '">' . $value->name . '</option>';            
        }
        
        $data = array( 'id' => $role_id,'row'=>$row );
        print_r(json_encode($data));

    }

}