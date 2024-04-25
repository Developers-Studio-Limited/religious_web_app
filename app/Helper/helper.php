<?php

use Illuminate\Support\Facades\Response;

function sendSuccess($message, $data) {
    return Response::json(array('status' => 'success', 'successMessage' => $message, 'successData' => $data), 200, [], JSON_NUMERIC_CHECK);
}

function sendError($error_message, $code, $data = null) {
    return Response::json(array('status' => 'error', 'errorMessage' => $error_message, 'data' => $data), 400);
}
function getFileExtensionForBase64($file)
{

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $ext = $finfo->buffer($file) . "\n";
    $ext = strtolower($ext);

    if (strpos($ext, 'png') !== false) {
        return ".png";
    } else if (strpos($ext, 'jpg') !== false) {
        return ".jpg";
    } else if (strpos($ext, 'jpeg') !== false) {
        return ".jpeg";
    } else if (strpos($ext, 'gif') !== false) {
        return ".gif";
    } else if (strpos($ext, 'svg') !== false) {
        return ".svg";
    } else if (strpos($ext, 'bmp') !== false) {
        return ".bmp";
    } else if (strpos($ext, 'webp') !== false) {
        return ".webp";
    } else {
        return ".no-extension";
    }
}



