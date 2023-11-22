<?php

/*
<COPYRIGHT>

Copyright © 2022-2023, Canyon GBS LLC

All rights reserved.

This file is part of a project developed using Laravel, which is an open-source framework for PHP.
Canyon GBS LLC acknowledges and respects the copyright of Laravel and other open-source
projects used in the development of this solution.

This project is licensed under the Affero General Public License (AGPL) 3.0.
For more details, see https://github.com/canyongbs/assistbycanyongbs/blob/main/LICENSE.

Notice:
- The copyright notice in this file and across all files and applications in this
 repository cannot be removed or altered without violating the terms of the AGPL 3.0 License.
- The software solution, including services, infrastructure, and code, is offered as a
 Software as a Service (SaaS) by Canyon GBS LLC.
- Use of this software implies agreement to the license terms and conditions as stated
 in the AGPL 3.0 License.

For more information or inquiries please visit our website at
https://www.canyongbs.com or contact us via email at legal@canyongbs.com.

</COPYRIGHT>
*/

return [
    'custom' => [
        'auth_profile.edit',
        'record_menu.access',
        'engage_menu.access',
        'service_management_menu.access',
        'support_menu.access',
        'settings_menu.access',
        'kb_menu.access',
        'support_item.create',
        'support_item.edit',
        'support_item.show',
        'support_item.delete',
        'support_item.access',
        'support_training_item.create',
        'support_training_item.edit',
        'support_training_item.show',
        'support_training_item.delete',
        'support_training_item.access',
        'support_feedback_item.create',
        'support_feedback_item.edit',
        'support_feedback_item.show',
        'support_feedback_item.delete',
        'support_feedback_item.access',
        'support_page.create',
        'support_page.edit',
        'support_page.show',
        'support_page.delete',
        'support_page.access',
        'record_enrollment_item.show',
        'record_enrollment_item.access',
        'record_program_item.show',
        'record_program_item.access',
        'record_student_item.show',
        'record_student_item.access',
        'prospect_menu.access',
        'report_menu.access',
        'report_student.create',
        'report_student.edit',
        'report_student.show',
        'report_student.delete',
        'report_student.access',
        'report_prospect.create',
        'report_prospect.edit',
        'report_prospect.show',
        'report_prospect.delete',
        'report_prospect.access',
        'journey_menu.access',
    ],

    'model' => [
        'audit_log' => [
            '*',
        ],
        'service_request' => [
            '*',
        ],
        'service_request_priority' => [
            '*',
        ],
        'service_request_status' => [
            '*',
        ],
        'service_request_type' => [
            '*',
        ],
        'service_request_update' => [
            '*',
        ],
        'engagement_email_item' => [
            '*',
        ],
        'engagement_interaction_driver' => [
            '*',
        ],
        'engagement_interaction_item' => [
            '*',
        ],
        'engagement_interaction_outcome' => [
            '*',
        ],
        'engagement_interaction_relation' => [
            '*',
        ],
        'engagement_interaction_type' => [
            '*',
        ],
        'engagement_student_file' => [
            '*',
        ],
        'engagement_text_item' => [
            '*',
        ],
        'institution' => [
            '*',
        ],
        'journey_email_item' => [
            '*',
        ],
        'journey_item' => [
            '*',
        ],
        'journey_target_list' => [
            '*',
        ],
        'journey_text_item' => [
            '*',
        ],
        'kb_item' => [
            '*',
        ],
        'kb_item_category' => [
            '*',
        ],
        'kb_item_quality' => [
            '*',
        ],
        'kb_item_status' => [
            '*',
        ],
        'prospect_item' => [
            '*',
        ],
        'prospect_source' => [
            '*',
        ],
        'prospect_status' => [
            '*',
        ],
        'record_enrollment_item' => [
            '*',
        ],
        'record_program_item' => [
            '*',
        ],
        'record_student_item' => [
            '*',
        ],
        'support_page' => [
            '*',
        ],
    ],
];
