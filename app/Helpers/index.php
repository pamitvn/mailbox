<?php

if (!function_exists('table_name_of_model')) {
    function table_name_of_model(string $model)
    {
        return with(new $model)->getTable();
    }
}

if (!function_exists('dispatch_action')) {
    function dispatch_action($dispatch): mixed
    {
        $result = Arr::first(array_filter($dispatch, function ($val) {
            return !is_null($val);
        }));


        if ($result) {
            return $result;
        }

        abort(404);
    }
}


require "query.php";
