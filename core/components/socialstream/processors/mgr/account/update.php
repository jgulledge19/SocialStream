<?php
/**
 * @package socialstream
 * @subpackage processors
 */

/* get obj */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('socialstream.account_err_ns'));
$account = $modx->getObject('jgSocialAccounts',$scriptProperties['id']);
if (empty($account)) return $modx->error->failure($modx->lexicon('socialstream.account_err_nf'));

/* set fields */
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

/* save */
if ($account->save() == false) {
    return $modx->error->failure($modx->lexicon('socialstream.account_err_save'));
}


return $modx->error->success('',$account);