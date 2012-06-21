<?php
/**
 * Get streams/feeds based on the accounts
 */

$return_info = $modx->getOption('returnInfo', $scriptProperties, true);
// add package
$s_path = $modx->getOption('core_path').'components/socialstream/model/';
$modx->addPackage('socialstream', $s_path);

// $strip_tags = $modx->getOption('strip_tags', $scriptProperties, true);

$modx->setLogTarget('HTML');

$output = '';

$query = $modx->newQuery('jgSocialAccounts');
$query->where(array(
    'get_feeds' => '1',
    'active' => '1'
));
if ( $limit_feeds > 0 ) {
    $query->limit($limit_feeds);
}
//$query->sortby($sortby, $order);
$accounts = $modx->getCollection('jgSocialAccounts', $query);
//$modx->setLogTarget($oldTarget);
//echo 'SQL:<br>'.$query->toSQL();

$social_services = array(
    'Twitter' => 'twitter.class.php',
    'Facebook' => 'facebook.class.php',
    'FacebookSearch' => 'facebooksearch.class.php',
    'RSS' => 'rss.class.php',
    'YouTube' => 'youtube.class.php'
);
if ( file_exists($modx->getOption('core_path').'components/socialstream/custom.config.php')) {
    require_once $modx->getOption('core_path').'components/socialstream/custom.config.php';
    $social_services = array_merge($social_services, $social_extensions);
}
require_once $s_path.'socialfeed.class.php';
// now load each class so it can be called on
foreach($social_services as $name => $class) {
    require_once $s_path.$class;
}

foreach ($accounts as $account ) {
    // get the lastest feeds
    //echo 'TEST ';
    //echo $account->get('username');
    // now call on the classs
    
    
    // $feed = new SocialFeed($modx);
    $class = $account->get('service');//$this->avaiable_services[$this->service];
    // echo ' - Class: '.$class;
    $feedService = new $class($modx);
    if (!is_object($feedService) ) {
       continue;
    }
    
    $feedService->setService($account->get('service'));// facebook/twitter
    $feedService->setAccount($account->get('id'), $account->get('username'), $account->get('auto_approve'));// useraccount
    //$feedService->setAccountsStatus(array('username'=>'approve'));
    $feedService->setUrl($account->get('feed_url'));// the rss/json url to retrieve data
    // the date this ran last
    $feedService->setGetDate($account->get('get_date'));// the 
    
    // process & save to db
    if ( $feedService->process() ){
         // now save the latest date into the database
        $account->set('get_date', time());
        if ( $account->save() ) {
            $output .= '<br>Got feeds for '.$account->get('id');
        } else {
            $output .= '<br>Error getting feeds for '.$account->get('id');
        }
        //$output .= '<br>Got feeds for '.$account->get('id');
    } else {
        
    }
}
if ( $return_info ) {
    return $output;
} else {
    return true;
}