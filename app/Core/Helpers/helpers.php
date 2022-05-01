<?php

if (!function_exists('getEloquentSqlWithBindings')) {
    function getEloquentSqlWithBindings($query)
    {
        return vsprintf(str_replace('?', '%s', $query->toSql()),
            collect($query->getBindings())->map(function($binding) {
                return is_numeric($binding) ? $binding : "'{$binding}'";
            })->toArray());
    }
}
