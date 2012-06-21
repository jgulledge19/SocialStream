<?php
/**
 * @description Rss class uses RSS data Extends SocialFeed
 */
class YouTube extends SocialFeed {
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
            //$url = 'http://www.facebook.com/feeds/page.php?id='.urlencode($this->account['username']).'&format=atom10='; 
            $url = 'http://modx.com/extras/feeds/revolution/latest.rss';
        }
        $this->setUserAgent();
        
        $xml = simplexml_load_file($url);  //Load feed with simplexml
        
        $data = $xml->attributes();
        $version = $data['version'];
        
        // now put this into a standard socialStream feed array format
        switch ($version){
            case '0.92':
                /** 0.92
                 * title
                 * description
                 * link
                 */
                // break;
            case '2.0':
            case '2':
            default:
                /** 2.0
                 * title
                 * link
                 * comments
                 * pubDate
                 * category
                 * guid
                 * description
                 * 
                 */
                $username = str_replace('Uploads by ','', $xml->channel->title);
                $source = $xml->channel->link;
                
                $feeds = $xml->channel->item;
                
                foreach ( $feeds as $feed ) {
                    // print_r($feed);
                    /* echo '
                    <br>Title: '.iconv('UTF-8', 'ISO-8859-15//TRANSLIT', $feed->title).'
                    <br>Description: '.strip_tags(str_replace('</p><p>', ' ', iconv('UTF-8', 'ISO-8859-15//TRANSLIT', $feed->description)) ).'
                    <br>Link: '.$feed->link; */
                    $title = iconv('UTF-8', 'ISO-8859-15//TRANSLIT', $feed->title);
                    $this->feed_data[] = array(
                        'username' => (string)$username,
                        'service' => 'YouTube',
                        'post_date' => $this->_convertTime($feed->pubDate),// need to format (2011-06-27T10:41:09+0000)
                        'feed' => (string)trim($title), 
                        'html_feed' => $this->_makeHtml(trim($title)),
                        'post_url' => (string)$this->_userUrl($feed->link),
                        'author' => (string)$username, //??
                        //'email','',
                        //'copyright' => '',
                        'likes' => 0,
                        'dislikes' => '0',
                        'followers' => '0',
                        'post_id' => (string)substr($feed->link, -32), // this is used as unique identifier and only 32 chars 
                        'source'=> $source,
                        // retweet_count
                    );
                }
                break;
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
            '<a rel="" href="\1">\1</a>',
            $status_text
        );
        
        return $status_text;
    }
    /**
     * make the YouTube link go to the useraccount page not the generic YouTube page
     * 
     */
    private function _userUrl($url) {
        if ( !empty($this->account['username'])){
            $base = 'http://www.youtube.com/user/'.$this->account['username'];
            list($org, $params) = explode('?', $url);
            $url = $base.'?'.$params;
        }
        return $url;
    }
}