<?php

namespace local_ops\external;

use block_recentlyaccesseditems\external;
use core\session\exception;
use core_external\external_function_parameters as Core_externalExternal_function_parameters;
use core_external\external_multiple_structure as Core_externalExternal_multiple_structure;
use core_external\external_single_structure as Core_externalExternal_single_structure;
use core_external\external_value as Core_externalExternal_value;
use core_external\external_api as exterapi;
use core_external\external_warnings;
use core_external\external_files;
use core_external\external_format_value;
use stdClass;

class get_courses_with_parent_cat extends \core_external\external_api
{
    public static function get_courses_with_parent_cat_parameters()
    {
        return new Core_externalExternal_function_parameters(
            array(
                'catname' => new Core_externalExternal_value(PARAM_TEXT, 'category Name', $allownull = true),

            )
        );
    }
    // public static function get_courses_with_parent_cat($catname = null)
    // {
    //     global $DB, $CFG;
    //     require_once($CFG->dirroot . "/course/lib.php");
    //     require_once($CFG->dirroot . '/course/externallib.php');
    //     // $course = $DB->get_record('course', array('id' => 1), '*', MUST_EXIST);
    //     //   var_dump($course);
    //     $categories = $DB->get_records_select('course_categories', NULL);
    //     if ($catname != null) {
    //         $catid = self::findcatid($categories, $catname);
    //     }
    //     $data = exterapi::call_external_function('core_course_get_courses_by_field', [], false); // need to optimize in future !!
    //     $cprices = $DB->get_recordset_sql('SELECT courseid,cost,currency FROM `mdl_enrol` WHERE enrol = "fee"');
    //     $i = 0;
    //     foreach ($data as $cor) {
    //         if ($i == 1) {
    //             if ($catid == null)
    //                 array_shift($cor['courses']);
    //             $courses = $cor['courses'];
    //             break;
    //         }
    //         $i++;
    //     }
    //     $data = array();
    //     if ($catname != null) {
    //         foreach ($categories as $cat) {
    //             if ($cat->id == $catid || $cat->parent == $catid) {
    //                 foreach ($courses as $cor) {
    //                     if ($cor['categoryid'] == $cat->id) {
    //                         $data[] = (array) array_merge((array)$cor, array('parentcat' => self::findcatName($categories, $cat->parent), 'interprice' => self::findprice($cor['id'],$cprices)));
    //                     }
    //                 }
    //             }
    //         }
    //     } else {
    //         foreach ($courses as $cor) {
    //             foreach ($categories as $cat) {
    //                 if ($cor['categoryid'] == $cat->id) {
    //                     $data[] = (array) array_merge((array)$cor, array('parentcat' => self::findcatName($categories, $cat->parent),'interprice' => self::findprice($cor['id'],$cprices)));
    //                 }
    //             }
    //         }
    //     }
           
    //     $result = array(
    //         'courses' => $data
    //     );

    //     return $result;
    // }
    // public static function get_courses_with_parent_cat_returns()
    // {

