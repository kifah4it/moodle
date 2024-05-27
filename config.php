<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'mariadb';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'localhost';
$CFG->dbname    = 'moodle';
$CFG->dbuser    = 'root';
$CFG->dbpass    = '';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => 3306,
  'dbsocket' => '',
  'dbcollation' => 'utf8mb4_unicode_ci',
);

$CFG->wwwroot   = 'https://localhost';
$CFG->dataroot  = 'E:\\MoodleWindowsInstaller\\server\\moodledata';
$CFG->dirroot   = 'E:\\MoodleWindowsInstaller\\server\\moodle';
$CFG->admin     = 'admin';

$CFG->directorypermissions = 0777;
$CFG->alternateloginurl = 'http://localhost:8080/ccit/public/login';
$CFG->APP_URL = 'http://localhost:8080/ccit/public';
require_once(__DIR__ . '/lib/setup.php');

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
