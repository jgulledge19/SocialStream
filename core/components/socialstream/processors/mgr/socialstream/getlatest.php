<?php
/**
 * Get a list of Feeds
 * 
 * @package socialstream
 * @subpackage processors
 * This file needs to be customized
 */

/**
 * Get streams/feeds based on the accounts
 */

$query = $modx->newQuery('jgSocialAccounts');
$query->where(array(
    'get_feeds' => '1',
    'active' => '1'
));
if ( $limit_feeds > 0 ) {
    $query->limit($limit_feeds);
}
$accounts = $modx->getCollection('jgSocialAccounts', $query);

$social_services = array(
    'Twitter' => 'twitter.class.php',
    'Facebook' => 'facebook.class.php',
    'FacebookSearch' => 'facebooksearch.class.php',
    'RSS' => 'rss.class.php',
    'YouTube' => 'youtube.class.php'
);
$s_path = $modx->getOption('core_path').'components/socialstream/model/';
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
            //$output .= '<br>Got feeds for '.$account->get('id');
        } else {
            return $modx->error->failure($modx->lexicon('socialstream.getlastest_err_save'));
        }
        //$output .= '<br>Got feeds for '.$account->get('id');
    } else {
        return $modx->error->failure($modx->lexicon('socialstream.getlastest_err_process').' '.
            $account->get('service').' username: '.$account->get('username'));
    }
}
return $modx->error->success($modx->lexicon('socialstream.getlastest_save'),$accout);
 
 
 
 
 /*
 
 
 
 
 
 
// setup default properties 
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,30);
$sort = $modx->getOption('sort',$scriptProperties,'post_date');
$dir = $modx->getOption('dir',$scriptProperties,'DESC');
$query = $modx->getOption('query',$scriptProperties,'');

// build query 
$c = $modx->newQuery('jgSocialFeeds');

if (!empty($query)) {
    $c->where(array(
        'username:LIKE' => '%'.$query.'%',
        'OR:feed:LIKE' => '%'.$query.'%',
    ));
}

$count = $modx->getCount('jgSocialFeeds',$c);
$c->sortby($sort,$dir);
if ($isLimit) {
    $c->limit($limit,$start);
}
$feeds = $modx->getIterator('jgSocialFeeds', $c);


// iterate 
$list = array();
foreach ($feeds as $feed) {
    $feed_array = $feed->toArray();
    // make the date readable
    $feed_array['post_date'] = date('n/j/y g:ia',$feed_array['post_date']);
    $list[] = $feed_array; 
}
return $this->outputArray($list,$count);
  */