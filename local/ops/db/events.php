<?php


defined('MOODLE_INTERNAL') || die();

    $observers = array(
		//Users
		array(
            'eventname' => '\core\event\user_created',
            'callback' => 'local_ops_observer::user_created',
        ),
		array(
            'eventname' => '\core\event\user_deleted',
            'callback' => 'local_ops_observer::user_deleted',
        ),
		array(
            'eventname' => '\core\event\user_password_updated',
            'callback' => 'local_ops_observer::user_password_updated',
        ),
		array(
            'eventname' => 'core\event\user_updated',
            'callback' => 'local_ops_observer::user_updated',
        ),
		
		//Chapters
		array(
            'eventname' => '\mod_book\event\chapter_created',
            'callback' => 'local_ops_observer::chapter_created',
        ),
		array(
            'eventname' => '\mod_book\event\chapter_deleted',
            'callback' => 'local_ops_observer::chapter_deleted',
        ),
		array(
            'eventname' => '\mod_book\event\chapter_updated',
            'callback' => 'local_ops_observer::chapter_updated',
        ),
		
		//Course
		array(
            'eventname' => '\core\event\course_created',
            'callback' => 'local_ops_observer::course_created',
        ),
		array(
            'eventname' => '\core\event\course_deleted',
            'callback' => 'local_ops_observer::course_deleted',
        ),
		array(
            'eventname' => '\core\event\course_module_created',
            'callback' => 'local_ops_observer::course_module_created',
        ),
		array(
            'eventname' => '\core\event\course_module_deleted',
            'callback' => 'local_ops_observer::course_module_deleted',
        ),
		array(
            'eventname' => '\core\event\course_module_updated',
            'callback' => 'local_ops_observer::course_module_updated',
        ),
		array(
            'eventname' => '\core\event\course_restored',
            'callback' => 'local_ops_observer::course_restored',
        ),
		array(
            'eventname' => '\core\event\course_updated',
            'callback' => 'local_ops_observer::course_updated',
        ),
        array(
            'eventname' => '\core\event\course_section_updated',
            'callback' => 'local_ops_observer::section_updated'
        ),
        array(
            'eventname' => '\core\event\course_section_created',
            'callback' => 'local_ops_observer::section_created'
        ),
    );