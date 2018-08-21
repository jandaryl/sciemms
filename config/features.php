<?php

return [
    /*
    |--------------------------------------------------------------------------
    | System Features Setting
    |--------------------------------------------------------------------------
     */

    'multi-language'     =>    env('MULTI_LANGUAGES_ENABLED',   false),
    'form-submissions'   =>    env('FORM_SUBMISSION_ENABLED',   false),
    'form-settings'      =>    env('FORM_SETTING_ENABLED',      false),
    'metas'              =>    env('METAS_ENABLED',             false),
    'redirection'        =>    env('REDIRECION_ENABLED',        false),
];
