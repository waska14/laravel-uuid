<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Uuid column default name in database
    |--------------------------------------------------------------------------
    |
     */
    "default_column_name"  => "uuid",

    /*
    |--------------------------------------------------------------------------
    | Uuid default version when using Waska\Uuid::get() function
    |--------------------------------------------------------------------------
    |
    | By default version is set to 4, which is generating pseudo-random uuid strings (without passing parameters).
    | If you change this value to 3 or to 5, you must pass $name (you can pass $namespace too) parameter to get() function, see below.
    |
    | Waska\Uuid::get(3, "some_random_string")
    |
    | or you can pass $namespace also (if not, package will use values below 'v3_default_namespace' or 'v5_default_namespace')
    |
    | Waska\Uuid::get(3, "some_random_string", "valid_uuid_string")
    |
    | Note !!!!     > From: http://php.net/manual/en/function.uniqid.php#94959
    | For versions 3 and 5 given the same namespace and name, the output is always the same.
    |
     */
    "default_version"      => 4,

    /*
    |--------------------------------------------------------------------------
    | Default namespace for version 3
    |--------------------------------------------------------------------------
    |
    | Note: When generating v3 uuid, for the same string ($name) the output will be the same always.
    |
     */
    "v3_default_namespace" => "ed6e1dbc-b3ea-4650-b1e2-5167ba8f0d5c",

    /*
    |--------------------------------------------------------------------------
    | Default namespace for version 5
    |--------------------------------------------------------------------------
    |
    | Note: When generating v5 uuid, for the same string ($name) the output will be the same always.
    |
     */
    "v5_default_namespace" => "d7d40f7f-6fbf-4aee-883f-3118203be187",
];
