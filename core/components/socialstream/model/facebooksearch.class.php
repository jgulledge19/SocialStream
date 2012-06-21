<?php
/**
 * @description Twitter class default uses json data Extends SocialFeed
 */
class FacebookSearch extends SocialFeed {
    /**
     * @description construct just call on the parent class
     */
    function __construct(&$modx, $avaible_services=array() ) {
        parent::__construct($modx, $avaible_services);
    }
    /**
     * @description this will retrieve the feeds and return an array
     * @return $array
     */
    public function process(){
        
        if ( !empty($this->feed_url) ) {
            $url = $this->feed_url;
        } else {
            // http://graph.facebook.com/search?limit=&q=Bethel+College&limit=25&since=1309182538
            $url = 'http://graph.facebook.com/search?limit='.$this->feed_limit.'&q='.str_replace('@','',urlencode($this->account['username'])); 
        }
        $feeds = file_get_contents($url);
        // now put this into a standard socialStream feed array format
        // http://www.elated.com/articles/json-basics/
        $data = json_decode($feeds, true);// true for an array
        $data = $data['data'];
        $this->feed_data = array();
        foreach ( $data as $feed ){
            if ( empty($feed['message'])){
                continue;
            }
            $this->feed_data[] = array(
                'username' => $feed['from']['id'],
                'service' => 'FacebookSearch',
                'post_date' => $this->_convertTime($feed['created_time']),// need to format (2011-06-27T10:41:09+0000)
                'feed' => $feed['message'], 
                'html_feed' => $this->_makeHtml($feed['message']),
                // 'status',
                'post_url' => '',//$this->_postUrl($feed) ? id?,link
                'author' => $feed['from']['name'], //??
                //'email','',
                //'copyright' => '',
                'likes' => $feed['likes']['count'],
                'dislikes' => '0',
                'followers' => '0',
                'post_id' => $feed['id'], 
                'source'=> $feed['source'],
                // retweet_count
                // [user][profile_image_url] => http://a1.twimg.com/profile_images/85816023/campus_fall_07_181_normal.jpg
                // [user][id] => 22062516
            );
            
        }
        // now save the feeds
        if ( $this->_saveFeeds() ){
            return true;
        } else {
            return false;
        }
        
        /*[0] => Array
        (
            [in_reply_to_screen_name] => 
            [in_reply_to_user_id_str] => 
            [user] => Array
                (
                    [is_translator] => 
                    [statuses_count] => 625
                    [friends_count] => 638
                    [following] => 1
                    [followers_count] => 1045
                    [listed_count] => 46
                    [location] => Mishawaka, Indiana
                    [name] => Bethel College
                    [notifications] => 
                    [profile_image_url] => http://a1.twimg.com/profile_images/85816023/campus_fall_07_181_normal.jpg
                    [id_str] => 22062516
                    [default_profile] => 1
                    [utc_offset] => -21600
                    [url] => http://www.bethelcollege.edu
                    [description] => Christian Liberal Arts College; Updates posted by @ErinKinzel and @Matt_Esau from the marketing and communications office.
                    [screen_name] => BethelCollegeIN
                    [created_at] => Thu Feb 26 21:10:40 +0000 2009
                    [id] => 22062516
                    [default_profile_image] => 
                    [time_zone] => Central Time (US & Canada)
                )
            [contributors] => 
            [retweeted] => 
            [truncated] => 
            [id_str] => 73099684025085952
            [text] => BC is praying for @connect2OCC, Ozark Christian College in Joplin, MO as they deal w/ the effect of the tornado. http://ht.ly/521nP
            [in_reply_to_status_id] => 
            [created_at] => Tue May 24 18:54:57 +0000 2011
            [place] => 
            [in_reply_to_user_id] => 
            [id] => 7.3099684025086E+16
            [source] => <a href="http://www.hootsuite.com" rel="nofollow">HootSuite</a>
            [favorited] => 
            [in_reply_to_status_id_str] => 
            [coordinates] => 
            [geo] => 
            [retweet_count] => 0
        )*/
        
    }
    /**
     * @description return a post url
     * @param $feed - the json array
     * 
     */
    private function _postUrl($feed){
        $post_url = 'http://twitter.com/#!/'.$feed['user']['screen_name'].'/status/'.$feed['id_str'];
        return $post_url;
    }
    /**
     * @param $time
     */
    private function _convertTime($time) {
        // Thu Feb 26 21:10:40 +0000 2009
        return strtotime($time);
    }
    /**
     * @description return an html version of the text/feed
     * @param $status_text - string
     * 
     */
    private function _makeHtml($status_text){
        // function from: http://davidwalsh.name/linkify-twitter-feed
        //function linkify_twitter_status($status_text)
        // linkify URLs
        $status_text = preg_replace(
            '/(https?:\/\/\S+)/',
            '<a rel="" href="\1">\1</a>',
            $status_text
        );
        
        
        return $status_text;
    }
    
    
    /*// make the twitter hash links
        // http://efreedom.com/Question/1-3639136/Turn-Twitter-Hash-Tag-Link-Links-Clickable-Links
        $str = preg_replace('/\#([a-z0-9]+)/i', '<a href="http://search.twitter.com/search?q=%23$1">#$1</a>', $str);
        $url_pattern = '';
        $hash_pattern = '';
        $at_pattern = '';
        // replace with
        $url_replacement = '';
        $hash_replacement = '';
        $at_replacement = '';
        
        $str = preg_replace(array($url_pattern, $hash_pattern), array($url_replacement, $hash_replacement), $str);
        
        */
    /* *
     * @description - make urls html anchors(<a>) see http://snipplr.com/view/19085/replace-urls-in-string-with-html-links/
     *      other: http://www.gidforums.com/t-1816.html
     * http://efreedom.com/Question/1-3639136/Turn-Twitter-Hash-Tag-Link-Links-Clickable-Links
     * @param $string
     * /
    private function _makeLinks($string){
        $host = "([a-z\d][-a-z\d]*[a-z\d]\.)+[a-z][-a-z\d]*[a-z]";
        $port = "(:\d{1,})?";
        $path = "(\/[^?<>\#\"\s]+)?";
        $query = "(\?[^<>\#\"\s]+)?";
        return preg_replace("#((ht|f)tps?:\/\/{$host}{$port}{$path}{$query})#i", "<a href=\"$1\">$1</a>", $string);
    }
    */
}

