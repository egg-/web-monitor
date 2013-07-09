<?php

/*
 * return $_GET's variable
 */
function hz_env_get($index = null, $default = null)
{
	return null === $index ? $_GET : (isset($_GET[$index]) === false ? $default : $_GET[$index]);
}

/*
 * return $_POST's variable
 */
function hz_env_post($index = null, $default = null)
{
	return null === $index ? $_POST : (isset($_POST[$index]) === false ? $default : $_POST[$index]);
}

/*
 * return $_POST or $_GET
 */
function hz_env_param($index = null, $default = null)
{
	return hz_env_post($index, hz_env_get($index, $default));
}

/*
 * return $_SERVER's variable
 */
function hz_env_server($index = null, $default = null)
{
	return null === $index ? $_SERVER : (isset($_SERVER[$index]) === false ? $default : $_SERVER[$index]);
}

/*
 set header content type
 */
function hz_set_contenttype($type, $charset = 'UTF-8')
{
	switch ($type) {
		case 'javascript':
			@header('Content-Type: text/javascript; charset='.$charset);
			break;

		case 'json':
			@header('Content-Type: application/json; charset='.$charset);
			break;

		case 'xml':
			@header('Content-Type: application/xml; charset='.$charset);
			break;

		case 'css':
			@header('Content-Type: text/css; charset='.$charset);
			break;

		case 'plain':
			@header('Content-Type: text/plain; charset='.$charset);
			break;

		case 'html':
		default:
			@header('Content-Type: text/html; charset='.$charset);
			break;
	}
}

/*
 * output directly
 */
function hz_out($content, $type, $error = false, $pre = '', $post = '')
{
	ob_start('ob_gzhandler');
	$error and @header('HTTP/1.1 '.$error);
	hz_set_contenttype($type);
	$pre and print($pre);
	print($content);
	$post and print($post);
	ob_end_flush();
}


/**
 * output error
 */
function hz_out_error($msg, $type = null, $error = 500)
{
    $output = '';
    switch ($type) {
        case 'javascript':
            $output = 'document.write("'.$msg.'");';
            $error = false;
            break;
        case 'xml':
			is_string($msg) and $msg = array('message'=>$msg);

            $output = array('<?xml version="1.0" encoding="utf-8"?>');
			$output[] = hz_toxml($msg, 'error');
			$output = implode("\n", $output);

            break;
		case 'json':
			$output = is_string($msg) ? $msg : hz_json_encode($msg);
			break;
        default:
            $output = $msg;
            $type = 'html';
            break;
    }
    hz_out($output, $type, $error);
    exit;
}
