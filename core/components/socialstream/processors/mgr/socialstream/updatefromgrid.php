<?php
/**
 * @package doodle
 * @subpackage processors
 */
/* parse JSON */
if (empty($scriptProperties['data'])) return $modx->error->failure('Invalid data.');
$_DATA = $modx->fromJSON($scriptProperties['data']);
if (!is_array($_DATA)) return $modx->error->failure('Invalid data.');

/* get obj */
if (empty($_DATA['id'])) return $modx->error->failure($modx->lexicon('socialstream.feed_err_ns'));
$feed = $modx->getObject('jgSocialFeeds',$_DATA['id']);
if (empty($feed)) return $modx->error->failure($modx->lexicon('socialstream.feed_err_nf'));

/* set fields */
unset($_DATA['post_date']);
$feed->fromArray($_DATA);

/* save */
if ($feed->save() == false) {
    return $modx->error->failure($modx->lexicon('socialstream.feed_err_save'));
}


return $modx->error->success('',$feed);