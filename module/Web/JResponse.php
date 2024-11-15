<?php

class JResponse extends JObject
{

    public int $status; // True(1) / False(0)
    public string $message; // Display Message
    public mixed $data;

    public function __construct(int $status = 0, string $message = "")
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = null;
    }

    public function SetErrorMessage(string $Message)
    {
        $this->status = 0;
        $this->message = $Message;
        $this->data = null;
    }

    public static function toErrorJsonResponse(string $Message)
    {
        $res = new JResponse();
        $res->SetErrorMessage($Message);
        $res->toJsonResponse();
        unset($res);
    }

    public static function toSuccessJsonResponse(string $message, mixed $Data = null)
    {
        $res = new JResponse();
        $res->status = 1;
        $res->message = $message;
        $res->data = $Data;
        $res->toJsonResponse();
        unset($res);
    }

}