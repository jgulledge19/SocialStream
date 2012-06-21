<?php
/**
 * This file is useless just calls on another file that calls on an unnessicary file that then does the work!
 * This is confussing and just a preferance for organization 
 */
$output = include dirname(__FILE__).'/controllers/index.php';
return $output;