<?php
/**
 * Loads the home page.
 *
 * @package doodles
 * @subpackage controllers
 * 
 * You may need to edit this file
 */

$modx->regClientStartupScript($cmpController->config['jsUrl'].'mgr/widgets/'.$cmpController->config['packageName'].'.grid.js');
$modx->regClientStartupScript($cmpController->config['jsUrl'].'mgr/widgets/accounts.grid.js');
$modx->regClientStartupScript($cmpController->config['jsUrl'].'mgr/widgets/home.panel.js');
$modx->regClientStartupScript($cmpController->config['jsUrl'].'mgr/sections/index.js');

$output = '<div id="'.$cmpController->config['packageName'].'-panel-home-div"></div>';

return $output;

/* ORG example 
$modx->regClientStartupScript($doodles->config['jsUrl'].'mgr/widgets/doodles.grid.js');
$modx->regClientStartupScript($doodles->config['jsUrl'].'mgr/widgets/home.panel.js');
$modx->regClientStartupScript($doodles->config['jsUrl'].'mgr/sections/index.js');

$output = '<div id="doodles-panel-home-div"></div>';

return $output;
*/