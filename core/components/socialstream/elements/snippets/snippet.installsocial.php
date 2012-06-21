<?php
/**
 * This snippet simply installs the db stuff
 */

// add package
$s_path = $modx->getOption('core_path').'components/socialstream/model/';
$modx->addPackage('socialstream', $s_path);
 
$m = $modx->getManager();
// the class table object name
$m->createObjectContainer('jgSocialAccounts');
$m->createObjectContainer('jgSocialFeeds');
return 'Table created.';