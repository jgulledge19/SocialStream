<?php
/**
 * @package socialstream
 * @subpackage processors
 * 
 */

if (empty($scriptProperties['username'])) {
    $modx->error->addField('username',$modx->lexicon('socialstream.account_err_ns_name'));
} else {
    $alreadyExists = $modx->getObject('jgSocialAccounts',
        array(
            'username' => $scriptProperties['username'],
            'service' => $scriptProperties['service'],
            )
        );
    if ($alreadyExists) {
        $modx->error->addField('username',$modx->lexicon('socialstream.account_err_ae'));
    }
}

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

$account = $modx->newObject('jgSocialAccounts');
$account->fromArray($scriptProperties);

if ($account->get('auto_approve') == true || $account->get('auto_approve') == 'Yes' ) {
    $account->set('auto_approve', '1');
} else {
    $account->set('auto_approve', '0');
}
if ($account->get('active') == true || $account->get('active') == 'Yes' ) {
    $account->set('active', '1');
} else {
    $account->set('active', '0');
}
if ($account->get('get_feeds') == true || $account->get('get_feeds') == 'Yes' ) {
    $account->set('get_feeds', '1');
} else {
    $account->set('get_feeds', '0');
}
// set create time
$account->set('create_date', strftime('%Y-%m-%d %H:%M:%S'));

/* save */
if ($account->save() == false) {
    return $modx->error->failure($modx->lexicon('socialstream.account_err_save'));
}

return $modx->error->success('',$accout);