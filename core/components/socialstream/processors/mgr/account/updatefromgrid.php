<?php
/**
 * @package doodle
 * @subpackage processors
 */
/* parse JSON */
if (empty($scriptProperties['data'])) return $modx->error->failure('Invalid data.');
$_DATA = $modx->fromJSON($scriptProperties['data']);
if (!is_array($_DATA)){
    return $modx->error->failure('Invalid data.');
}

/* get obj */
if (empty($_DATA['id'])) {
    return $modx->error->failure($modx->lexicon('socialstream.account_err_ns'));
}
$account = $modx->getObject('jgSocialAccounts',$_DATA['id']);
if (empty($account)) return $modx->error->failure($modx->lexicon('socialstream.account_err_nf'));

/* set fields */
$account->fromArray($_DATA);
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
/* save */
if ($account->save() == false) {
    return $modx->error->failure($modx->lexicon('socialstream.account_err_save'));
}


return $modx->error->success('',$account);