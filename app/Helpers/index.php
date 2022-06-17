<?php

if (!function_exists('table_name_of_model')) {
    function table_name_of_model(string $model)
    {
        return with(new $model)->getTable();
    }
}

require "query.php";
