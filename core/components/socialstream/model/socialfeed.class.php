<?php

/**
 * @description This class calls on a service calls that will retreive posts/feeds
 * 
 */
class SocialFeed {
    /**
     * @var $service this is the service name, like Facebook/Twitter
     */
    protected $service = '';
    /**
     * @var $account - an array of username & password if needed so like
     *  array('username'=>'User') 
     */
    protected $account = array();
    /**
     * @var $accounts_status - all accounts with in array of username=>approve status
     */
    protected $accounts_status = array();
    /**
     * @var $feed_url - the url that the service will call on to get social feeds 
     */
    protected $feed_url  = '';
    /**
     * @var $get_date - the date & time to get posts from until present time
     */
    protected $get_date = '';
    /**
     * @var $feed_limit - limit how many feeds to get from service
     */
    protected $feed_limit = 20;
    /**
     * @var $modx
     */
    protected $modx;
    /**
     * @var $avaible_services
     */
    protected $avaible_services;
    /**
     * @var feed_data this is the data as an array
     */
    protected $feed_data = array();
    /**
     * @description __constructor
     * @param $modx
     * @param $avaible_services
     */
    function __construct(&$modx, $avaible_services=array()){
        $this->modx = $modx;
        $this->avaiable_services = $avaible_services;
    }
    /**
     * @param $service this is the service name, like Facebook/Twitter
     */
    public function setService($service) {
        $this->service = $service;
    }
    /**
     * @param $username 
     * @param $auto_approve
     * @param $password  
     */
    public function setAccount($id, $username, $auto_approve, $password=NULL){
        $this->account = array(
            'id' => $id,
            'username' => $username,
            'password' => $password,
            'auto_approve' => $auto_approve
        );
    }
    /**
     * @param $accounts_status - all accounts with in array of username=>approve status
     */
    public function setAccountsStatus ($accounts_status) {
        $this->accounts_status = $accounts_status;
    }
    /**
     * @param $feed_url - the url that the service will call on to get social feeds
     *  if empty the service class will create the url
     */
    public function setUrl($feed_url) {
        $this->feed_url = $feed_url;
    }
    /**
     * @param $get_date - the date & time to get posts from until present time
     */
    public function setGetDate($get_date) {
        $this->get_date = $get_date;
    }
    /**
     * @param $feed_limit - limit how many feeds to get from service
     */
    public function setLimit($feed_limit) {
        $this->feed_url = $feed_limit;
    }
    /**
     * @param $user_agent string a valid browser User Agent 
     */
    public function setUserAgent($user_agent='Mozilla/5.0 (Windows NT 6.1; WOW64; rv:5.0) Gecko/20100101 Firefox/5.0') {
        ini_set('user_agent', $user_agent);
    }
    /**
     * @description run() - call on the service class and get the feeds
     * @return boolean
     */
    protected function _saveFeeds() {
        // now save in the database
        $x=0;
        foreach ($this->feed_data as $feed) {
            $x++;
            // does this already exist?
            if ( !empty($feed['post_id']) ) {
                $query = $this->modx->newQuery('jgSocialFeeds');
                $query->where(array(
                    'social_account_id' => $this->account['id'],//$this->social_account_id,
                    'post_id' => $feed['post_id']
                ));
                //$query->sortby($sortby, $order);
                $old_feed = $this->modx->getObject('jgSocialFeeds',$query);
                if ( is_object($old_feed) && $old_feed->get('id') > 0 ) {
                    unset($old_feed);
                    //echo '<br>this exists:  '.$x.' - '.$feed['post_id']. ' strlen: '.strlen($feed['post_id']);
                    continue;
                }
            }
            // save to DB
            $new_feed = $this->modx->newObject('jgSocialFeeds');
            $new_feed->fromArray($feed);
            $new_feed->set('status', 'pending');
            $tw_user = $feed['username'];
            if ( !empty($tw_user) ){
               if ( ($this->account['username'] == $tw_user && $this->account['auto_approve']  ) 
                    || $this->accounts_status[$feed['username']]  ) {
                   $new_feed->set('status', 'auto_approved');
                   // auto_approved, approved, pending, delete
               } 
            }
            $new_feed->set('social_account_id', $this->account['id']);
            
            $new_feed->save();
            
            unset($new_feed);
        }
        return true;
    }
    
}