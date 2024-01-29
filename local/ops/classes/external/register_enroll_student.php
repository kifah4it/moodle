<?php
namespace local_ops\external;

use core\session\exception;
use core_external\external_function_parameters as Core_externalExternal_function_parameters;
use core_external\external_multiple_structure as Core_externalExternal_multiple_structure;
use core_external\external_single_structure as Core_externalExternal_single_structure;
use core_external\external_value as Core_externalExternal_value;
use core_external\external_api as exterapi;
use core_external\external_warnings;
use stdClass;

class register_enroll_student extends \core_external\external_api{
    public static function register_enroll_student_parameters(){
        return new Core_externalExternal_function_parameters(
                array(
                    'username' => new Core_externalExternal_value(PARAM_TEXT,'username'),
                    'password' => new Core_externalExternal_value(PARAM_TEXT,'password'),           
                    'firstname' => new Core_externalExternal_value(PARAM_TEXT,'first name'),       
                    'lastname' => new Core_externalExternal_value(PARAM_TEXT,'last name'),
                    'email' => new Core_externalExternal_value(PARAM_TEXT,'email'),
                    'arabfullname' => new Core_externalExternal_value(PARAM_TEXT,'arabic full name'),
                    // 'courses' => new Core_externalExternal_multiple_structure(
                    //     new Core_externalExternal_value(PARAM_TEXT,'courseid')
                    // )
                    )
                );
        
    }
    public static function register_enroll_student($username, $password, $firstname, $lastname, $email, $arabfullname){
        global $DB,$CFG;
        // var_dump($CFG->dirroot);
        // die();
  //      require_once($CFG->dirroot.'/enrol/apply/lib.php');
        // require_once("../../enrol/apply/lib.php");
      //   $application = enrol_apply_plugin::roles_protected();
        

         

        // return $result = array(
        //     'user' => var_dump($plugin->add_instance($course,null))
        //      );
       $customfields = array();
       $customfields[0] = ['type'=>'text','name'=>'profile_field_arabname','value'=>$arabfullname];
        $result = exterapi::call_external_function('auth_email_signup_user', ['username'=> $username,'password'=>$password,
        'firstname'=>$firstname,'lastname'=>$lastname,'email'=>$email,'customprofilefields'=>$customfields]);
        // if user created successfully
        if((!$result["error"] && $result["data"]["success"]) 
        || $result["error"] && $result["exception"]->errorcode == "auth_emailnoemail"){
            //$user = exterapi::call_external_function('core_user_get_users_by_field',['field'=>'username','values'=>[$username]]);
            $user = $DB->get_record('user',['username'=>$username]);
            $plugin = enrol_get_plugin('apply');
            $course = $DB->get_record('course', array('id'=> 3), '*', MUST_EXIST);
            $enrolmethds = $DB->get_records('enrol',array('enrol'=>'apply','courseid'=>3));
            foreach($enrolmethds as $instance){
            $timestart = time();
            $timeend = $timestart + $instance->enrolperiod;
            $plugin->enrol_user($instance,$user->id,5,$timestart,$timeend,ENROL_USER_SUSPENDED);
            return $result = array(
                'user' => var_dump($plugin)
                 );
                }
        }
        $result = array(
            'user' => var_dump($result)
             );
        // $result = [
        //         'fname' => $fname,
        //         'lname' => $lname,
        //         'arabfullname' => $arabfullname,
        //         'username'=> $username,
        //         'email' => $email,
        //         'password' => $password,
        //         'courses' => $courses,
        //         'message' => 'success'
        //     ];
            return $result;
    }
    public static function register_enroll_student_returns(){
       
        return new Core_externalExternal_single_structure(
            array(
               // 'success' => new Core_externalExternal_value(PARAM_BOOL, 'True if the user was created false otherwise'),
                'user'  => new Core_externalExternal_value(PARAM_RAW,'user info'),
            )
        );

    //  return new Core_externalExternal_single_structure (   
    //         array(
    //         'fname' => new Core_externalExternal_value(PARAM_TEXT,'first name'),
    //         'arabfullname' => new Core_externalExternal_value(PARAM_TEXT,'arabic full name'),
    //         'lname' => new Core_externalExternal_value(PARAM_TEXT,'last name'),
    //         'username' => new Core_externalExternal_value(PARAM_TEXT,'username'),
    //         'email' => new Core_externalExternal_value(PARAM_TEXT,'email'),
    //         'password' => new Core_externalExternal_value(PARAM_TEXT,'password'),
    //         'message' => new Core_externalExternal_value(PARAM_TEXT,'success message'),
    //         'courses' => new Core_externalExternal_multiple_structure(
    //             new Core_externalExternal_value(PARAM_TEXT,'courseid')
    //         )
    //     ));
    }
    
 
}