    //     return new Core_externalExternal_single_structure(
    //         array(
    //             'courses' => new Core_externalExternal_multiple_structure(self::get_course_structure(false), 'Course')
    //         )
    //     );
    // }
    public static function get_courses_with_parent_cat($catname = null)
    {
        global $DB, $CFG;
        require_once($CFG->dirroot . "/course/lib.php");
        require_once($CFG->dirroot . '/course/externallib.php');
        // $course = $DB->get_record('course', array('id' => 1), '*', MUST_EXIST);
        //   var_dump($course);
        if($catname == null){
        $sql = "SELECT c.id,c.category as categoryid,cat.name as categoryname, cat.parent as catparent,c.sortorder,c.fullname,c.shortname,c.summary,c.startdate,c.enddate,c.lang,(SELECT CONCAT( 'pluginfile.php/',f.contextid,'/',f.component,'/',f.filearea,'/',f.filename) as img FROM `mdl_files` f INNER JOIN `mdl_context` ctx on ctx.id = f.contextid AND ctx.instanceid = c.id  AND f.mimetype LIKE 'image%' AND f.filename <> '.' LIMIT 1) as courseimage,
        COALESCE((SELECT cd.value FROM `mdl_customfield_field` cf INNER JOIN `mdl_customfield_data` cd on cf.id = cd.fieldid INNER JOIN `mdl_context` ctx on ctx.instanceid = c.id AND ctx.id = cd.contextid AND cf.shortname = 'arname' limit 1),NULL) as arname,
        COALESCE((SELECT cd.value FROM `mdl_customfield_field` cf INNER JOIN `mdl_customfield_data` cd on cf.id = cd.fieldid INNER JOIN `mdl_context` ctx on ctx.instanceid = c.id AND ctx.id = cd.contextid AND cf.shortname = 'ardesc' limit 1),NULL) as ardesc,
        COALESCE((SELECT cd.value FROM `mdl_customfield_field` cf INNER JOIN `mdl_customfield_data` cd on cf.id = cd.fieldid INNER JOIN `mdl_context` ctx on ctx.instanceid = c.id AND ctx.id = cd.contextid AND cf.shortname = 'course_details' limit 1),NULL) as course_details,
        COALESCE((SELECT cd.value FROM `mdl_customfield_field` cf INNER JOIN `mdl_customfield_data` cd on cf.id = cd.fieldid INNER JOIN `mdl_context` ctx on ctx.instanceid = c.id AND ctx.id = cd.contextid AND cf.shortname = 'course_details_en' limit 1),NULL) as course_details_en,                         
        COALESCE((SELECT cd.value FROM `mdl_customfield_field` cf INNER JOIN `mdl_customfield_data` cd on cf.id = cd.fieldid INNER JOIN `mdl_context` ctx on ctx.instanceid = c.id AND ctx.id = cd.contextid AND cf.shortname = 'price' limit 1),NULL) as price,
        COALESCE((SELECT cd.value FROM `mdl_customfield_field` cf INNER JOIN `mdl_customfield_data` cd on cf.id = cd.fieldid INNER JOIN `mdl_context` ctx on ctx.instanceid = c.id AND ctx.id = cd.contextid AND cf.shortname = 'short_desc_ar' limit 1),NULL) as short_desc_ar,
        COALESCE((SELECT cd.value FROM `mdl_customfield_field` cf INNER JOIN `mdl_customfield_data` cd on cf.id = cd.fieldid INNER JOIN `mdl_context` ctx on ctx.instanceid = c.id AND ctx.id = cd.contextid AND cf.shortname = 'short_desc_en' limit 1),NULL) as short_desc_en, 
        en.cost as interprice, en.currency, c.format
        FROM `mdl_course` c LEFT JOIN `mdl_course_categories` cat on cat.id = c.category LEFT JOIN `mdl_enrol` en on c.id = en.courseid AND en.enrol = 'fee' WHERE c.format <> 'site';";
        }else{
            $sql = "SELECT c.id,c.category as categoryid,cat.name as categoryname, cat.parent as catparent,c.sortorder,c.fullname,c.shortname,c.summary,c.startdate,c.enddate,c.lang,(SELECT CONCAT( 'pluginfile.php/',f.contextid,'/',f.component,'/',f.filearea,'/',f.filename) as img FROM `mdl_files` f INNER JOIN `mdl_context` ctx on ctx.id = f.contextid AND ctx.instanceid = c.id  AND f.mimetype LIKE 'image%' AND f.filename <> '.' LIMIT 1) as courseimage,
            COALESCE((SELECT cd.value FROM `mdl_customfield_field` cf INNER JOIN `mdl_customfield_data` cd on cf.id = cd.fieldid INNER JOIN `mdl_context` ctx on ctx.instanceid = c.id AND ctx.id = cd.contextid AND cf.shortname = 'arname' limit 1),NULL) as arname,
            COALESCE((SELECT cd.value FROM `mdl_customfield_field` cf INNER JOIN `mdl_customfield_data` cd on cf.id = cd.fieldid INNER JOIN `mdl_context` ctx on ctx.instanceid = c.id AND ctx.id = cd.contextid AND cf.shortname = 'ardesc' limit 1),NULL) as ardesc,
            COALESCE((SELECT cd.value FROM `mdl_customfield_field` cf INNER JOIN `mdl_customfield_data` cd on cf.id = cd.fieldid INNER JOIN `mdl_context` ctx on ctx.instanceid = c.id AND ctx.id = cd.contextid AND cf.shortname = 'course_details' limit 1),NULL) as course_details,
            COALESCE((SELECT cd.value FROM `mdl_customfield_field` cf INNER JOIN `mdl_customfield_data` cd on cf.id = cd.fieldid INNER JOIN `mdl_context` ctx on ctx.instanceid = c.id AND ctx.id = cd.contextid AND cf.shortname = 'course_details_en' limit 1),NULL) as course_details_en,                         
            COALESCE((SELECT cd.value FROM `mdl_customfield_field` cf INNER JOIN `mdl_customfield_data` cd on cf.id = cd.fieldid INNER JOIN `mdl_context` ctx on ctx.instanceid = c.id AND ctx.id = cd.contextid AND cf.shortname = 'price' limit 1),NULL) as price,
            COALESCE((SELECT cd.value FROM `mdl_customfield_field` cf INNER JOIN `mdl_customfield_data` cd on cf.id = cd.fieldid INNER JOIN `mdl_context` ctx on ctx.instanceid = c.id AND ctx.id = cd.contextid AND cf.shortname = 'short_desc_ar' limit 1),NULL) as short_desc_ar,
            COALESCE((SELECT cd.value FROM `mdl_customfield_field` cf INNER JOIN `mdl_customfield_data` cd on cf.id = cd.fieldid INNER JOIN `mdl_context` ctx on ctx.instanceid = c.id AND ctx.id = cd.contextid AND cf.shortname = 'short_desc_en' limit 1),NULL) as short_desc_en,
            en.cost as interprice, en.currency, c.format
            FROM `mdl_course` c LEFT JOIN `mdl_course_categories` cat on cat.id = c.category LEFT JOIN `mdl_enrol` en on c.id = en.courseid AND en.enrol = 'fee'
            WHERE c.format <> 'site' AND (cat.name ='".$catname."' OR cat.parent = (SELECT id FROM `mdl_course_categories` WHERE name ='".$catname."'));";
        }
        $data = $DB->get_recordset_sql($sql);
        $courses = array();
        foreach($data as $c){
             $courses[] =  array(
                    'id' => $c->id,
                    'categoryid' => $c->categoryid,
                    'parentcat' => $c->parentcat,
                    'sortorder' => $c->sortorder,
                    'fullname' => $c->fullname,
                    'shortname' => $c->shortname,
                    'summary' => $c->summary,
                    'startdate' => $c->startdate,
                    'enddate' => $c->enddate,
                    'lang' => $c->lang,
                    'courseimage' => $c->courseimage,
                    'arname' => $c->arname,
                    'ardesc' => $c->ardesc,
                    'course_details' => $c->course_details,
                    'course_details_en'=> $c->course_details_en,
                    'price' => $c->price,
                    'short_desc_ar' => $c->short_desc_ar,
                    'short_desc_en' => $c->short_desc_en,
                    'interprice' => $c->interprice,
                    'currency' => $c->currency,
                    'categoryname' => $c->categoryname,
                    'format' => $c->format
             );
        }
        $result = array(
            'courses' => $courses
        );
        return $result;
    }
    public static function get_courses_with_parent_cat_returns()
    {

        return new Core_externalExternal_single_structure(
            array(
                'courses' => new Core_externalExternal_multiple_structure(self::get_course_structure(), 'Course')
            )
        );
    }
    protected static function get_course_structure() {
        $coursestructure = array(
            'id' => new Core_externalExternal_value(PARAM_INT, 'course id'),
            'categoryid' => new Core_externalExternal_value(PARAM_INT, 'category id'),
            'parentcat' => new Core_externalExternal_value(PARAM_RAW, 'category name'),
            'sortorder' => new Core_externalExternal_value(PARAM_INT, 'Sort order in the category', VALUE_OPTIONAL),
            'fullname' => new Core_externalExternal_value(PARAM_RAW, 'course full name'),
            'shortname' => new Core_externalExternal_value(PARAM_RAW, 'course short name'),
            'summary' => new Core_externalExternal_value(PARAM_RAW, 'summary'),
            'startdate' => new Core_externalExternal_value(PARAM_INT, 'Timestamp when the course start', VALUE_OPTIONAL),
            'enddate' => new Core_externalExternal_value(PARAM_INT, 'Timestamp when the course end', VALUE_OPTIONAL),
            'lang' => new Core_externalExternal_value(PARAM_SAFEDIR, 'Forced course language', VALUE_OPTIONAL),
            'courseimage' => new Core_externalExternal_value(PARAM_RAW, 'Course image', VALUE_OPTIONAL),
            'arname' => new Core_externalExternal_value(PARAM_RAW, 'arname'),
            'ardesc' => new Core_externalExternal_value(PARAM_RAW, 'ardesc'),
            'course_details' => new Core_externalExternal_value(PARAM_RAW, 'course_details'),
            'course_details_en' => new Core_externalExternal_value(PARAM_RAW, 'course_details_en'),
            'price' => new Core_externalExternal_value(PARAM_RAW, 'price'),
            'short_desc_ar' => new Core_externalExternal_value(PARAM_RAW, 'short_desc_ar'),
            'short_desc_en' => new Core_externalExternal_value(PARAM_RAW, 'short_desc_en'),
            'interprice' => new Core_externalExternal_value(PARAM_TEXT, 'international price'),
            'currency' => new Core_externalExternal_value(PARAM_TEXT, 'currency'),
            'categoryname' => new Core_externalExternal_value(PARAM_RAW, 'category name'),
            'format' => new Core_externalExternal_value(PARAM_RAW, 'format'),
        );
        return new Core_externalExternal_single_structure($coursestructure);
    }
    // protected static function get_course_structure($onlypublicdata = true) {
    //     $coursestructure = array(
    //         'id' => new Core_externalExternal_value(PARAM_INT, 'course id'),
    //         'fullname' => new Core_externalExternal_value(PARAM_RAW, 'course full name'),
    //         'displayname' => new Core_externalExternal_value(PARAM_RAW, 'course display name'),
    //         'shortname' => new Core_externalExternal_value(PARAM_RAW, 'course short name'),
    //         'courseimage' => new Core_externalExternal_value(PARAM_URL, 'Course image', VALUE_OPTIONAL),
    //         'categoryid' => new Core_externalExternal_value(PARAM_INT, 'category id'),
    //         'categoryname' => new Core_externalExternal_value(PARAM_RAW, 'category name'),
    //         'sortorder' => new Core_externalExternal_value(PARAM_INT, 'Sort order in the category', VALUE_OPTIONAL),
    //         'summary' => new Core_externalExternal_value(PARAM_RAW, 'summary'),
    //         'summaryformat' => new external_format_value('summary'),
    //         'summaryfiles' => new external_files('summary files in the summary field', VALUE_OPTIONAL),
    //         'overviewfiles' => new external_files('additional overview files attached to this course'),
    //         'showactivitydates' => new Core_externalExternal_value(PARAM_BOOL, 'Whether the activity dates are shown or not'),
    //         'showcompletionconditions' => new Core_externalExternal_value(PARAM_BOOL,
    //             'Whether the activity completion conditions are shown or not'),
    //         'contacts' => new Core_externalExternal_multiple_structure(
    //             new Core_externalExternal_single_structure(
    //                 array(
    //                     'id' => new Core_externalExternal_value(PARAM_INT, 'contact user id'),
    //                     'fullname'  => new Core_externalExternal_value(PARAM_NOTAGS, 'contact user fullname'),
    //                 )
    //             ),
    //             'contact users'
    //         ),
    //         'enrollmentmethods' => new Core_externalExternal_multiple_structure(
    //             new Core_externalExternal_value(PARAM_PLUGIN, 'enrollment method'),
    //             'enrollment methods list'
    //         ),
    //         'customfields' => new Core_externalExternal_multiple_structure(
    //             new Core_externalExternal_single_structure(
    //                 array(
    //                     'name' => new Core_externalExternal_value(PARAM_RAW, 'The name of the custom field'),
    //                     'shortname' => new Core_externalExternal_value(PARAM_RAW,
    //                         'The shortname of the custom field - to be able to build the field class in the code'),
    //                     'type'  => new Core_externalExternal_value(PARAM_ALPHANUMEXT,
    //                         'The type of the custom field - text field, checkbox...'),
    //                     'valueraw' => new Core_externalExternal_value(PARAM_RAW, 'The raw value of the custom field'),
    //                     'value' => new Core_externalExternal_value(PARAM_RAW, 'The value of the custom field'),
    //                 )
    //             ), 'Custom fields', VALUE_OPTIONAL),
    //              'parentcat' => new Core_externalExternal_value(PARAM_RAW, 'category name'),
    //              'interprice' => new Core_externalExternal_value(PARAM_RAW, 'international price'),
    //     );

