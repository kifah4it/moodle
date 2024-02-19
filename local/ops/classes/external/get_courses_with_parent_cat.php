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

class get_courses_with_parent_cat extends \core_external\external_api{
    public static function get_courses_with_parent_cat_parameters(){
        return new Core_externalExternal_function_parameters(
                array(
                    'catname' => new Core_externalExternal_value(PARAM_TEXT,'category Name',$allownull = true),
                    
                    )
                );
        
    }
    public static function get_courses_with_parent_cat($catname = null){
        global $DB,$CFG;
        require_once($CFG->dirroot . "/course/lib.php");
        require_once($CFG->dirroot . '/course/externallib.php');
        // $course = $DB->get_record('course', array('id' => 1), '*', MUST_EXIST);
        //   var_dump($course);
        $categories = $DB->get_records_select('course_categories',NULL);
        if($catname != null){
        $catid = self::findcatid($categories,$catname);
        }
        $data = exterapi::call_external_function('core_course_get_courses_by_field', [], false);// need to optimize in future !!
              
            $i = 0;
           foreach($data as $cor){
               if($i == 1){
                   if($catid == null)
                    array_shift($cor['courses']);
                $courses = $cor['courses'];
                break;
               }
                $i++;               
           }
           $data = array();
           if($catname != null){
               foreach($categories as $cat){
                   if($cat->id == $catid || $cat->parent == $catid){
                foreach($courses as $cor){
                  if($cor['categoryid'] == $cat->id){
                    $data[] = (array) array_merge( (array)$cor, array( 'parentcat' => self::findcatName($categories,$cat->parent) ) );
                  }
               }
            }
           }
        }else{
            foreach($courses as $cor){
                foreach($categories as $cat){
                   if($cor['categoryid'] == $cat->id){
                         $data[] = (array) array_merge( (array)$cor, array( 'parentcat' => self::findcatName($categories,$cat->parent) ) );
                   }
                }
            }
        }
        
        $result = array(
            'courses' => var_dump($data)
             );
        
            return $result;
    }
    public static function get_courses_with_parent_cat_returns(){
       
        return new Core_externalExternal_single_structure(
            array(
                'courses'  => new Core_externalExternal_value(PARAM_RAW,'courses with parent category'),
            )
        );
  
    }
    private static function findcatName($cat,$id){
        foreach($cat as $ca){
            if($ca->id == $id)
            return $ca->name;
          }
          return 'uncategorized';
      }
      private static function findcatid($cat,$name){
        foreach($cat as $ca){
            if($ca->name == $name)
            return $ca->id;
          }
          return 0;
      }
    
 
}