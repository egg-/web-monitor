<?php
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
