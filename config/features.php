<?php

return [
    /*
    |--------------------------------------------------------------------------
    | System Features Setting
    |--------------------------------------------------------------------------
     */

    'multi-language'     =>    env('MULTI_LANGUAGES_ENABLED', true),
    'form-submissions'   =>    env('FORM_SUBMISSION_ENABLED', true),
    'form-settings'      =>    env('FORM_SETTING_ENABLED', true),
    'metas'              =>    env('METAS_ENABLED', true),
    'redirection'        =>    env('REDIRECION_ENABLED', true),
];
