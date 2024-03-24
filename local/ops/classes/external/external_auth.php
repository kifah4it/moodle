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

class external_auth extends \core_external\external_api
{
    public static function login_by_userkey_parameters()
    {
        return new Core_externalExternal_function_parameters(
            array(
                'userid' => new Core_externalExternal_value(PARAM_TEXT, 'username or email or mobile number'),
                'password' => new Core_externalExternal_value(PARAM_TEXT, 'password'),
            )
        );
    }
    public static function login_by_userkey($userid,$password)
    {
        global $DB;
        $useridfiled = $userid;
        $password = $password;
        $sql = "SELECT u.username,u.email,u.firstname,u.lastname,ud.data as m_num, u.password FROM `mdl_user` u INNER JOIN `mdl_user_info_data` ud ON ud.userid = u.id INNER JOIN `mdl_user_info_field` uf ON uf.id = ud.fieldid
        WHERE (u.username = '" . $useridfiled . "' or u.email = '" . $useridfiled . "' or (uf.shortname = 'm_num' AND ud.data = '" . $useridfiled . "')) AND uf.shortname = 'm_num'" . "";
        $rs = $DB->get_recordset_sql($sql);
        $result = array();
        foreach ($rs as $r) {
            if (password_verify($password, $r->password)) {
                $result['status'] = true;
                $result['loginurl'] = self::get_userkey_loginurl($r->username);
                $result['username'] = $r->username;
                $result['email'] = $r->email;
                $result['fname'] = $r->firstname;
                $result['lname'] = $r->lastname;
                $result['m_num'] = $r->m_num;
                return $result;
            }
            else{
                $result['status'] = false;
                $result['loginurl'] = 'incorrect password';
                $result['username'] = '';
                $result['email'] = '';
                $result['fname'] = '';
                $result['lname'] = '';
                $result['m_num'] = '';
                return $result;
            }
        }
                $result['status'] = false;
                $result['loginurl'] = 'incorrect userid';
                $result['username'] = '';
                $result['email'] = '';
                $result['fname'] = '';
                $result['lname'] = '';
                $result['m_num'] = '';
                return $result;
    }
    private static function get_userkey_loginurl($username)
    {
        $resp = exterapi::call_external_function('auth_userkey_request_login_url',['user'=>['username' => $username]]);
        return $resp['data']['loginurl'];
    }
    public static function login_by_userkey_returns()
    {
        return new Core_externalExternal_single_structure(
            array(
                'status' => new Core_externalExternal_value(PARAM_BOOL, 'success or not'),
                'loginurl' => new Core_externalExternal_value(PARAM_RAW, 'login url with generated token'),
                'username' => new Core_externalExternal_value(PARAM_TEXT, 'username'),
                'email' => new Core_externalExternal_value(PARAM_TEXT, 'email'),
                'fname' => new Core_externalExternal_value(PARAM_TEXT, 'fname'),
                'lname' => new Core_externalExternal_value(PARAM_TEXT, 'lname'),
                'm_num' => new Core_externalExternal_value(PARAM_TEXT, 'm_num'),
            )
        );
    }
}
