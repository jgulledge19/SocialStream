<?php

// need SimplePie
if (!class_exists('SimplePie')) {
    require_once(ABSPATH . WPINC . '/class-simplepie.php');
}

global $socialStream;

/*
 * Functions
 */
function lifestream_path_join() {
    $bits = func_get_args();
    $sep = (in_array(PHP_OS, array("WIN32", "WINNT")) ? '\\' : '/');
    foreach ($bits as $key=>$value) {
        $bits[$key] = rtrim($value, $sep);
    }
    return implode($sep, $bits);
}
function lifestream_array_key_pop($array, $key, $default=null) {
    $value = @$array[$key];
    unset($array[$key]);
    if (!$value) $value = $default;
    return $value;
}
// Returns the utf string corresponding to the unicode value (from php.net, courtesy - romans@void.lv)
function lifestream_code2utf($num) {
    if ($num < 128) return chr($num);
    if ($num < 2048) return chr(($num >> 6) + 192) . chr(($num & 63) + 128);
    if ($num < 65536) return chr(($num >> 12) + 224) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
    if ($num < 2097152) return chr(($num >> 18) + 240) . chr((($num >> 12) & 63) + 128) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
    return '';
}
function lifestream_str_startswith($string, $chunk) {
    return substr($string, 0, strlen($chunk)) == $chunk;
}
function lifestream_str_endswith($string, $chunk) {
    return substr($string, strlen($chunk)*-1) == $chunk;
}
function lifestream_get_class_constant($class, $const) {
    return constant(sprintf('%s::%s', $class, $const));
}
// not need:
function lifestream_get_single_event($feed_type) {
    global $socialStream;
    
    return $socialStream->get_single_event($feed_type);
}

// Start social stream

$socialStream = new Lifestream();

// Not needed:
//require_once(LIFESTREAM_PATH . '/inc/labels.php');

// what does this do?
$socialStream->register_feed('Lifestream_GenericFeed');

/**
 * Outputs the recent lifestream events.
 * @param {Array} $args An array of keyword args.
 */
function lifestream($args=array())
{
    global $socialStream;

    setlocale(LC_ALL, WPLANG);

    $_ = func_get_args();

    $defaults = array(
        'id'    => $socialStream->generate_unique_id(),
        'limit' => $socialStream->get_option('number_of_items'),
    );

    if (@$_[0] && !is_array($_[0]))
    {
        // old style
        $_ = array(
            'limit'         => @$_[0],
            'feed_ids'      => @$_[1],
            'date_interval' => @$_[2],
            'user_ids'      => @$_[4],
        );
        foreach ($_ as $key=>$value)
        {
            if ($value == null) unset($_[$key]);
        }
    }
    else
    {
        $_ = $args;
    }
    $page = $socialStream->get_page_from_request();
    $defaults['offset'] = ($page-1)*(!empty($_['limit']) ? $_['limit'] : $defaults['limit']);

    $_ = array_merge($defaults, $_);
    $limit = $_['limit'];
    $_['limit'] = $_['limit'] + 1;
    $options =& $_;
    
    // TODO: offset
    //$offset = $socialStream->get_option('lifestream_timezone');
    $events = call_user_func(array(&$socialStream, 'get_events'), $_);
    $has_next_page = (count($events) > $limit);
    if ($has_next_page) {
        $events = array_slice($events, 0, $limit);
    }
    $has_prev_page = ($page > 1);
    $has_paging = ($has_next_page || $has_prev_page);
    $show_metadata = empty($options['hide_metadata']);
    
    require($socialStream->get_theme_filepath('main.inc.php'));

    echo '<!-- Powered by Lifestream (version: '.LIFESTREAM_VERSION.'; theme: '.$socialStream->get_option('theme', 'default').'; iconset: '.$socialStream->get_option('icons', 'default').') -->';

    if ($socialStream->get_option('show_credits') == '1')
    {
        echo '<p class="lifestream_credits"><small>'.$socialStream->credits().'</small></p>';
    }
}

function lifestream_sidebar_widget($_=array())
{
    global $socialStream;
    
    setlocale(LC_ALL, WPLANG);
    
    $defaults = array(
        'limit'         => 10,
        'break_groups'  => true,
        'show_details'  => false,
    );
    
    $_ = array_merge($defaults, $_);
    
    $_['id'] = $socialStream->generate_unique_id();
    
    $options =& $_;
    
    // TODO: offset
    //$offset = $socialStream->get_option('lifestream_timezone');
    $events = call_user_func(array(&$socialStream, 'get_events'), $_);
    $show_metadata = empty($options['hide_metadata']);
    
    require($socialStream->get_theme_filepath('sidebar.inc.php'));
}

function lifestream_register_feed($class_name)
{
    global $socialStream;
    
    $socialStream->register_feed($class_name);
}

// built-in feeds
//include(LIFESTREAM_PATH . '/inc/extensions.php');

// legacy local_feeds - OLD NOT NEEDED
// PLEASE READ extensions/README
//@include(LIFESTREAM_PATH . '/local_feeds.inc.php');

// detect external extensions in extensions/
$socialStream->detect_extensions();
$socialStream->detect_themes();
$socialStream->detect_icons();

// sort once
ksort($socialStream->feeds);

// Require more of the codebase
require_once(LIFESTREAM_PATH . '/inc/widget.php');
require_once(LIFESTREAM_PATH . '/inc/syndicate.php');
require_once(LIFESTREAM_PATH . '/inc/template.php');

?>