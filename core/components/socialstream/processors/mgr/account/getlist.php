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
$limit = $modx->getOption('limit',$scriptProperties,30);
$sort = $modx->getOption('sort',$scriptProperties,'username');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$query = $modx->getOption('query',$scriptProperties,'');

/* build query */
$c = $modx->newQuery('jgSocialAccounts');

if (!empty($query)) {
    $c->where(array(
        'username:LIKE' => '%'.$query.'%',
        'OR:name:LIKE' => '%'.$query.'%',
    ));
}

$count = $modx->getCount('jgSocialAccounts',$c);
$c->sortby($sort,$dir);
if ($isLimit) {
    $c->limit($limit,$start);
}
$feeds = $modx->getIterator('jgSocialAccounts', $c);


/* iterate */
$list = array();
foreach ($feeds as $feed) {
    $list[] = $feed->toArray();
}
return $this->outputArray($list,$count);