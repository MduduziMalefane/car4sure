<?php

function WriteLog($errno, $errstr, $filedes, $extra_headers)
{
    $LogFileName = 'ErrorLog';
    $erroText = date('Y-m-d H:i:s') . "\r\n\r\n$errno: $errstr $filedes, $extra_headers\r\n\r\n";

    $eb = new Exception();

    $erroText .= $eb->getTraceAsString() . "\r\n\r\n";
    $erroText .= "----------------------------------------------------------------------------------- \r\n\r\n";

    $FilePath = $GLOBALS['LOGSDIR'] . "$LogFileName.log";
    file_put_contents($FilePath, $erroText, FILE_APPEND);
}

function TraceLog($error, $ShowStack = false)
{
    $TraceFileName = 'TraceLog';
    $erroText = date('Y-m-d H:i:s') . "\r\n\r\n$error\r\n\r\n";

    if ($ShowStack) {
        $eb = new Exception();
        $erroText .= $eb->getTraceAsString() . "\r\n\r\n";
    }

    $erroText .= "----------------------------------------------------------------------------------- \r\n\r\n";

    $FilePath = $GLOBALS['LOGSDIR'] . "$TraceFileName.log";
    file_put_contents($FilePath, $erroText, FILE_APPEND);
}


function WriteFatal()
{
    $LogFileName = 'FatalLog';
    $errfile = "unknown file";
    $errstr = "shutdown";
    $errno = E_CORE_ERROR;
    $errline = 0;

    $error = error_get_last();

    if ($error !== NULL) {
        $errno = $error["type"];
        $errfile = $error["file"];
        $errline = $error["line"];
        $errstr = $error["message"];


        $erroText = date('Y-m-d H:i:s') . "\r\n\r\n$errno: $errfile\r\nLine:$errline\r\n:$errstr \r\n\r\n";
        $erroText .= "----------------------------------------------------------------------------------- \r\n\r\n";

        $FilePath = $GLOBALS['LOGSDIR'] . "$LogFileName.log";
        file_put_contents($FilePath, $erroText, FILE_APPEND);
    }
}