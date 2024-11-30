<?php

/**
 * @package   local_portal
 * @copyright 2022 Elearnified Inc.
 */

$capabilities = array(

    'local/ecommerce:view' => array(
        'riskbitmask' => RISK_PERSONAL,
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'companymanager' => CAP_ALLOW,
            'companydepartmentmanager' => CAP_ALLOW,
            'clientadministrator' => CAP_ALLOW
        ),
    )
);
