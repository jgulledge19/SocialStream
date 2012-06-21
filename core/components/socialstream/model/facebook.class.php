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
            $url = 'http://www.facebook.com/feeds/page.php?id='.urlencode($this->account['username']).'&format=atom10'; 
        }
        // now put this into a standard socialStream feed array format
        
        $this->setUserAgent();
        
        // http://www.acornartwork.com/blog/2010/04/19/tutorial-facebook-rss-feed-parser-in-pure-php/
        $feeds = simplexml_load_file($url);  //Load feed with simplexml
        //print_r($feeds);
        //$array = $feeds->feed->entry;
        
        
        //print_r($array);
        foreach ( $feeds as $key => $feed ) {
            if ( $key !== 'entry') {
                continue;
            }
            //print_r($feed);
            $link = $feed->link->attributes();
            $this->feed_data[] = array(
                'username' => (string)$feed->author->name[0],
                'service' => 'Facebook',
                'post_date' => $this->_convertTime($feed->published),// need to format (2011-06-27T10:41:09+0000)
                'feed' => (string)trim($feed->title[0]), 
                'html_feed' => $this->_makeHtml(trim($feed->title)),//$feed->content
                // 'status',
                'post_url' => (string)$link['href'],//$this->_postUrl($feed) ? id?,link
                'author' => (string)$feed->author->name[0], //??
                //'email','',
                //'copyright' => '',
                'likes' => 0,
                'dislikes' => '0',
                'followers' => '0',
                'post_id' => (string)$feed->id[0], 
                'source'=> $feed['source'],
                // retweet_count
                // [user][profile_image_url] => http://a1.twimg.com/profile_images/85816023/campus_fall_07_181_normal.jpg
                // [user][id] => 22062516
            );
            
        }
        //print_r($this->feed_data);
        
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
            '<a rel="" href="\1" target="_blank">\1</a>',
            $status_text
        );
        
        return $status_text;
    }
}