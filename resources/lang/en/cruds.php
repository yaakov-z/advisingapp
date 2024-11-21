<?php

/*
<COPYRIGHT>

    Copyright © 2016-2024, Canyon GBS LLC. All rights reserved.

    Advising App™ is licensed under the Elastic License 2.0. For more details,
    see https://github.com/canyongbs/advisingapp/blob/main/LICENSE.

    Notice:

    - You may not provide the software to third parties as a hosted or managed
      service, where the service provides users with access to any substantial set of
      the features or functionality of the software.
    - You may not move, change, disable, or circumvent the license key functionality
      in the software, and you may not remove or obscure any functionality in the
      software that is protected by the license key.
    - You may not alter, remove, or obscure any licensing, copyright, or other notices
      of the licensor in the software. Any use of the licensor’s trademarks is subject
      to applicable law.
    - Canyon GBS LLC respects the intellectual property rights of others and expects the
      same in return. Canyon GBS™ and Advising App™ are registered trademarks of
      Canyon GBS LLC, and we are committed to enforcing and protecting our trademarks
      vigorously.
    - The software solution, including services, infrastructure, and code, is offered as a
      Software as a Service (SaaS) by Canyon GBS LLC.
    - Use of this software implies agreement to the license terms and conditions as stated
      in the Elastic License 2.0.

    For more information or inquiries please visit our website at
    https://www.canyongbs.com or contact us via email at legal@canyongbs.com.

</COPYRIGHT>
*/

