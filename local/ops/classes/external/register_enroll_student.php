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
use local_ops\external\Course_availability;
class register_enroll_student extends \core_external\external_api
{
    public static function enroll_student_parameters()
    {
        return new Core_externalExternal_function_parameters(
            array(
                'username' => new Core_externalExternal_value(PARAM_TEXT, 'username'),
                'courses' => new Core_externalExternal_multiple_structure(
                    new Core_externalExternal_value(PARAM_TEXT, 'courseid')
                )
            )
        );
    }
   
    public static function enroll_student($username, $courses = array())
    {
        global $DB, $CFG;
        try {
            $courseprereq = array();
            $user = $DB->get_record('user', ['username' => $username]);
           // $courses_prereqs = exterapi::call_external_function('local_ops_check_course_prereq',['userid'=>$user->id,'coursesids'=>$courses]);
         //   $courses_prereqs = Course_availability::check_course_prereq(48,$courses);
            $plugin = enrol_get_plugin('apply');
            foreach ($courses as $c) {
                
                $course = $DB->get_record('course',array('id' => $c));
                $enrolmethds = $DB->get_records('enrol', array('enrol' => 'apply', 'courseid' => $c));
                foreach ($enrolmethds as $instance) {
                    $timestart = $course->startdate;
                    if($instance->enrolperiod > 0)
                    $timeend = $timestart + $instance->enrolperiod;
                    else
                    $timeend = 0;

                    $plugin->enrol_user($instance, $user->id, 5, $timestart, $timeend, ENROL_USER_SUSPENDED);
                }
            }
            self::notifyadminWithNewRegistration($user->id);
                     // printf(json_encode($courses_prereqs)));
                    //  die();
           // return var_dump($courses_prereqs);
            return $result = array(
                'status' => 'success',
                'message' => '',
               // 'courses_prereqs' => $courses_prereqs
                // 'courses_prereqs' => [['courseid' => '1','acceptance' => false,
                // 'prereqs'=>[['courseiname' =>'1','criteriatype' =>'2','requiredgrade' =>'3','ismatched' =>false]]]]

            );
        } catch (Exception $e) {
            return $result = array(
                'status' => 'error',
                'message' => $e->getMessage(),
             //   'courses_prereqs' => [['courseid' => '1','acceptance' => false,
              //   'prereqs'=>[['courseiname' =>'1','criteriatype' =>'2','requiredgrade' =>'3','ismatched' =>false]]]]
            );
        }
    }
    private static function notifyadminWithNewRegistration($userid)
    {
        
        global $DB, $CFG;
        $dir = $CFG->wwwroot;
       // $sesskey = sesskey();
        //Get admin ID
            $admin = $DB->get_record('user',['username'=>'admin']);
        // Create a new notification object
        $table = 'notifications'; // Replace with the name of the table you want to insert data into
        $dataobject = new stdClass();
        $dataobject->useridfrom = $userid; // Replace with the ID of the user who created the notification
        $dataobject->useridto = $admin->id; // Replace with the ID of the user to whom the notification was sent
        $dataobject->subject = "New Registiration";
        $dataobject->fullmessage = "This is a new notification.";
        $dataobject->fullmessagehtml = "you have new Registration Request <a href='$dir/user/view.php?id=$userid'>User</a>";
        $dataobject->fullmessageformat = 1; // 0 for plain text, 1 for HTML
        $dataobject->smallmessage = "New Notification small message";
        $dataobject->component = "myplugin";
        $dataobject->eventtype = "myevent";
        $dataobject->contexturl = ""; // Replace with the URL of the context where the notification was created
        $dataobject->contexturlname = ""; // Replace with the display name of the context where the notification was created
        $dataobject->timecreated = time();
        $notificationID = $DB->insert_record($table, $dataobject);

        // Insert the notification into the mdl_message_popup_notifications table
        $table = 'message_popup_notifications';
        $dataobject = new stdClass();
        $dataobject->userid = $admin->id;
        $dataobject->notificationid = $notificationID;
        $DB->insert_record($table, $dataobject);
    }
    public static function enroll_student_returns()
    {
        return new Core_externalExternal_single_structure(
            array(
                // 'success' => new Core_externalExternal_value(PARAM_BOOL, 'True if the user was created false otherwise'),
                'status'  => new Core_externalExternal_value(PARAM_RAW, 'staus'),
                'message' => new Core_externalExternal_value(PARAM_RAW, 'error message'),
                // 'courses_prereqs' => new Core_externalExternal_multiple_structure(
                //     new Core_externalExternal_single_structure(
                //         array(
                //             'courseid' => new Core_externalExternal_value(PARAM_RAW, 'courseid'),
                //             'acceptance' => new Core_externalExternal_value(PARAM_BOOL, 'enroll accpeted'),
                //             'prereqs' => new Core_externalExternal_multiple_structure(
                //                 new Core_externalExternal_single_structure(
                //                     array(
                //                         'courseiname' => new Core_externalExternal_value(PARAM_RAW, 'coursiename'),
                //                         'criteriatype' => new Core_externalExternal_value(PARAM_RAW, 'criteriatype'),
                //                         'requiredgrade' => new Core_externalExternal_value(PARAM_RAW, 'requiredgrade'),
                //                         'ismatched' => new Core_externalExternal_value(PARAM_BOOL, 'ismatched'),
                //                     )
                //                 )
                //             )
                //         )
                //     )
                // )
            )
        );
    }
    // Register new student
    public static function register_student_parameters()
    {
        return new Core_externalExternal_function_parameters(
            array(
                'username' => new Core_externalExternal_value(PARAM_TEXT, 'username'),
                'password' => new Core_externalExternal_value(PARAM_TEXT, 'password'),
                'firstname' => new Core_externalExternal_value(PARAM_TEXT, 'first name'),
                'lastname' => new Core_externalExternal_value(PARAM_TEXT, 'last name'),
                'email' => new Core_externalExternal_value(PARAM_TEXT, 'email'),
                'arabfullname' => new Core_externalExternal_value(PARAM_TEXT, 'arabic full name'),
                'mobnum' => new Core_externalExternal_value(PARAM_TEXT, 'mobile number'),
                'birthdate' => new Core_externalExternal_value(PARAM_TEXT, 'birthdate'),
            )
        );
    }
    public static function register_student($username, $password, $firstname, $lastname, $email, $arabfullname, $mobnum,$birthdate)
    {
        global $DB, $CFG;
        $customfields = array();
        
        $customfields[0] = ['type' => 'text', 'name' => 'profile_field_arabname', 'value' => $arabfullname];
        $customfields[1] = ['type' => 'text', 'name' => 'profile_field_m_num', 'value' => $mobnum];
        $customfields[2] = ['type' => 'datetime', 'name' => 'profile_field_brthdate','value'=>strtotime($birthdate)];
        $result = exterapi::call_external_function('auth_email_signup_user', [
            'username' => $username, 'password' => $password,
            'firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'customprofilefields' => $customfields
        ]);

        // if user created successfully
        try {
            if($result['error'] && $result["exception"]->errorcode != "auth_emailnoemail"){
                $messages = array();
            $messages[0] = ["field" => 'error', "message" => $result["exception"]->debuginfo];
            $result = array(
                'status' => 'error',
                'messages' => $messages
            );
            }
            else if ((!$result["error"] && $result["data"]["success"])
                || $result["error"] && $result["exception"]->errorcode == "auth_emailnoemail"
            ) {
                $messages = array();
                $messages[0] = ["field" => '', "message" => ''];
                $result = array(
                    'status' => 'success',
                    'messages' => $messages
                );
            } else if (!$result["error"]["success"]) {
                $messages = array();
              //  $messages[0] = ["field" => 'unknown', "message" => var_dump($result)];
                foreach ($result["data"]["warnings"] as $warn) {
                    $messages[] = ["field" => $warn["item"], "message" => $warn["message"]];
                }
                $result = array(
                    'status' => 'warnings',
                    'messages' => $messages
                );
            }

            return $result;
        } catch (Exception $e) {
            $messages = array();
            $messages[0] = ["field" => null, "message" => $e->getMessage()];
            $result = array(
                'status' => 'error',
                'messages' => $messages
            );
            return $result;
        }
    }
    public static function register_student_returns()
    {

        return new Core_externalExternal_single_structure(
            array(
                'status' => new Core_externalExternal_value(PARAM_TEXT, 'success or error'),
                'messages' => new Core_externalExternal_multiple_structure(
                    new Core_externalExternal_multiple_structure(
                        new Core_externalExternal_value(PARAM_TEXT, 'field'),
                        new Core_externalExternal_value(PARAM_TEXT, 'message'),
                    )
                ),
            )
        );
    }
}
