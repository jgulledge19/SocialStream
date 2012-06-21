<?php
/**
 * @description Facebook class uses RSS data Extends SocialFeed
 */
class Facebook extends SocialFeed {
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
            // 22839919973
            $url = 'http://www.facebook.com/feeds/page.php?id='.urlencode($this->account['username']).'&format=atom10='; 
        }
        // now put this into a standard socialStream feed array format
        
        //  Mozilla/5.0 (Windows NT 6.1; WOW64; rv:5.0) Gecko/20100101 Firefox/5.0
        ini_set('user_agent', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:5.0) Gecko/20100101 Firefox/5.0');
        // http://www.acornartwork.com/blog/2010/04/19/tutorial-facebook-rss-feed-parser-in-pure-php/
        $feeds = simplexml_load_file($url);  //Load feed with simplexml
        print_r($feeds);
        foreach ( $feeds->entry as $feed ) {
            if ( empty($feed['message'])){
                continue;
            }
            $this->feed_data[] = array(
                'username' => $feed->author->name,
                'service' => 'Facebook',
                'post_date' => $this->_convertTime($feed->published),// need to format (2011-06-27T10:41:09+0000)
                'feed' => $feed->title, 
                'html_feed' => $this->_makeHtml($feed->title),//$feed->content
                // 'status',
                'post_url' => $feed->link->attributes['href'],//$this->_postUrl($feed) ? id?,link
                'author' => $feed->author->name, //??
                //'email','',
                //'copyright' => '',
                'likes' => 0,
                'dislikes' => '0',
                'followers' => '0',
                'post_id' => $feed->id, 
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
            '<a ref="" href="\1">\1</a>',
            $status_text
        );
        
        return $status_text;
    }
}