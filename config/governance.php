<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Governance change approval
    |--------------------------------------------------------------------------
    |
    | Change Requests are approval-required by default. Disable only for small
    | local/demo setups where documenting the change is enough.
    |
    */
    'change_approval_required' => filter_var(
        env('GOVERNANCE_CHANGE_APPROVAL_REQUIRED', true),
        FILTER_VALIDATE_BOOLEAN,
    ),
];
