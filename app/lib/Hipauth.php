<?php 
 
class Hipauth{

	public static function getAssignedPermissions() {
		// Get the user
		$user = Auth::user();
    	$assignedPermissions = array();
		
    	// Get the user's roles
    	$roles = $user->roles()->get();

    	// Get the permissions for each role
    	foreach($roles as $role) {
    		$permissionsObj =  $role->permissions()->get();
    		foreach($permissionsObj as $permission) {
    			array_push($assignedPermissions, $permission->code);
    		}
    	}

    	return $assignedPermissions;
	}

	// Returns true if the user has ANY of the requested permissions
	public static function hasAnyPermissions($requestedPermissions) {

		if (\User::hasAccess("superadmin")) return true;

		$hipAuthObj = new \Hipauth();
		$assignedPermissions = $hipAuthObj->getAssignedPermissions();

    	foreach($requestedPermissions as $requestedPermission) {

    		if(in_array($requestedPermission, $assignedPermissions)) {
    			return true;
    		}
    		
    	}

        return false;        
    }

	// Returns true if the user has ALL of the requested permissions
	public static function hasAllPermissions($requestedPermissions) {

		if (\User::hasAccess("superadmin")) return true;

		$hipAuthObj = new \Hipauth();
		$assignedPermissions = $hipAuthObj->getAssignedPermissions();

    	foreach($requestedPermissions as $requestedPermission) {

    		if(!in_array($requestedPermission, $assignedPermissions)) {
    			return false;
    		}
    		
    	}

        return true;        
    }
	

}