    //     if (!$onlypublicdata) {
    //         $extra = array(
    //             'idnumber' => new Core_externalExternal_value(PARAM_RAW, 'Id number', VALUE_OPTIONAL),
    //             'format' => new Core_externalExternal_value(PARAM_PLUGIN, 'Course format: weeks, topics, social, site,..', VALUE_OPTIONAL),
    //             'showgrades' => new Core_externalExternal_value(PARAM_INT, '1 if grades are shown, otherwise 0', VALUE_OPTIONAL),
    //             'newsitems' => new Core_externalExternal_value(PARAM_INT, 'Number of recent items appearing on the course page', VALUE_OPTIONAL),
    //             'startdate' => new Core_externalExternal_value(PARAM_INT, 'Timestamp when the course start', VALUE_OPTIONAL),
    //             'enddate' => new Core_externalExternal_value(PARAM_INT, 'Timestamp when the course end', VALUE_OPTIONAL),
    //             'maxbytes' => new Core_externalExternal_value(PARAM_INT, 'Largest size of file that can be uploaded into', VALUE_OPTIONAL),
    //             'showreports' => new Core_externalExternal_value(PARAM_INT, 'Are activity report shown (yes = 1, no =0)', VALUE_OPTIONAL),
    //             'visible' => new Core_externalExternal_value(PARAM_INT, '1: available to student, 0:not available', VALUE_OPTIONAL),
    //             'groupmode' => new Core_externalExternal_value(PARAM_INT, 'no group, separate, visible', VALUE_OPTIONAL),
    //             'groupmodeforce' => new Core_externalExternal_value(PARAM_INT, '1: yes, 0: no', VALUE_OPTIONAL),
    //             'defaultgroupingid' => new Core_externalExternal_value(PARAM_INT, 'default grouping id', VALUE_OPTIONAL),
    //             'enablecompletion' => new Core_externalExternal_value(PARAM_INT, 'Completion enabled? 1: yes 0: no', VALUE_OPTIONAL),
    //             'completionnotify' => new Core_externalExternal_value(PARAM_INT, '1: yes 0: no', VALUE_OPTIONAL),
    //             'lang' => new Core_externalExternal_value(PARAM_SAFEDIR, 'Forced course language', VALUE_OPTIONAL),
    //             'theme' => new Core_externalExternal_value(PARAM_PLUGIN, 'Fame of the forced theme', VALUE_OPTIONAL),
    //             'marker' => new Core_externalExternal_value(PARAM_INT, 'Current course marker', VALUE_OPTIONAL),
    //             'legacyfiles' => new Core_externalExternal_value(PARAM_INT, 'If legacy files are enabled', VALUE_OPTIONAL),
    //             'calendartype' => new Core_externalExternal_value(PARAM_PLUGIN, 'Calendar type', VALUE_OPTIONAL),
    //             'timecreated' => new Core_externalExternal_value(PARAM_INT, 'Time when the course was created', VALUE_OPTIONAL),
    //             'timemodified' => new Core_externalExternal_value(PARAM_INT, 'Last time  the course was updated', VALUE_OPTIONAL),
    //             'requested' => new Core_externalExternal_value(PARAM_INT, 'If is a requested course', VALUE_OPTIONAL),
    //             'cacherev' => new Core_externalExternal_value(PARAM_INT, 'Cache revision number', VALUE_OPTIONAL),
    //             'filters' => new Core_externalExternal_multiple_structure(
    //                 new Core_externalExternal_single_structure(
    //                     array(
    //                         'filter'  => new Core_externalExternal_value(PARAM_PLUGIN, 'Filter plugin name'),
    //                         'localstate' => new Core_externalExternal_value(PARAM_INT, 'Filter state: 1 for on, -1 for off, 0 if inherit'),
    //                         'inheritedstate' => new Core_externalExternal_value(PARAM_INT, '1 or 0 to use when localstate is set to inherit'),
    //                     )
    //                 ),
    //                 'Course filters', VALUE_OPTIONAL
    //             ),
    //             'courseformatoptions' => new Core_externalExternal_multiple_structure(
    //                 new Core_externalExternal_single_structure(
    //                     array(
    //                         'name' => new Core_externalExternal_value(PARAM_RAW, 'Course format option name.'),
    //                         'value' => new Core_externalExternal_value(PARAM_RAW, 'Course format option value.'),
    //                     )
    //                 ),
    //                 'Additional options for particular course format.', VALUE_OPTIONAL
    //             ),
    //         );
    //         $coursestructure = array_merge($coursestructure, $extra);
    //     }
    //     return new Core_externalExternal_single_structure($coursestructure);
    // }
    private static function findcatName($cat, $id)
    {
        foreach ($cat as $ca) {
            if ($ca->id == $id)
                return $ca->name;
        }
        return 'uncategorized';
    }
    private static function findcatid($cat, $name)
    {
        foreach ($cat as $ca) {
            if ($ca->name == $name)
                return $ca->id;
        }
        return 0;
    }
    private static function findprice($cid, $cprices){
        foreach($cprices as $c){
            if($cid == $c->courseid){
                return $c->cost."$";
            }
        }
        return null;
    }
}
