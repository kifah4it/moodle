<?php

function local_ops_myprofile_navigation(core_user\output\myprofile\tree $tree, $user, $iscurrentuser) {
    global $CFG,$PAGE,$DB;
    $category = new core_user\output\myprofile\category('enrolment_requests',
    get_string('enrolment_requests',$component='local_ops'), null);
    $tree->add_category($category);
    $records = $DB->get_records_sql(
      'SELECT ue.id,ue.userid,ue.enrolid,e.courseid,c.fullname FROM {user_enrolments} ue 
      JOIN {enrol} e ON ue.enrolid = e.id 
      JOIN {course} c ON e.courseid = c.id
      WHERE e.enrol LIKE "apply" AND ue.status != 0 AND ue.userid = ?',
      [
          $user->id,
      ]
  );
  $content="";
  if(count($records)>0){
  $actionurl = $CFG->wwwroot.'/enrol/apply/manage.php';
  $content='<form id="enrol_apply_manage_form" method="post" action='.'"'.$actionurl.'"'.'>';
  $content.='<input id="formaction" name="formaction" type="hidden" value="confirm">';
  $content.='<ul style="margin:20px">';
  foreach($records as $rec){
   $content.='<li>'.'<input type="checkbox" value="'.$rec->id.'" name="userenrolments[]" style="margin:20px">'
   .$rec->fullname.'</li>';
  }
    $content.='</ul>';
    $content.='<input type="submit" class="btn btn-primary" value='.'"'.get_string('accept_register',$component='local_ops').'"'.'/>';
    $content.='</form>';
}
    $node = new core_user\output\myprofile\node('enrolment_requests','enrolment_requests',
      null, null, null,$content);
       $tree->add_node($node);
       return true;
}
