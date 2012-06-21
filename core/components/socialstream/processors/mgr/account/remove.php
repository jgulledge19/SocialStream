<?php
/**
 * @package socialstream
 * @subpackage processors
 */

/* get obj */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('socialstream.account_err_ns'));
$account = $modx->getObject('jgSocialAccounts',$scriptProperties['id']);
if (empty($account)) return $modx->error->failure($modx->lexicon('socialstream.account_err_nf'));

/* remove */
if ($account->remove() == false) {
    return $modx->error->failure($modx->lexicon('socialstream.account_err_remove'));
}

return $modx->error->success('',$account);