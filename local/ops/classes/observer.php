<?php

class local_ops_observer
{
    public static function user_loggedin(\core\event\user_loggedin $event){
        // $ch = curl_init();
        // $headers = array(
        //     'Accept: application/json',
        //     'Content-Type: application/json',
        // );

        // curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/ccit/public/api/v1/retriveCourses');
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($event));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // // Timeout in seconds
        // curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        // $statu = curl_exec($ch);
    }
    //Users observers
    public static function user_created(\core\event\user_created $event)
    {
        // require_once($CFG->dirroot . '/classes/notification_queue.php');
        // require_once($CFG->dirroot . '/lib/user.php');
        //  require_once(dirname(dirname(__FILE__)) . '/lib/datamanager.php');

        // Get information about the new user
        self::notifyadminWithNewRegistration($event->relateduserid);

        // $user_name = get_string('name', 'user');
        // $user_email = $new_user->email;
        // $course_name = ""; // Get course name if applicable
        
        // Create the notification message
        // $message = new stdClass();
        // $message->type = NOTIFICATION_TYPE_ADMIN;
        // $message->subject = "New user created";
        // $message->message = "A new user, {$new_user->username}, has been created. Their name is {$new_user->firstname} {$new_user->lastname} and their email address is {$user_email}. They were enrolled in the course {$course_name}.";

        // Add the notification to the queue
        // $queue = new notification_queue();
        // $queue->insert($message);
    }

    public static function user_deleted(\core\event\user_deleted $event)
    {
    }

    public static function user_password_updated(\core\event\user_password_updated $event)
    {
    }

    public static function user_updated(\core\event\user_updated $event)
    {
        // $ch = curl_init();
        // $headers = array(
        //     'Accept: application/json',
        //     'Content-Type: application/json',
        // );

        // curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/ccit/public/api/v1/retriveCourses');
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, sesskey());
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // // Timeout in seconds
        // curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        // $statu = curl_exec($ch);
    }


    //Chapter observers
    public static function chapter_created(\mod_book\event\chapter_created $event)
    {
    }

    public static function chapter_deleted(\mod_book\event\chapter_deleted $event)
    {
    }

    public static function chapter_updated(\mod_book\event\chapter_updated $event)
    {
    }

    //Course observers
    public static function course_created(\core\event\course_created $event)
    {
        self::getCoursesData();
    }

    public static function course_updated(\core\event\course_updated $event)
    {
       
        self::getCoursesData();
    }

    public static function course_deleted(\core\event\course_deleted $event)
    {
        self::getCoursesData();
    }

    public static function course_module_created(\core\event\course_module_created $event)
    {
        global $DB;
        // var_dump($event->userid);
        // die();
        $groups = core_external\external_api::call_external_function('core_group_get_course_user_groups',
        ['courseid'=>$event->courseid,'userid'=>$event->userid]);
        if(count($groups["data"]["groups"]) > 0){
        $gid = $groups["data"]["groups"][0]["id"];
        $cm = $DB->get_record('course_modules',['id'=>$event->objectid]);
        $cm->availability = '{"op":"&","c":[{"type":"group","id":'.$gid.'}],"showc":[false]}';
        
         $DB->update_record('course_modules',$cm);
        }
    }

    public static function course_module_deleted(\core\event\course_module_deleted $event)
    {
    }

    public static function course_module_updated(\core\event\course_module_updated $event)
    {
        global $DB;
        // var_dump($event->userid);
        // die();
        $groups = core_external\external_api::call_external_function('core_group_get_course_user_groups',
        ['courseid'=>$event->courseid,'userid'=>$event->userid]);
        if(count($groups["data"]["groups"]) > 0){
        $gid = $groups["data"]["groups"][0]["id"];
        $cm = $DB->get_record('course_modules',['id'=>$event->objectid]);
        $cm->availability = '{"op":"&","c":[{"type":"group","id":'.$gid.'}],"showc":[false]}';
        
         $DB->update_record('course_modules',$cm);
        }
        
    }

    public static function course_restored(\core\event\course_restored $event)
    {
    }
    public static function section_created(\core\event\course_section_created $event)
    {
        global $DB;
        $groups = core_external\external_api::call_external_function('core_group_get_course_user_groups',
        ['courseid'=>$event->courseid,'userid'=>$event->userid]);
        if(isset($groups["data"])){
        if(count($groups["data"]["groups"]) > 0){
        $gid = $groups["data"]["groups"][0]["id"];
        $cs = $DB->get_record('course_sections',['id'=>$event->objectid]);
        $cs->availability = '{"op":"&","c":[{"type":"group","id":'.$gid.'}],"showc":[false]}';
        
         $DB->update_record('course_sections',$cs);
        }
    }
    }
    public static function section_updated(\core\event\course_section_updated $event)
    {
        global $DB;
        $groups = core_external\external_api::call_external_function('core_group_get_course_user_groups',
        ['courseid'=>$event->courseid,'userid'=>$event->userid]);
        if(isset($groups["data"])){
        if(count($groups["data"]["groups"]) > 0){
        $gid = $groups["data"]["groups"][0]["id"];
        $cs = $DB->get_record('course_sections',['id'=>$event->objectid]);
        $cs->availability = '{"op":"&","c":[{"type":"group","id":'.$gid.'}],"showc":[false]}';
        
         $DB->update_record('course_sections',$cs);
        }
    }
    }
    private static function findcatName($cat,$id){
      foreach($cat as $ca){
          if($ca->id == $id)
          return $ca->name;
        }
        return 'uncategorized';
    }
    private static function getCoursesData()
    {
    //     global $CFG,$DB;
    //     require_once($CFG->dirroot . "/course/lib.php");
    //     require_once($CFG->dirroot . '/course/externallib.php');
    //     // $course = $DB->get_record('course', array('id' => 1), '*', MUST_EXIST);
    //     //   var_dump($course);

    //     $data = core_external\external_api::call_external_function('core_course_get_courses_by_field', [], false);
    //     $courses;
    //    // $rem = array_shift($courses['courses']);
    //     $categories = $DB->get_records_select('course_categories',NULL);
    //         $i = 0;
    //        foreach($data as $cor){
    //            if($i == 1){
    //             array_shift($cor['courses']);
    //             $courses = $cor['courses'];
    //             break;
    //            }
    //             $i++;               
    //        }
    //        $data = array();
    //        foreach($courses as $cor){
    //            foreach($categories as $cat){
    //               if($cor['categoryid'] == $cat->id){
    //                     $data[] = (array) array_merge( (array)$cor, array( 'parentcat' => self::findcatName($categories,$cat->parent) ) );
    //               }
    //            }
    //        }
           
        $ch = curl_init();
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
        );

        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/ccit/public/api/v1/retriveCourses');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data,true));
        curl_setopt($ch, CURLOPT_POSTFIELDS, " ");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Timeout in seconds
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        $statu = curl_exec($ch);
        // var_dump($statu);
        // die();
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
        $dataobject->useridfrom = $admin->id; // Replace with the ID of the user who created the notification
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
}
