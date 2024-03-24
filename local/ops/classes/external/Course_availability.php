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

class Course_availability extends \core_external\external_api
{
    public static function check_course_prereq_parameters()
    {
        return new Core_externalExternal_function_parameters(
            array(
                'userid' => new Core_externalExternal_value(PARAM_TEXT, 'userid'),
                'coursesids' => new Core_externalExternal_multiple_structure(
                    new Core_externalExternal_value(PARAM_TEXT, 'courseid')
                )
            )
        );
    }
    private static function is_enrolled_last_course($userid,$coursesid){
        global $DB;
        $sql = "SELECT ue.userid,cr.startdate as course_start_date,
        (SELECT MAX(startdate) from `mdl_course` where id = 14) as last_course_start_date,
        cc.timecompleted,en.courseid FROM `mdl_user_enrolments` ue LEFT JOIN `mdl_course_completions` cc 
        on ue.timestart = cc.timestarted INNER JOIN `mdl_enrol` en on en.id = ue.enrolid INNER JOIN `mdl_course` 
        cr on cr.id = en.courseid where ue.userid =". $userid . " and cr.id =". $coursesid ."";
         $rs = $DB->get_recordset_sql($sql);
         foreach($rs as $r){
             if($r->course_start_date == $r->last_course_start_date){
                    return true;
             }
             return false;
         }
         return false;

    }
    public static function check_course_prereq($userid, $coursesids = array())
    {
        global $DB;
        $courses_prereqs = array();
        $reuslts = array();
        foreach ($coursesids as $cid) {
            //check if the sutdent enrolled in the last course established
            if(self::is_enrolled_last_course($userid,$cid)){
                $results[] = array(
                    'courseid' => $cid,
                    'status' =>  'enrolled',
                    'prereqs' => [['courseiname' => '', 'criteriatype' => '', 'requiredgrade' => '', 'ismatched' => false]]
                );
                continue;
            }
            // find matched course pre-requists
            $sql = "SELECT id as criteriaid, criteriatype, courseinstance,
        (SELECT fullname FROM mdl_course WHERE id = cr.courseinstance) as courseiname, gradepass, course, 
        (SELECT CASE WHEN criteriaid = cr.id THEN 'yes' ELSE 'no' END 
        FROM mdl_course_completion_crit_compl WHERE userid =" . $userid . " AND criteriaid = cr.id ) as matched, 
        (SELECT method FROM mdl_course_completion_aggr_methd WHERE course = cr.course AND criteriatype IS NULL) 
        as method FROM mdl_course_completion_criteria cr WHERE course =" . $cid . "";
            $rs = $DB->get_recordset_sql($sql);
            $matchedcrits = array();
            $prereq = array();
            $Any = false; // All criteria shoud be matched or any
            $critnum = 0;
            foreach ($rs as $r) {
                if ($r->matched == 'yes') {
                    $matchedcrits[] = ['courseiname' => $r->courseiname, 'criteriatype' => $r->criteriatype, 'requiredgrade' => $r->gradepass, 'ismatched' => true];
                } else {

                    $prereq[] = ['courseiname' => $r->courseiname, 'criteriatype' => $r->criteriatype, 'requiredgrade' => $r->gradepass, 'ismatched' => false];
                }
                $critnum++;
                $r->method == 1 ? $Any = false : $Any = true;
            }
            $acceptance = 'not accepted';
            if ($critnum == count($matchedcrits)) // all criterias required and matched
                $acceptance = 'accepted';
            else if ($Any == true && count($matchedcrits) > 0) { // one or more required criteria matched
                $acceptance = 'accepted';
            } else
                $acceptance = 'not accepted'; // criterias not matched

            $prereq = array_merge($matchedcrits, $prereq);
            //  $courses_prereqs[] = $prereq;
            $results[] = array(
                'courseid' => $cid,
                'status' =>  $acceptance,
                'prereqs' => $prereq
            );
        }
        return $results;
        // return $result = array(
        //     'courseid' => $courseid,
        //     'acceptance' =>  $acceptance,
        //     'prereqs' => $prereq
        // );
    }
    public static function check_course_prereq_returns()
    {
        return new Core_externalExternal_multiple_structure(
            new Core_externalExternal_single_structure(
                array(
                    'courseid' => new Core_externalExternal_value(PARAM_RAW, 'courseid'),
                    'status' => new Core_externalExternal_value(PARAM_TEXT, 'enroll accpeted'),
                    'prereqs' => new Core_externalExternal_multiple_structure(
                        new Core_externalExternal_single_structure(
                            array(
                                'courseiname' => new Core_externalExternal_value(PARAM_RAW, 'coursiename'),
                                'criteriatype' => new Core_externalExternal_value(PARAM_RAW, 'criteriatype'),
                                'requiredgrade' => new Core_externalExternal_value(PARAM_RAW, 'requiredgrade'),
                                'ismatched' => new Core_externalExternal_value(PARAM_BOOL, 'ismatched'),
                            )
                        )
                    )
                )
            )
        );
    }
}