return [
    'userManagement' => [
        'title' => 'User management',
        'title_singular' => 'User management',
    ],
    'permission' => [
        'title' => 'Permissions',
        'title_singular' => 'Permission',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'title' => 'Title',
            'title_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'role' => [
        'title' => 'Roles',
        'title_singular' => 'Role',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'title' => 'Title',
            'title_helper' => ' ',
            'permissions' => 'Permissions',
            'permissions_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'user' => [
        'title' => 'Users',
        'title_singular' => 'User',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'name' => 'Name',
            'name_helper' => ' ',
            'email' => 'Email',
            'email_helper' => ' ',
            'email_verified_at' => 'Email verified at',
            'email_verified_at_helper' => ' ',
            'password' => 'Password',
            'password_helper' => ' ',
            'roles' => 'Roles',
            'roles_helper' => ' ',
            'remember_token' => 'Remember Token',
            'remember_token_helper' => ' ',
            'locale' => 'Locale',
            'locale_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
            'emplid' => 'Employee ID',
            'emplid_helper' => ' ',
            'type' => 'Type',
            'type_helper' => ' ',
        ],
    ],
    'auditLog' => [
        'title' => 'Audit Logs',
        'title_singular' => 'Audit Log',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'description' => 'Event',
            'description_helper' => ' ',
            'subject_id' => 'Subject ID',
            'subject_id_helper' => ' ',
            'subject_type' => 'Subject Type',
            'subject_type_helper' => ' ',
            'user_id' => 'User ID',
            'user_id_helper' => ' ',
            'properties' => 'Attributes',
            'properties_helper' => ' ',
            'host' => 'IP',
            'host_helper' => ' ',
            'created_at' => 'Event time',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
        ],
    ],
    'userAlert' => [
        'title' => 'User Alerts',
        'title_singular' => 'User Alert',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'message' => 'Message',
            'message_helper' => ' ',
            'link' => 'Link',
            'link_helper' => ' ',
            'users' => 'Users',
            'users_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'recordMenu' => [
        'title' => 'Records',
        'title_singular' => 'Record',
    ],
    'engageMenu' => [
        'title' => 'Engagement',
        'title_singular' => 'Engagement',
    ],
    'serviceManagementMenu' => [
        'title' => 'Service Management',
        'title_singular' => 'Service Management',
    ],
    'supportMenu' => [
        'title' => 'Support',
        'title_singular' => 'Support',
    ],
    'settingsMenu' => [
        'title' => 'Field Settings',
        'title_singular' => 'Field Setting',
    ],
    'institution' => [
        'title' => 'Institution',
        'title_singular' => 'Institution',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'code' => 'Code',
            'code_helper' => ' ',
            'name' => 'Name',
            'name_helper' => ' ',
            'description' => 'Description',
            'description_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'serviceRequest' => [
        'title' => 'Service Requests',
        'title_singular' => 'Service Request',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'close_details' => 'Close Details/Description',
            'close_details_helper' => ' ',
            'res_details' => 'Internal Service Request Details',
            'res_details_helper' => ' ',
            'created_by' => 'Created By',
            'created_by_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
            'service_request_number' => 'Service Request Number',
            'service_request_number_helper' => ' ',
            'student' => 'Student',
            'student_helper' => ' ',
            'institution' => 'Institution',
            'institution_helper' => ' ',
            'state' => 'State',
            'state_helper' => ' ',
            'type' => 'Type',
            'type_helper' => ' ',
            'priority' => 'Priority',
            'priority_helper' => ' ',
            'assigned_to' => 'Assigned To',
            'assigned_to_helper' => ' ',
        ],
    ],
    'serviceRequestStatus' => [
        'title' => 'Service Request Statuses',
        'title_singular' => 'Service Request Status',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'status' => 'Status',
            'status_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'serviceRequestType' => [
        'title' => 'Service Request Type',
        'title_singular' => 'Service Request Type',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'type' => 'Type',
            'type_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'serviceRequestPriority' => [
        'title' => 'Service Request Priorities',
        'title_singular' => 'Service Request Priority',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'priority' => 'Priority',
            'priority_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'rhMenu' => [
        'title' => 'Resource Hub',
        'title_singular' => 'Resource Hub',
    ],
    'rhItem' => [
        'title' => 'RH Article',
        'title_singular' => 'RH Article',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'question' => 'Question/Issue/Feature',
            'question_helper' => ' ',
            'solution' => 'Solution',
            'solution_helper' => ' ',
            'notes' => 'Internal Notes',
            'notes_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
            'quality' => 'Quality',
            'quality_helper' => ' ',
            'status' => 'Status',
            'status_helper' => ' ',
            'public' => 'Public',
            'public_helper' => ' ',
            'category' => 'Category',
            'category_helper' => ' ',
            'institution' => 'Institution',
            'institution_helper' => ' ',
        ],
    ],
    'rhItemQuality' => [
        'title' => 'RH Quality',
        'title_singular' => 'RH Quality',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'rating' => 'Rating',
            'rating_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'rhItemStatus' => [
        'title' => 'RH Statuses',
        'title_singular' => 'RH Status',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'status' => 'Status',
            'status_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'rhItemCategory' => [
        'title' => 'RH Categories',
        'title_singular' => 'RH Category',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'category' => 'Category',
            'category_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'engagementInteractionItem' => [
        'title' => 'Interactions',
        'title_singular' => 'Interaction',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'direction' => 'Direction',
            'direction_helper' => ' ',
            'start' => 'Start',
            'start_helper' => ' ',
            'duration' => 'Duration',
            'duration_helper' => ' ',
            'subject' => 'Subject',
            'subject_helper' => ' ',
            'description' => 'Description',
            'description_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'engagementInteractionType' => [
        'title' => 'Interaction Type',
        'title_singular' => 'Interaction Type',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'type' => 'Type',
            'type_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'engagementInteractionRelation' => [
        'title' => 'Interaction Relations',
        'title_singular' => 'Interaction Relation',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'relation' => 'Relation',
            'relation_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'engagementInteractionDriver' => [
        'title' => 'Interaction Drivers',
        'title_singular' => 'Interaction Driver',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'driver' => 'Drivers',
            'driver_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'engagementInteractionOutcome' => [
        'title' => 'Interaction Outcomes',
        'title_singular' => 'Interaction Outcome',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'outcome' => 'Outcome',
            'outcome_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'supportItem' => [
        'title' => 'Request Help',
        'title_singular' => 'Request Help',
    ],
    'supportTrainingItem' => [
        'title' => 'Training',
        'title_singular' => 'Training',
    ],
    'supportFeedbackItem' => [
        'title' => 'Feedback',
        'title_singular' => 'Feedback',
    ],
    'supportPage' => [
        'title' => 'Support Pages',
        'title_singular' => 'Support Page',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'title' => 'Title',
            'title_helper' => ' ',
            'body' => 'Body',
            'body_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'recordEnrollmentItem' => [
        'title' => 'Student Enrollments',
        'title_singular' => 'Student Enrollment',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'sisid' => 'SIS ID',
            'sisid_helper' => ' ',
            'name' => 'Enrollment Name',
            'name_helper' => ' ',
            'start' => 'Start',
            'start_helper' => ' ',
            'end' => 'End',
            'end_helper' => ' ',
            'course' => 'Course',
            'course_helper' => ' ',
            'grade' => 'Grade',
            'grade_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'recordProgramItem' => [
        'title' => 'Assigned Programs',
        'title_singular' => 'Assigned Program',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'name' => 'Name',
            'name_helper' => ' ',
            'institution' => 'Institution',
            'institution_helper' => ' ',
            'plan' => 'Plan',
            'plan_helper' => ' ',
            'career' => 'Career',
            'career_helper' => ' ',
            'term' => 'Term',
            'term_helper' => ' ',
            'status' => 'Status',
            'status_helper' => ' ',
            'foi' => 'FOI',
            'foi_helper' => ' ',
            'gpa' => 'GPA',
            'gpa_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'recordStudentItem' => [
        'title' => 'Students',
        'title_singular' => 'Student',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'sisid' => 'SIS ID',
            'sisid_helper' => ' ',
            'otherid' => 'Other ID',
            'otherid_helper' => ' ',
            'first' => 'First',
            'first_helper' => ' ',
            'last' => 'Last',
            'last_helper' => ' ',
            'full' => 'Full',
            'full_helper' => ' ',
            'preferred' => 'Preferred',
            'preferred_helper' => ' ',
            'email' => 'School Email',
            'email_helper' => ' ',
            'email_2' => 'Personal Email',
            'email_2_helper' => ' ',
            'mobile' => 'Mobile',
            'mobile_helper' => ' ',
            'sms_opt_out' => 'SMS Opt Out',
            'sms_opt_out_helper' => ' ',
            'email_bounce' => 'Email Bounce',
            'email_bounce_helper' => ' ',
            'phone' => 'Other Phone',
            'phone_helper' => ' ',
            'address' => 'Address',
            'address_helper' => ' ',
            'address_2' => 'Address 2',
            'address_2_helper' => ' ',
            // TODO Can this be Date of Birth?
            'birthdate' => 'Birthdate',
            'birthdate_helper' => ' ',
            'dual' => 'Dual',
            'dual_helper' => ' ',
            'ferpa' => 'FERPA',
            'ferpa_helper' => ' ',
            'gpa' => 'GPA',
            'gpa_helper' => ' ',
            'dfw' => 'DFW',
            'dfw_helper' => ' ',
            'firstgen' => 'First Generation',
            'firstgen_helper' => ' ',
            'ethnicity' => 'Ethnicity',
            'ethnicity_helper' => ' ',
            'lastlmslogin' => 'Last LMS Login',
            'lastlmslogin_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
            'hsgrad' => 'HS Grad Year',
            'hsgrad_helper' => ' ',
        ],
    ],
    'engagementEmailItem' => [
        'title' => 'Emails',
        'title_singular' => 'Email',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'subject' => 'Subject',
            'subject_helper' => ' ',
            'body' => 'Body',
            'body_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
            'email' => 'Email',
            'email_helper' => ' ',
        ],
    ],
    'engagementTextItem' => [
        'title' => 'Text Messages',
        'title_singular' => 'Text Message',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'direction' => 'Direction',
            'direction_helper' => ' ',
            'mobile' => 'Mobile',
            'mobile_helper' => ' ',
            'message' => 'Message',
            'message_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'engagementStudentFile' => [
        'title' => 'Documents',
        'title_singular' => 'Document',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'file' => 'Files',
            'file_helper' => 'Each file must be smaller than 2 MB.',
            'description' => 'Description',
            'description_helper' => ' ',
            'student' => 'Student',
            'student_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'prospectMenu' => [
        'title' => 'Prospect Menu',
        'title_singular' => 'Prospect Menu',
    ],
    'prospectItem' => [
        'title' => 'Prospective Student',
        'title_singular' => 'Prospective Student',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'first' => 'First',
            'first_helper' => ' ',
            'last' => 'Last',
            'last_helper' => ' ',
            'full' => 'Full Name',
            'full_helper' => ' ',
            'preferred' => 'Preferred',
            'preferred_helper' => ' ',
            'description' => 'Description',
            'description_helper' => ' ',
            'email' => 'Primary Email',
            'email_helper' => ' ',
            'email_2' => 'Other Email',
            'email_2_helper' => ' ',
            'mobile' => 'Mobile',
            'mobile_helper' => ' ',
            'sms_opt_out' => 'SMS Opt Out',
            'sms_opt_out_helper' => ' ',
            'email_bounce' => 'Email Bounce',
            'email_bounce_helper' => ' ',
            'phone' => 'Other Phone',
            'phone_helper' => ' ',
            'address' => 'Address',
            'address_helper' => ' ',
            'address_2' => 'Address 2',
            'address_2_helper' => ' ',
            'birthdate' => 'Birthdate',
            'birthdate_helper' => ' ',
            'hsgrad' => 'HS Grad Year',
            'hsgrad_helper' => ' ',
            'hsdate' => 'HS Grad Date',
            'hsdate_helper' => ' ',
            'assigned_to' => 'Assigned To',
            'assigned_to_helper' => ' ',
            'created_by' => 'Created By',
            'created_by_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
            'status' => 'Status',
            'status_helper' => ' ',
            'source' => 'Source',
            'source_helper' => ' ',
        ],
    ],
    'serviceRequestUpdate' => [
        'title' => 'Updates',
        'title_singular' => 'Update',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'student' => 'Student',
            'student_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
            'service_request' => 'Service Request',
            'service_request_helper' => ' ',
            'update' => 'Update',
            'update_helper' => ' ',
            'internal' => 'Internal',
            'internal_helper' => ' ',
            'direction' => 'Direction',
            'direction_helper' => ' ',
        ],
    ],
    'reportMenu' => [
        'title' => 'Reporting',
        'title_singular' => 'Reporting',
    ],
    'reportStudent' => [
        'title' => 'Student Reporting',
        'title_singular' => 'Student Reporting',
    ],
    'reportProspect' => [
        'title' => 'Prospect Reporting',
        'title_singular' => 'Prospect Reporting',
    ],
    'journeyMenu' => [
        'title' => 'Journeys',
        'title_singular' => 'Journey',
    ],
    'journeyItem' => [
        'title' => 'Email Campaigns',
        'title_singular' => 'Email Campaign',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'name' => 'Name',
            'name_helper' => ' ',
            'body' => 'Body',
            'body_helper' => ' ',
            'start' => 'Start',
            'start_helper' => ' ',
            'end' => 'End',
            'end_helper' => ' ',
            'frequency' => 'Frequency',
            'frequency_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'journeyEmailItem' => [
        'title' => 'Email Campaign',
        'title_singular' => 'Email Campaign',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'name' => 'Name',
            'name_helper' => ' ',
            'body' => 'Email Body',
            'body_helper' => ' ',
            'start' => 'Start',
            'start_helper' => ' ',
            'end' => 'End',
            'end_helper' => ' ',
            'active' => 'Active',
            'active_helper' => ' ',
            'frequency' => 'Frequency',
            'frequency_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'journeyTextItem' => [
        'title' => 'Test Campaign',
        'title_singular' => 'Test Campaign',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'name' => 'Name',
            'name_helper' => ' ',
            'text' => 'Text',
            'text_helper' => 'You may send a text message up to 159 characters.',
            'start' => 'Start',
            'start_helper' => ' ',
            'end' => 'End',
            'end_helper' => ' ',
            'active' => 'Active',
            'active_helper' => ' ',
            'frequency' => 'Frequency',
            'frequency_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'journeyTargetList' => [
        'title' => 'Journey Query',
        'title_singular' => 'Journey Query',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'name' => 'Name',
            'name_helper' => ' ',
            'description' => 'Description',
            'description_helper' => ' ',
            'query' => 'Query',
            'query_helper' => ' ',
            'population' => 'Est Pop',
            'population_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'prospectStatus' => [
        'title' => 'Prospect Status',
        'title_singular' => 'Prospect Status',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'status' => 'Status',
            'status_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'prospectSource' => [
        'title' => 'Prospect Source',
        'title_singular' => 'Prospect Source',
        'fields' => [
            'id' => 'ID',
            'id_helper' => ' ',
            'source' => 'Source',
            'source_helper' => ' ',
            'created_at' => 'Created at',
            'created_at_helper' => ' ',
            'updated_at' => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at' => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
];
