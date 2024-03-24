<?php
$functions = [
    // The name of your web service function, as discussed above.
    'local_ops_enroll_student' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_ops\external\register_enroll_student',
        'methodname' => 'enroll_student',
        // A brief, human-readable, description of the web service function.
        'description' => 'Register a new user',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            MOODLE_OFFICIAL_MOBILE_SERVICE,
        ]
    ],
    'local_ops_register_student' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_ops\external\register_enroll_student',
        'methodname' => 'register_student',
        // A brief, human-readable, description of the web service function.
        'description' => 'Enroll them in one or many coruses with multi enrollment methods.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            MOODLE_OFFICIAL_MOBILE_SERVICE,
        ]
    ],
    'local_ops_get_courses_with_parent_cat' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_ops\external\get_courses_with_parent_cat',
        'methodname' => 'get_courses_with_parent_cat',
        // A brief, human-readable, description of the web service function.
        'description' => 'get courses with / without category parameter & with course category parent',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            MOODLE_OFFICIAL_MOBILE_SERVICE,
        ]
        ],
        'local_ops_check_course_prereq' => [
            // The name of the namespaced class that the function is located in.
            'classname'   => 'local_ops\external\Course_availability',
            'methodname' => 'check_course_prereq',
            // A brief, human-readable, description of the web service function.
            'description' => 'Check if a student can enorl in one or more courses depend on courses prerequists',
    
            // Options include read, and write.
            'type'        => 'write',
    
            // Whether the service is available for use in AJAX calls from the web.
            'ajax'        => true,
    
            // An optional list of services where the function will be included.
            'services' => [
                // A standard Moodle install includes one default service:
                // - MOODLE_OFFICIAL_MOBILE_SERVICE.
                // Specifying this service means that your function will be available for
                // use in the Moodle Mobile App.
                MOODLE_OFFICIAL_MOBILE_SERVICE,
            ]
            ],
            'local_ops_login_by_userkey' => [
                // The name of the namespaced class that the function is located in.
                'classname'   => 'local_ops\external\external_auth',
                'methodname' => 'login_by_userkey',
                // A brief, human-readable, description of the web service function.
                'description' => 'check user credentials and generate userkey token. You can use username, email or mobile number to identify user',
        
                // Options include read, and write.
                'type'        => 'write',
        
                // Whether the service is available for use in AJAX calls from the web.
                'ajax'        => true,
        
                // An optional list of services where the function will be included.
                'services' => [
                    // A standard Moodle install includes one default service:
                    // - MOODLE_OFFICIAL_MOBILE_SERVICE.
                    // Specifying this service means that your function will be available for
                    // use in the Moodle Mobile App.
                    MOODLE_OFFICIAL_MOBILE_SERVICE,
                ]
            ]

];