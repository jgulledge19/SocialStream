<?php
/**
 * This snippet will Display the social feeds from the db 
 */
// snippet options
$limit = $modx->getOption('limit', $scriptProperties, 10);
$accounts = $modx->getOption('accounts', $scriptProperties, '');// this can be a comma separated list
$services = $modx->getOption('services', $scriptProperties, '');// this can be a comma separated list
$sortby = $modx->getOption('sortby', $scriptProperties, 'post_date');
$order = $modx->getOption('order', $scriptProperties, 'DESC');

$skin = $modx->getOption('skin', $scriptProperties, 'socialstream_');
$feed_tpl = $modx->getOption('feedTpl', $scriptProperties, $skin.'feedTpl');
$twitter_tpl = $modx->getOption('twitterTpl', $scriptProperties, $skin.'feedTpl');
$facebook_tpl = $modx->getOption('facebookTpl', $scriptProperties, $skin.'feedTpl');

// snippet code

// add package
$s_path = $modx->getOption('core_path').'components/socialstream/model/';
$modx->addPackage('socialstream', $s_path);

$query = $modx->newQuery('jgSocialFeeds');
$query->innerJoin('jgSocialAccounts','Account');

//$this->modx->getSelectColumns('ChurchEvents','ChurchEvents','',array('id','name'));
$query->select(array(
        $modx->getSelectColumns('jgSocialFeeds','jgSocialFeeds'), 
        '`Account`.`name` AS accountName',
        '`Account`.`username` AS accountUsername',
        '`Account`.`public_url` AS accountPublicUrl',
        
     ));// http://rtfm.modx.com/display/xPDO20/xPDOQuery.select
//$query->select('DISTINCT `ChurchEvents`.`id` AS `ChurchEvents_id`,'.$this->modx->getSelectColumns('ChurchEvents','ChurchEvents') );// http://rtfm.modx.com/display/xPDO20/xPDOQuery.select
        

if ( !empty($accounts) ) {
    $tmp = explode(',',$accounts);
    $accounts_in = array();
    foreach ($tmp as $value){
        $accounts_in[] = trim($value);
    }
    $query->where(array(
        'status:IN' => array('auto_approved','approved'),
        'username:IN'=> $accounts_in,
    ));
} elseif( !empty($services)) {
    $tmp = explode(',',$services);
    $services_in = array();
    foreach ($tmp as $value){
        $services_in[] = trim($value);
    }
    $query->where(array(
        'status:IN' => array('auto_approved','approved'),
        'service:IN' => $services_in
    ));
} else {
    $query->where(array(
        'status:IN' => array('auto_approved','approved')
    ));
}
$query->limit($limit);
$query->sortby($sortby, $order);

        $query->prepare();
        //echo 'SQL:<br>'.$query->toSQL().'<br>';
        
$feeds = $modx->getCollection('jgSocialFeeds',$query);
$output = '';
foreach ($feeds as $feed ){
    // get the chunck
    $data = $feed->toArray();
    switch ($data['service']) {
        case 'Twitter':
            $chunk = $twitter_tpl;
            break;
        case 'FacebookSearch':
        case 'Facebook':
            $chunk = $facebook_tpl;
            break;
        default:
            $chunk = $feed_tpl;
            break;
    }
    if ( $data['accountUsername'] != $data['username']) {
        $data['accountName'] = $data['author'];
    }
    $output .= $modx->getChunk($chunk, $data);
}

return $output;