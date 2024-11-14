<?php

///
// Code Meanings
// 1: Data Ok
// 2: Data Ok But redirect
// 3: Data ok But Append Html
// 4: Data Ok but no reply
// 5: Data Crit Error
// 6: Data Cirt Error and redirect

class JObject
{

    public function toJsonString()
    {
        return json_encode($this);
    }

    public function toJsonResponse()
    {
        JObject::toJsonResponseExternal($this);
    }

    public static function toJsonStatusResponse($Status, $message)
    {
        http_response_code($Status);
        header("Content-Type: application/json");
        echo $message;
    }

    public static function toJsonResponseExternal($value)
    {
        header("Content-Type: application/json");
        echo json_encode($value);
    }

}
