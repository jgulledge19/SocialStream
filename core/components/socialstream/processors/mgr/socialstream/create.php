<?php
/**
 * @package socialstream
 * @subpackage processors
 * This file needs to be customized
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

/* save */
if ($account->save() == false) {
    return $modx->error->failure($modx->lexicon('socialstream.account_err_save'));
}

return $modx->error->success('',$accout);