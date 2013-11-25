<?php
class Tweets {
    private $_tweet;

    private $_CONSUMER_KEY = '';

    private $_CONSUMER_SECRET = '';

    private $_OAUTH_TOKEN = '';

    private $_OAUTH_TOKEN_SECRET = '';

    private $_username = '';

    //Maximum of tweets you want to get
    private $_count = 10;

    //Path to cache folder
    private $cache_path = 'tmp/cache/';

    //Length of time to cache a file in seconds
    private $cache_time = 3600;

    public function __construct()
    {
        $this->_tweet = new TwitterOAuth( $this->_CONSUMER_KEY, $this->_CONSUMER_SECRET, $this->_OAUTH_TOKEN, $this->_OAUTH_TOKEN_SECRET );
    }

    public function set_consumer_key($info){
        $this->_CONSUMER_KEY = $info;
    }



    public function getTweets()
    {
        if($this->is_cached('tweet')){

        //if cache file doesn't expire, return cache
            return $this->get_cache('tweet');
        }else{

        //setup twitter api connection
            $connection = $this->_tweet;
            $connection->host = "https://api.twitter.com/1.1/";
            $connection->ssl_verifypeer = TRUE;
            $connection->content_type = 'application/x-www-form-urlencoded';
            $tweets = $connection->get('http://api.twitter.com/1.1/statuses/user_timeline.json?screen_name='.$this->_username.'&count='.$this->_count);

            // !$tweets->errors() (rate limit exceeded) 100 API calls per hour limit: https://blog.twitter.com/2008/what-does-rate-limit-exceeded-mean-updated
            if(!empty($tweets) && !isset($tweets->errors)){
                foreach ($tweets as $key=>$tweet) {
                    $tweets[$key]->text = $this->url_to_link($tweet->text);
                    $tweets[$key]->created_at_explode = $this->explode_date($tweets[$key]->created_at);
                }
                $this->set_cache('tweet', $tweets);
                return $tweets;
            }else{
                return false;
            }
        }
    }

    //set cache
    private function set_cache($label, $data)
    {
        file_put_contents($this->cache_path . $this->safe_filename($label) .'.cache', serialize($data));
    }

    //retrieve cache
    private function get_cache($label)
    {
        if($this->is_cached($label)){

            $filename = $this->cache_path . $this->safe_filename($label) .'.cache';
            return unserialize(file_get_contents($filename));
        }
        return false;
    }

    //check if cache had expired
    private function is_cached($label)
    {
        $filename = $this->cache_path . $this->safe_filename($label) .'.cache';

        if(file_exists($filename) && (filemtime($filename) + $this->cache_time >= time())) return true;

        return false;
    }

    //explode 'created_at' string to array, for customize purpose, new name is 'created_at_explode'
    private function explode_date($date){
        return explode(' ',$date);
    }

    //change url to link
    private function url_to_link($url)
    {
        return preg_replace('$(\s|^)(https?://[a-z0-9_./?=&-]+)(?![^<>]*>)$i', ' <a href="$2" target="_blank">$2</a> ', $url." ");

    }

    //Helper function to validate filenames
    private function safe_filename($filename)
    {
        return preg_replace('/[^0-9a-z\.\_\-]/i','', strtolower($filename));
    }
}