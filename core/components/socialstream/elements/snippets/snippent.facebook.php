<?php

/*
 * http://developers.facebook.com/docs/reference/fql/stream/
 * 
 * OLD: http://blog.jylin.com/2009/10/01/loading-wall-posts-using-facebookstream_get/
 * 
 * BC RSS(atom) http://www.facebook.com/feeds/page.php?id=22839919973&amp;format=atom10
 * Twitter RSS http://api.twitter.com/1/statuses/user_timeline.rss?screen_name=@BethelCollegeIN
 * 
 */[[!getFeed? &url=`http://twitter.com/statuses/user_timeline/162369603.rss` &tpl=`twitterFeedTpl` &limit=`3`]]

// get twitter json

$twitter = file_get_contents('http://api.twitter.com/1/statuses/user_timeline.json?screen_name=@BethelCollegeIN');

$obj = json_decode($twitter);
//print $obj->{'foo-bar'}; // 12345
print_r($obj);

 /*
  * Twitter: http://dev.twitter.com/doc/get/statuses/home_timeline
  * http://dev.twitter.com/start
  * http://dev.twitter.com/console??
  * 
  * PHP: https://dev.twitter.com/pages/libraries#php
  * 
  * 
  * API key: PC533QSIHdDBEj0Liamtw
  * Callback URL: http://www.bethelcollege.edu
  * 
  * OAuth 1.0a Settings

OAuth 1.0a integrations require more work.
Consumer key

PC533QSIHdDBEj0Liamtw
Consumer secret

ROLsPomZxEgGTeQodTAecUWvesZLNiX8nfXMurFkw
Request token URL

https://api.twitter.com/oauth/request_token
Access token URL

https://api.twitter.com/oauth/access_token
Authorize URL

https://api.twitter.com/oauth/authorize
We support hmac-sha1 signatures. We do not support the plaintext signature method.
Registered OAuth Callback URL

http://www.bethelcollege.edu
  */