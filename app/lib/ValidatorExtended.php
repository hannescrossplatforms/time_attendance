<?php
 
namespace Lib;
 
use Illuminate\Validation\Validator as IlluminateValidator;
 
class ValidatorExtended extends IlluminateValidator {
 
    private $_custom_messages = array(
        "alpha_num" => "The :attribute may only contain letters and numbers.",
        "alpha_num_dash" => "The :attribute may only contain letters, numbers, and dashes.",
        "alpha_dash_spaces" => "The :attribute may only contain letters, spaces, and dashes.",
        "alpha_num_dash_spaces" => "The :attribute may only contain letters, spaces, and dashes.",
        "alpha_num_spaces" => "The :attribute may only contain letters, numbers, and spaces.",
        "macaddress_format" => "The :attribute format is invalid  AA:BB:CC:DD:EE:FF.",
        "array_not_null" => "You must select at least one :attribute .",
    );
 
    public function __construct( $translator, $data, $rules, $messages = array(), $customAttributes = array() ) {
        parent::__construct( $translator, $data, $rules, $messages, $customAttributes );
 
        $this->_set_custom_stuff();
    }
 
    protected function _set_custom_stuff() {
        //setup our custom error messages
        $this->setCustomMessages( $this->_custom_messages );
    }
 
     protected function validateMacaddressFormat( $attribute, $value ) {
        error_log("validateMacaddress");
        return (bool) preg_match( "/^([0-9A-F]{2}[:]){5}([0-9A-F]{2})$/", $value );
    }

    protected function validateAlphaNumDashSpaces( $attribute, $value ) {
        error_log("validateAlphaNumDashSpaces");
        return (bool) preg_match( "/^[A-Za-z0-9\s-_]+$/", $value );
    }

    protected function validateAlphaNumDash( $attribute, $value ) {
        error_log("validateAlphaNumDash");
        return (bool) preg_match( "/^[A-Za-z0-9-_]+$/", $value );
    }

    protected function validateAlphaDashSpaces( $attribute, $value ) {
        error_log("validateAlphaDashSpaces");
        return (bool) preg_match( "/^[A-Za-z\s-_]+$/", $value );
    }

    protected function validateAlphaNumSpaces( $attribute, $value ) {
        error_log("validateAlphaNumSpaces");
        return (bool) preg_match( "/^[A-Za-z0-9\s]+$/", $value );
    }

    protected function validateAlphaNum( $attribute, $value ) {
        error_log("validateAlphaNum");
        return (bool) preg_match( "/^[A-Za-z0-9]+$/", $value );
    }

    protected function validateArrayNotNull( $attribute, $value ) {
        error_log("validateArrayNotNull");
        return (bool) $value;
    }
 
}   //end of class
 
 
//EOF