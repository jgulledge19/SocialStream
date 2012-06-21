<?php
/**
 * Get a list of Feeds
 * 
 * @package socialstream
 * @subpackage processors
 * This file needs to be customized
 */
/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'post_date');
$dir = $modx->getOption('dir',$scriptProperties,'DESC');
$query = $modx->getOption('query',$scriptProperties,'');

/* build query */
$c = $modx->newQuery('jgSocialFeeds');

if (!empty($query)) {
    $c->where(array(
        'username:LIKE' => '%'.$query.'%',
        'OR:feed:LIKE' => '%'.$query.'%',
    ));
}

$count = $modx->getCount('jgSocialFeeds',$c);
$c->sortby($sort,$dir);
if ( $sort != 'post_date' ) {
    $c->sortby('post_date','DESC');
}
if ($isLimit) {
    $c->limit($limit,$start);
}
$feeds = $modx->getIterator('jgSocialFeeds', $c);


/* iterate */
$list = array();
foreach ($feeds as $feed) {
    $feed_array = $feed->toArray();
    // make the date readable
    $feed_array['post_date'] = date('n/j/y g:ia',$feed_array['post_date']);
    $list[] = $feed_array; 
}
return $this->outputArray($list,$count);