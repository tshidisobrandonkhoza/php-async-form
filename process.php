<?php

sleep(2);

function is_ajax_request()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
}

$errors = [];
$length = isset($_POST['length']) ? (int) $_POST['length'] : '';
$width = isset($_POST['width']) ? (int) $_POST['width'] : '';
$height = isset($_POST['height']) ? (int) $_POST['height'] : '';

if ($length == '')
{
    $errors[] = 'length';
}
if ($width == '')
{
    $errors[] = 'width';
}
if ($height == '')
{
    $errors[] = 'height';
}


if (!empty($errors))
{
    echo json_encode(['errors' => $errors]);
}
else
{

    $volume = $length * $width * $height;

    if (is_ajax_request())
    {
        echo json_encode(['volume' => $volume]);
    }
    else
    {
        exit;
    }
}
