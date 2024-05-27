<?php
require('../../../config.php');
global $USER;
$external_url = $CFG->APP_URL."/courses/English";
echo "<div style='width:100%;text-align:center'>";
echo "<h2>Congratulation!<h2><br>";
$sql = "SELECT q.name,q.grade,q.sumgrades,(SELECT MAX(grade) from `mdl_quiz_grades` WHERE quiz = q.id AND userid = '".$USER->id."') 
as student_grade FROM `mdl_quiz` q INNER JOIN `mdl_course` c on c.id = q.course 
WHERE c.shortname LIKE 'englishPT'";
$rs = $DB->get_recordset_sql($sql);
$totalgrade = 0;
$sumgrades = 0;
foreach($rs as $r){
    $totalgrade += $r->student_grade;
    $sumgrades += $r->sumgrades;
    echo $r->name." : ". number_format($r->student_grade,2) ." / " . number_format($r->sumgrades,2) . "<br>";
}
$totalgrade = number_format($totalgrade,2);
echo "<p>Your Result is: ".$totalgrade."% </p>";

$sql = "SELECT cc.course,c.fullname,cc.gradepass, (SELECT CONCAT( 'pluginfile.php/',f.contextid,'/',f.component,'/',f.filearea,'/',f.filename) as img FROM `mdl_files` f INNER JOIN `mdl_context` ctx on ctx.id = f.contextid AND ctx.instanceid = c.id  AND f.mimetype LIKE 'image%' AND f.filename <> '.' LIMIT 1) as courseimage
FROM `mdl_course_completion_criteria` cc INNER JOIN `mdl_course` c on c.id = cc.course 
WHERE cc.courseinstance = (SELECT id FROM `mdl_course` WHERE fullname = 'English Placement Test') AND cc.gradepass <= $totalgrade
ORDER BY gradepass DESC
LIMIT 1;";
$rs = $DB->get_recordset_sql($sql);
foreach($rs as $r){
    echo "<p>$r->fullname</p>";
    echo "<img src='$CFG->wwwroot/$r->courseimage' width='150px' /><br>";
    echo "<a class='btn btn-success' href='$CFG->APP_URL/course/$r->fullname' target='_blank'>Enroll now</a>";

}
echo "</div>";
