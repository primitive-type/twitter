<?php namespace League\Twitter;

class Api
{
    # cache for 1 minute
    public const DEFAULT_CACHE_TIMEOUT = 60;
  
    private const API_REALM = 'Twitter API';

    private const CHARACTER_LIMIT = 140;

    protected $consumer_key;
    protected $consumer_secret;
    protected $access_token_key;
    protected $access_token_secret;

    protected $oauth_token
    protected $oauth_consumer;

    protected $signature_method_plaintext;
    protected $signature_method_hmac_sha1;

    /**
     * Constructor
     */
    public function __construct(
        $consumer_key = null,
        $consumer_secret = null,
        $access_token_key = null,
        $access_token_secret = null,
        $input_encoding = null,
        $request_headers = null,
        $cache = static::DEFAULT_CACHE,
        $shortner = null,
        $base_url = null,
        $use_gzip_compression = false,
        $debug_http = false
    ) {
        $this->setCache($cache);
        $this->cache_timeout  = static::DEFAULT_CACHE_TIMEOUT;
        $this->input_encoding = $input_encoding;
        $this->use_gzip       = $use_gzip_compression;
        $this->debug_http     = $debug_http;
        $this->oauth_consumer = null;
        $this->shortlink_size = 19;

        $this->initializeRequestHeaders($request_headers);
        $this->initializeUserAgent();
        $this->initializeDefaultParameters();

        if (is_null($base_url)) {
          $this->base_url = 'https://api.twitter.com/1.1'
        } else {
          $this->base_url = $base_url
        }

        if (! is_null($consumer_key) and (is_null($access_token_key) or is_null($access_token_secret)) {
            throw new Exception('Twitter requires OAuth Access Token for all API access');
        }

        $this->setCredentials($consumer_key, $consumer_secret, $access_token_key, $access_token_secret);
    }

    /**
     * Set the consumer_key and consumer_secret for this instance.
     *
     * @param string consumer_key The consumer_key of the twitter account.
     * @param string consumer_secret The consumer_secret for the twitter account.
     * @param string access_token_key The OAuth access token key value
     * @param string access_token_secret The OAuth access token's secret
     */
    public function setCredentials(
        $consumer_key,
        $consumer_secret,
        $access_token_key = null,
        $access_token_secret = null
    ) {

        $this->consumer_key        = $consumer_key;
        $this->consumer_secret     = $consumer_secret;
        $this->access_token_key    = $access_token_key;
        $this->access_token_secret = $access_token_secret;
        $this->oauth_consumer      = null;

        if (
            ! is_null($consumer_key) and ! is_null($consumer_secret) and 
            ! is_null($access_token_key) and ! is_null($access_token_secret)
        ) {
            $this->signature_method_plaintext = oauth.SignatureMethod_PLAINTEXT();
            $this->signature_method_hmac_sha1 = oauth.SignatureMethod_HMAC_SHA1();
        }

        $this->oauth_token    = oauth.Token(key=access_token_key, secret=access_token_secret)
        $this->oauth_consumer = oauth.Consumer(key=consumer_key, secret=consumer_secret)
    }


    /**
     * Fetch a URL, optionally caching for a specified time.
     *
     * @param string $url URL to retrieve.
     * @param string $method HTTP Method, only GET or POST are acceptable.
     * @param array $parameters An array of key/value data for POST/GET params.
     * @param bool $no_cache If true, overrides the cache on the current request.
     * @param bool $use_gzip_compression If True, tells the server to gzip-compress the 
     *   response. It does not apply to POST requests. Defaults to null, which will get 
     *   the value to use from the instance variable $this->_use_gzip.
     * @return string
     */
    protected function fetchUrl(
        $url,
        $http_method = 'GET',
        array $parameters = null,
        $no_cache = null,
        $use_gzip_compression = null
    ) {
   
        $extra_params = $this->_default_params;

        if ($parameters) {
            $extra_params = array_merge($extra_params, $parameters);
        }

        $debug = (bool) $this->_debug_http;

        http_handler  = self._urllib.HTTPHandler(debuglevel=_debug)
        https_handler = self._urllib.HTTPSHandler(debuglevel=_debug)
        http_proxy = os.environ.get('http_proxy')
        https_proxy = os.environ.get('https_proxy')

        if http_proxy is None or  https_proxy is None :
          proxy_status = False
        else :
          proxy_status = True

        opener = self._urllib.OpenerDirector()
        opener.add_handler(http_handler)
        opener.add_handler(https_handler)

        if proxy_status is True :
          proxy_handler = self._urllib.ProxyHandler({'http':str(http_proxy),'https': str(https_proxy)})
          opener.add_handler(proxy_handler)

        if use_gzip_compression is None:
          use_gzip = self._use_gzip
        } else {
          use_gzip = use_gzip_compression

        # Set up compression
        if use_gzip and not post_data:
          opener.addheaders.append(('Accept-Encoding', 'gzip'))

        if self._oauth_consumer is not None:
          if post_data and http_method == "POST":
            parameters = post_data.copy()

          req = oauth.Request.from_consumer_and_token(self._oauth_consumer,
                                                      token=self._oauth_token,
                                                      http_method=http_method,
                                                      http_url=url, parameters=parameters)

          req.sign_request(self._signature_method_hmac_sha1, self._oauth_consumer, self._oauth_token)

          headers = req.to_header()

          if http_method == "POST":
            encoded_post_data = req.to_postdata()
          } else {
            encoded_post_data = None
            url = req.to_url()
        } else {
          url = self._BuildUrl(url, extra_params=extra_params)
          encoded_post_data = self._EncodePostData(post_data)

        # Open and return the URL immediately if we're not going to cache
        if encoded_post_data or no_cache or not self._cache or not self._cache_timeout:
          response = opener.open(url, encoded_post_data)
          url_data = self._DecompressGzippedResponse(response)
          opener.close()
        } else {
          # Unique keys are a combination of the url and the oAuth Consumer Key
          if self._consumer_key:
            key = self._consumer_key + ':' + url
          } else {
            key = url

          # See if it has been cached before
          last_cached = self._cache.GetCachedTime(key)

          # If the cached version is outdated then fetch another and store it
          if not last_cached or time.time() >= last_cached + self._cache_timeout:
            try:
              response = opener.open(url, encoded_post_data)
              url_data = self._DecompressGzippedResponse(response)
              self._cache.Set(key, url_data)
            except urllib2.HTTPError, e:
              print e
            opener.close()
          } else {
            url_data = self._cache.Get(key)

        # Always return the latest version
        return url_data
    }

    /**
     * Clear the any credentials for this instance.
     */
    public function clearCredentials() 
    {
        $this->consumer_key        = null;
        $this->consumer_secret     = null;
        $this->access_token_key    = null;
        $this->access_token_secret = null;
        $this->oauth_consumer      = null;
    }

    /**
     * Return twitter search results for a given term.
     * 
     * @param string $term Term to search by. Optional if you include geocode.
     * @param int $since_id Returns results with an ID greater than the specified ID.
     * @param int $max_id Returns only statuses with an ID less than or equal to the specified ID.
     * @param string $until Returns tweets generated before the given date (YYYY-MM-DD).
     * @param string $geocode Geolocation information in the form (latitude, longitude, radius)
     * @param string $count Number of results to return. Default is 15
     * @param string $lang Language for results as ISO 639-1 code. Default is null (all languages)
     * @param string $locale Language of the search query. Currently only 'ja' is effective.
     * @param string $result_type Type of result which should be returned. "mixed", "recent" and "popular"
     * @param string $include_entities If true, each tweet will include a node called "entities"
     *
     * @return array[League\Twitter\Status]
     */
    public function getSearch(
        $term = null,
        $geocode = null,
        $since_id = null,
        $max_id = null,
        $until = null,
        $count = 15,
        $lang = null,
        $locale = null,
        $result_type = "mixed",
        $include_entities = null
    ) {

        # Build request parameters
        $parameters = array();

        if ($since_id) {
            $parameters['since_id'] = (int) $since_id;
        }
        if ($max_id) {
            $parameters['max_id'] = (int) $max_id;
        }
        if ($until) {
            $parameters['until'] = $until;
        }
        if ($lang) {
            $parameters['lang'] = $lang;
        }
        if ($locale) {
            $parameters['locale'] = $locale;
        }
        if (is_null($term) and is_null($geocode)) {
            return array();
        }
        if (! is_null($term)) {
            $parameters['q'] = $term;
        }
        if (! is_null($geocode)) {
            $parameters['geocode'] = implode(',', map(str, geocode));
        }
        if ($include_entities) {
            $parameters['include_entities'] = 1;
        }
        
        $parameters['count'] = (int) $count;

        if (in_array($result_type, array('mixed', 'popular', 'recent')) {
            $parameters['result_type'] = $result_type;
        }

        // Make and send requests
        $url  = "{$this->base_url}/search/tweets.json";
        $json = $this->fetchUrl($url, $parameters)
        $data = $this->parseAndCheckTwitter($json)

        // Return built list of statuses
        return [Status.NewFromJsonDict(x) for x in data['statuses']];
    }

    /**
     * Return twitter user search results for a given term.
     * 
     * @param string $term Term to search by.
     * @param string $page Page of results to return. Default is 1. [Optional]
     * @param string $count Number of results to return.  Default is 20. [Optional]
     * @param string $include_entities If true, each tweet will include a node called "entities". [Optional]
     *
     * @return array[League\Twitter\User]
     */
    public function getUsersSearch($term, $page = 1, $count = 20, $include_entities = null)
    {
        // Build request parameters
        $parameters = array();

        if (! is_null($term)) {
            $parameters['q'] = $term;
        }

        if ($include_entities) {
            $parameters['include_entities'] = 1;
        }
        
        $parameters['count'] = (int) $count;

        # Make and send requests
        $url  = "{$this->base_url}/users/search.json";
        $$json = $this->fetchUrl($url, 'GET', $parameters);;
        $data = $this->_ParseAndCheckTwitter($json);
        return [User::newFromJsonArray(x) for x in $data];
    }

    /**
     * Get the current top trending topics
     * 
     * @param array $exclude Appends the exclude parameter as a request parameter.
     *
     * @return array[League\Twitter\Trend]
     */
    public function getTrendsCurrent($exclude = null)
    {
        return $this->GetTrendsWoeid(id=1, exclude=exclude)
    }

    /**
     * Return the top 10 trending topics for a specific WOEID
     * 
     * @param array $woeid The Yahoo! Where On Earth ID for a location.
     * @param array $exclude Appends the exclude parameter as a request parameter.
     *
     * @return array[League\Twitter\Trend]
     */
    public function getTrendsWoeid($woeid, $exclude = null)
    {
        $url  = "{$this->base_url}/trends/place.json";
        $parameters = array('id' => $woeid);

        if ($exclude) {
            $parameters['exclude'] = $exclude;
        }

        $json = $this->fetchUrl($url, $parameters)
        $data = $this->parseAndCheckTwitter($json)

        $trends = []
        $timestamp = data[0]['as_of']

        foreach ($data[0]['trends'] as $trend) {
            $trends[] = Trend::newFromJsonDict($trend, $timestamp));
        }

        return $trends;
    }

    /**
     * Fetch a collection of the most recent Tweets and Retweets posted by the
     * authenticating user and the users they follow.
     * 
     * The League\Twitter\Api instance must be authenticated.
     * 
     * @param int $count Specifies the number of statuses to retrieve. May not be 
     *  greater than 200. Defaults to 20.
     * @param int $since_id Returns results with an ID greater than (that is, more recent
     *  than) the specified ID. There are limits to the number of
     *  Tweets which can be accessed through the API. If the limit of
     *  Tweets has occurred since the since_id, the since_id will be
     *  forced to the oldest ID available.
     * @param int $max_id Returns results with an ID less than (that is, older than) or
     *  equal to the specified ID.
     * @param bool $trim_user  When true, each tweet returned in a timeline will include a user
     *  object including only the status authors numerical ID. Omit this
     *  parameter to receive the complete user object.
     * @param bool $exclude_replies  This parameter will prevent replies from appearing in the
     *  returned timeline. Using exclude_replies with the count
     *  parameter will mean you will receive up-to count tweets -
     *  this is because the count parameter retrieves that many
     *  tweets before filtering out retweets and replies.
     * @param bool $contributor_details  This parameter enhances the contributors element of the
     *  status response to include the screen_name of the contributor.
     *  By default only the user_id of the contributor is included.
     * @param bool $include_entities  The entities node will be disincluded when set to false.
     *  This node offers a variety of metadata about the tweet in a
     *  discreet structure, including: user_mentions, urls, and
     *  hashtags
     *
     * @return array[League\Twitter\Status]
     */
    public function getHomeTimeline(
        $count = null,
        $since_id = null,
        $max_id = null,
        $trim_user = false,
        $exclude_replies = false,
        $contributor_details = false,
        $include_entities = true
    ) {

        $url = "{$this->base_url}/statuses/home_timeline.json";

        if (! $this->_oauth_consumer) {
            throw new Exception("API must be authenticated.");
        }
        
        $parameters = array();

        if (! is_null($count) {
            if (! is_numeric($count)) {
                throw new \InvalidArgumentException("'count' must be an integer");
            }

            if ((int) $count > 200) {
                throw new \InvalidArgumentException("'count' may not be greater than 200");
            }
        
            $parameters['count'] = $count;
        }

        if ($since_id) {
            if (! is_numeric($since_id)) {
                throw new \InvalidArgumentException("'since_id' must be an integer");
            }
            
            $parameters['since_id'] = (int) $since_id;
        }

        if ($max_id) {
            if (! is_numeric($max_id)) {
                throw new \InvalidArgumentException("'max_id' must be an integer");
            }
            
            $parameters['max_id'] = (int) $max_id;
        }

        if ($trim_user) {
            $parameters['trim_user'] = 1;
        }

        if ($exclude_replies) {
            $parameters['exclude_replies'] = 1;
        }

        if ($contributor_details) {
            $parameters['contributor_details'] = 1;
        }

        if (! $include_entities) {
            $parameters['include_entities'] = 'false';
        }
        $json = $this->fetchUrl(url, 'GET', $parameters);
        $data = $this->parseAndCheckTwitter($json);
        return [Status::newFromJsonDict($x) for $x in data]
    }

    /**
     * Fetch the sequence of public Status messages for a single user.
     *
     * @param int user_id Specifies the ID of the user for whom to return the
     *  user_timeline. Helpful for disambiguating when a valid user ID
     *  is also a valid screen name. [Optional]
     * @param int screen_name Specifies the screen name of the user for whom to return the
     *  user_timeline. Helpful for disambiguating when a valid screen
     *  name is also a user ID. [Optional]
     * @param int $count Specifies the number of statuses to retrieve. May not be 
     *  greater than 200. Defaults to 20. [Optional]
     * @param int $since_id Returns results with an ID greater than (that is, more recent
     *  than) the specified ID. There are limits to the number of
     *  Tweets which can be accessed through the API. If the limit of
     *  Tweets has occurred since the since_id, the since_id will be
     *  forced to the oldest ID available. [Optional]
     * @param int $max_id Returns results with an ID less than (that is, older than) or
     *  equal to the specified ID. [Optional]
     * @param bool include_rts If true, the timeline will contain native retweets (if they
     *  exist) in addition to the standard stream of tweets. [Optional]
     * @param bool $trim_user  When true, each tweet returned in a timeline will include a user
     *  object including only the status authors numerical ID. Omit this
     *  parameter to receive the complete user object. [Optional]
     * @param bool $exclude_replies  This parameter will prevent replies from appearing in the
     *  returned timeline. Using exclude_replies with the count
     *  parameter will mean you will receive up-to count tweets -
     *  this is because the count parameter retrieves that many
     *  tweets before filtering out retweets and replies.
     *  [Optional]
     * @return array[League\Twitter\Status]
     */
    public function getUserTimeline(
        $user_id = null,
        $screen_name = null,
        $count = null,
        $since_id = null,
        $max_id = null,
        $include_rts = null,
        $trim_user = null,
        $exclude_replies = null
    ) {

        $parameters = array();

        $url = "{$this->base_url}/statuses/user_timeline.json";

        if ($user_id) {
            $parameters['user_id'] = $user_id;
        elseif ($screen_name) {
            $parameters['screen_name'] = $screen_name;
        }
        
        if ($since_id) {
            if (! is_numeric($since_id)) {
                throw new \InvalidArgumentException("'since_id' must be an integer");
            }
            $parameters['since_id'] = (int) $since_id;
        }

        if ($max_id) {
            if (! is_numeric($max_id)) {
                throw new \InvalidArgumentException("'max_id' must be an integer");
            }
            $parameters['max_id'] = (int) $max_id;
        }

        if ($count) {
            if (! is_numeric($count)) {
                throw new \InvalidArgumentException("'count' must be an integer");
            }
            $parameters['count'] = (int) $count;
        }

        if ($include_rts) {
            $parameters['include_rts'] = 1;
        }

        if ($trim_user) {
            $parameters['trim_user'] = 1;
        }

        if ($exclude_replies) {
            $parameters['exclude_replies'] = 1;
        }

        $json = $this->fetchUrl($url, 'GET', $parameters)
        $data = $this->parseAndCheckTwitter($json)
        return [Status.NewFromJsonDict($x) for $x in $data]
    }

    /**
     * Returns a single status message, specified by the id parameter.
     *
     * The League\Twitter\Api instance must be authenticated.
     *
     * @param int $id The numeric ID of the status you are trying to retrieve.
     * @param bool $trim_user When set to True, each tweet returned in a timeline will 
     *  include a user object including only the status authors numerical ID.
     *  Omit this parameter to receive the complete user object. [Optional]
     * @param bool $include_my_retweet When set to True, any Tweets returned that have 
     * been retweeted by the authenticating user will include an additional
     *  current_user_retweet node, containing the ID of the source status for the retweet. [Optional]
     * @param bool $include_entities If false, the entities node will be disincluded.
     *  This node offers a variety of metadata about the tweet in a discreet structure, 
     * including: user_mentions, urls, and hashtags. [Optional]
     * 
     * @return League\Twitter\Instance
     */
    public function getStatus(
        $id,
        $trim_user = false,
        $include_my_retweet = true,
        $include_entities = true
    ) {
        $url = "{$this->base_url}/statuses/show.json";

        if (! $this->_oauth_consumer) {
            throw new Exception("API must be authenticated.");
        }

        if (! is_numeric($id)) {
            throw new \InvalidArgumentException("'id' must be an integer");
        }
        
        $parameters = array('id' => (int) $id);

        if ($trim_user) {
            $parameters['trim_user'] = 1;
        }
        if ($include_my_retweet) {
            $parameters['include_my_retweet'] = 1;
        }
        if (! $include_entities) {
            $parameters['include_entities'] = 'none';
        }

        $json = $this->fetchUrl($url, 'GET', $parameters);
        $data = $this->parseAndCheckTwitter($json);
        return Status::newFromJsonDict($data);
    }
    
    /**
     * Destroys the status specified by the required ID parameter.
     *
     * The League\Twitter\Api instance must be authenticated.
     *
     * @param int $id The numeric ID of the status you are trying to retrieve.
     * @param bool $trim_user When set to True, each tweet returned in a timeline will 
     *  include a user object including only the status authors numerical ID.
     *  Omit this parameter to receive the complete user object. [Optional]
     *
     * @return League\Twitter\Status
     */
    public function destroyStatus($id, $trim_user = false)
    {
        if (! $this->_oauth_consumer) {
              throw new Exception("API must be authenticated.")
        }

        if (! is_numeric($id)) {
            throw new \InvalidArgumentException("'id' must be an integer");
        }

        $post_data = array('id': (int) $id);

        url  = "{$this->base_url}/statuses/destroy/{$id}.json";
        
        if ($trim_user) {
            $post_data['trim_user'] = 1;
        }

        $json = $this->fetchUrl($url, 'POST', $post_data);
        $data = $this->parseAndCheckTwitter($json);
        return Status::newFromJsonArray($data);
    }

    protected function calculateStatusLength($cls, $status, $linksize = 19) {
        $dummy_link = sprintf('https://-%d-chars%s/', $linksize, str_repeat('-', $linksize - 18));
        
        $parts = array_map(function($part) use ($dummy_link) {

            // If its not a URL, carry on with whatever it is
            if ( ! (strpos($part, 'http://') or strpos($part, 'https://'))) {
                return $part;

            // It is a URL, return the dummy link
            } else {
                return $dummy_link;
            }

        }, explode(' ', $status));

        return strlen(implode(' ', $parts));
    }

    /**
     * Post a twitter status message from the authenticated user.
     *
     * The League\Twitter\Api instance must be authenticated.
     *
     * @link https://dev.twitter.com/docs/api/1.1/post/statuses/update
     *
     * @param string $status The message text to be posted. Must be less 
     *   than or equal to 140 characters.
     * @param int in_reply_to_status_id The ID of an existing status that the status to 
     *   be posted is in reply to.  This implicitly sets the in_reply_to_user_id
     *   attribute of the resulting status to the user ID of the message being replied 
     *   to. Invalid/missing status IDs will be ignored. [Optional]
     * @param float $latitude Latitude coordinate of the tweet in degrees. 
     *   Will only work in conjunction with longitude argument. Both longitude and
     *   latitude will be ignored by twitter if the user has a false
     *   geo_enabled setting. [Optional]
     * @param float $longitude Longitude coordinate of the tweet in degrees. 
     *   Will only work in conjunction with latitude argument. Both longitude and
     *   latitude will be ignored by twitter if the user has a false
     *   geo_enabled setting. [Optional]
     * @param string $place_id A place in the world. These IDs can 
     * be retrieved from ET geo/reverse_geocode. [Optional]
     * @param bool $display_coordinates Whether or not to put a pin on the exact 
     *   coordinates a tweet as been sent from. [Optional]
     * @param bool $trim_user If true the returned payload will only contain the 
     *   user IDs, se the payload will contain the full user data item. [Optional]
     *
     * @return League\Twitter\Status
     */
    public postUpdate(
        $status,
        $in_reply_to_status_id = null,
        $latitude = null,
        $longitude = null,
        $place_id = null,
        $display_coordinates = false,
        $trim_user = false
    ) {

        if (! $this->_oauth_consumer) {
            throw new Exception("The League\Twitter\Api instance must be authenticated.");
        }

        $url = "{$this->base_url}/statuses/update.json";

        if ($this->calculateStatusLength($status, $this->_shortlink_size) > static::CHARACTER_LIMIT) {
            throw new \InvalidArgumentException("Text must be less than or equal to {static::CHARACTER_LIMIT} characters.");
        }

        $data = array('status' => $status);
        if ($in_reply_to_status_id) {
            $data['in_reply_to_status_id'] = $in_reply_to_status_id;
        }
        if (! (is_null($latitude) or is_null($longitude))) {
            $data['lat']     = (string) $latitude;
            $data['long']    = (string) $longitude;
        }
        if (! (is_null($place_id))) {
            $data['place_id'] = (string) $place_id;
        }
        if ($display_coordinates) {
            $data['display_coordinates'] = 'true';
        }
        if ($trim_user) {
            $data['trim_user'] = 'true';
        }
        $json = $this->fetchUrl($url, 'GET', $data);
        $data = $this->parseAndCheckTwitter($json);
        return Status::newFromJsonArray($data);
    }

    /**
     * Post one or more twitter status messages from the authenticated user.
     *
     * Unlike api.PostUpdate, this method will post multiple status updates
     * if the message is longer than 140 characters.
     *
     * The League\Twitter\Api instance must be authenticated.
     *
     * @link https://dev.twitter.com/docs/api/1.1/post/statuses/update
     *
     * @param string $status The message text to be posted. May be longer than 140 characters.
     * @param string $continuation The character string, if any, to be appended to all but the
     *   last message.  Note that Twitter strips trailing '...' strings from messages.  
     *   Consider using the unicode \u2026 character (horizontal ellipsis) instead.
     * @param array args See League\Twitter::postUpdate() for a list of accepted parameters.
     *
     * @return League\Twitter\Status
     */
    public function postUpdates($status, $continuation = null, array $args = null) {

        $results = array();

        if (is_null($continuation)) {
            $continuation = '';
        }
        
        $line_length = static::CHARACTER_LIMIT - strlen($continuation);
        $lines = $this->wordWrap($status, $line_length);

        $last_line = array_pop($lines);
        foreach ($lines as $line) {
            $results[] = $this->postUpdate($line.$continuation, $args);
        }
        $results[] = $this->postUpdate($last_line, $args);
        return $results;
    }

    /**
     * Retweet a tweet with the Retweet API.
     *
     * The League\Twitter\Api instance must be authenticated.
     * 
     * @param int $original_id The ID of the status to be retweeted.
     * @param bool $trim_user If true the returned payload will only contain the 
     *   user IDs, se the payload will contain the full user data item.
     *
     * @return League\Twitter\Status
     */
    public function postRetweet($original_id, $trim_user = false)
    {
        if (! $this->_oauth_consumer) {
            throw new Exception("The League\Twitter\Api instance must be authenticated.");
        }

        if (! is_numeric($original_id)) {
            throw new \InvalidArgumentException("'original_id' must be an integer");
        }
        if (! $original_id) <= 0) {
            throw new \InvalidArgumentException("'original_id' must be a positive number");
        }

        $url = sprintf('%s/statuses/retweet/%s.json', $this->base_url, $original_id);

        $data = array('id' => $original_id);

        if ($trim_user) {
            $data['trim_user'] = 'true';
        }

        $json = $this->fetchUrl($url, 'GET', $data)
        $data = $this->parseAndCheckTwitter($json);
        return Status::newFromJsonArray($data);
    }

    /**
     * Fetch the sequence of retweets made by the authenticated user.
     *
     * The League\Twitter\Api instance must be authenticated.
     *
     * @param int $since_id Returns results with an ID greater than (that is, more recent
     *   than) the specified ID. Th pear channel-discover pear.phpmd.orgere are limits to the number of Tweets which can be 
     *   accessed through the API. If the limit of Tweets has occurred since the since_id, 
     *   the since_id will be forced to the oldest ID available.
     * @param int $count The number of status messages to retrieve.
     * @param int $max_id Returns results with an ID less than (that is, older than) or
     *   equal to the specified ID.
     * @param int $trim_user If true the returned payload will only contain the user IDs,
     *   otherwise the payload will contain the full user data item.
     *
     * @return array[League\Twitter\Status]
     */
    public function getUserRetweets(
        $count = null, 
        $since_id = null, 
        $max_id = null, 
        $trim_user = false
    ) {
        return $this->getUserTimeline($since_id, $count, $max_id, $trim_user, $exclude_replies = true, $include_rts = true);
    }

    /**
     * Get status messages representing the 20 most recent replies.
     *
     * @param int $since_id Returns results with an ID greater than (that is, more recent
     *   than) the specified ID. There are limits to the number of Tweets which can be 
     *   accessed through the API. If the limit of Tweets has occurred since the since_id, 
     *   the since_id will be forced to the oldest ID available.
     * @param int $count The number of status messages to retrieve.
     * @param int $max_id Returns results with an ID less than (that is, older than) or
     *   equal to the specified ID.
     * @param int $trim_user If true the returned payload will only contain the user IDs,
     *   otherwise the payload will contain the full user data item.
     *
     * @return array[League\Twitter\Status]
     */
    public function getReplies($since_id = null, $count = null, $max_id = null, $trim_user = false) {
        return $this->getUserTimeline($since_id, $count, $max_id, $trim_user, $exclude_replies = false, $include_rts = false);
    }

    /**
     * Get retweets of a tweet
     *
     * @param int $status_id The ID of the tweet for which retweets should be searched for.
     * @param int $count The number of status messages to retrieve.
     * @param int $trim_user If true the returned payload will only contain the user IDs,
     *   otherwise the payload will contain the full user data item.
     *
     * @return array[League\Twitter\Status]
     */
    public function getRetweets($status_id, count=null, trim_user=false):

        if (! $this->_oauth_consumer) {
            throw new Exception("The League\Twitter\Api instsance must be authenticated.");
        }

        $url = sprintf('%s/statuses/retweets/%s.json', $this->base_url, $status_id);
        $parameters = array();
        if ($trim_user) {
            $parameters['trim_user'] = 'true';
        }
        if ($count) {
            if (! is_numeric($count)) {
              throw new \InvalidArgumentException('"count" must be an integer');
            }

            $parameters['count'] = (int) $count;
        }

        $json = $this->fetchUrl($url, 'GET', $parameters);
        $data = $this->parseAndCheckTwitter($json);
        return [Status::newFromJsonArray($s) for s in data];
    }

    /**
     * Get recent tweets of the user that have been retweeted by others.
     *
     * @param int $count The number of retweets to retrieve, up to 100. If omitted, 20 is assumed.
     * @param int $since_id Returns results with an ID greater than (newer than) this ID.
     * @param int $max_id Returns results with an ID less than or equal to this ID.
     * @param bool $trim_user When True, the user object for each tweet will only be an ID.
     * @param bool $include_entities When True, the tweet entities will be included.
     * @param bool $include_user_entities When True, the user entities will be included.
     *
     * @return array[League\Twitter\Status]
     */
    public function getRetweetsOfMe(
        $count = null,
        $since_id = null,
        $max_id = null,
        $trim_user = false,
        $include_entities = true,
        $include_user_entities = true
    ) {

        if (! $this->_oauth_consumer) {
            throw new Exception("The League\Twitter\Api instance must be authenticated.");
        }
        
        $url = sprintf('%s/statuses/retweets_of_me.json', $this->base_url);
        
        $parameters = array();

        if ($count) {
            if (! is_numeric($count)) {
              throw new \InvalidArgumentException('"count" must be an integer');
            }

            if ($count > 100) {
                throw new Exception("'count' may not be greater than 100");
            }

            $parameters['count'] = (int) $count;
        }
        
        if ($count) {
            $parameters['count'] = $count;
        }
        if ($since_id) {
            $parameters['since_id'] = $since_id;
        }
        if ($max_id) {
            $parameters['max_id'] = $max_id;
        }
        if ($trim_user) {
            $parameters['trim_user'] = $trim_user;
        if (! $include_entities) {
            $parameters['include_entities'] = $include_entities;
        }
        if (! $include_user_entities) {
            $parameters['include_user_entities'] = $include_user_entities;
        }
        
        $json = $this->fetchUrl($url, 'GET', $parameters);
        $data = $this->parseAndCheckTwitter($json);
        return [Status::newFromJsonArray($s) for s in data];
    }

    /**
     * Fetch users who are friends with the authenticated user.
     *
     * The League\Twitter\Api instance must be authenticated.
     *
     * @param int $user_id The twitter id of the user whose friends you are fetching.
     *   If not specified, defaults to the authenticated user.
     * @param string $screen_name The twitter name of the user whose friends you are fetching.
     *   If not specified, defaults to the authenticated user.
     * @param int $cursor Should be set to -1 for the initial call and then is used to
     *   control what result page Twitter returns.
     * @param bool $skip_status If true the statuses will not be returned in the user items.
     * @param bool $include_user_entities When true, the user entities will be included.
     *
     * @return array[League\Twitter\User]
     */
    public function getFriends(
        $user_id = null, 
        $screen_name = null, 
        $cursor = -1, 
        $skip_status = false, 
        $include_user_entities = false
    ) {

        if (! $this->_oauth_consumer) {
            throw new Exception("League\Twitter\Api instance must be authenticated");
        }

        $url = sprintf('%s/friends/list.json', $this->base_url);
        
        $result = array();
        $parameters = array();
        
        if ($user_id) {
            $parameters['user_id'] = $user_id;
        }

        if ($screen_name) {
            $parameters['screen_name'] = $screen_name;
        }

        if ($skip_status) {
            $parameters['skip_status'] = true;
        }
        if ($include_user_entities) {
            $parameters['include_user_entities'] = true;
        }
        while (true) {
            $parameters['cursor'] = $cursor;
            $json = $this->fetchUrl($url, 'GET', $parameters);
            $data = $this->parseAndCheckTwitter($json);
            $result += [User::newFromJsonArray(x) for x in data['users']];

            if (array_key_exists('next_cursor', $data) {
                if ($data['next_cursor'] == 0 or $data['next_cursor'] == $data['previous_cursor']) {
                    break;
                }
            } else {
                $cursor = $data['next_cursor'];
            } else {
                break;
            }
        }

        return $result;
    }

    /**
     * Fetch users who are friends with the authenticated user.
     *
     * The League\Twitter\Api instance must be authenticated.
     *
     * @param int $user_id The id of the user to retrieve the id list for.
     * @param string $screen_name The screen_name of the user to retrieve the id list for.
     * @param int $cursor Specifies the Twitter API Cursor location to start at.
     * @param bool $stringify_ids If true then twitter will return the ids as strings instead of integers.
     * @param int $count The number of status messages to retrieve.
     *
     * @return array[int]
     */
    public function getFriendIDs(
        $user_id = null,
        $screen_name = null,
        $cursor = -1,
        $stringify_ids = false,
        $count = null
    ) {

        $url = sprintf('%s/friends/ids.json', $this->base_url);
        if (! $this->_oauth_consumer) {
            throw new Exception("League\Twitter\Api instance must be authenticated");
        }
        $parameters = array();
        if (! is_null($user_id)) {
            $parameters['user_id'] = $user_id;
        }
        if (! is_null($screen_name)) {
            $parameters['screen_name'] = $screen_name;
        }
        if ($stringify_ids):
            $parameters['stringify_ids'] = true;
        }
        if (! is_null($count)) {
            $parameters['count'] = $count;
        }
        
        $result = array();

        while (true) {
            $parameters['cursor'] = $cursor;
            $json = $this->fetchUrl($url, 'GET', $parameters);
            $data = $this->parseAndCheckTwitter($json);
            $result += [x for x in data['ids']]
            if (array_key_exists('next_cursor', $data) {
                if ($data['next_cursor'] == 0 or $data['next_cursor'] == $data['previous_cursor']) {
                    break;
                } else {
                    $cursor = $data['next_cursor'];
                }
            } else {
                break;
            }
        }
        
        return $result;
    }

    /**
     * Returns a list of twitter user id's for every person that is following the specified user.
     * 
     * @param foo $user_id The id of the user to retrieve the id list for
     * @param foo $screen_name The screen_name of the user to retrieve the id list for
     * @param foo $cursor Specifies the Twitter API Cursor location to start at.
     * @param foo $stringify_ids if True then twitter will return the ids as strings instead of integers.
     * @param foo $count The number of status messages to retrieve. [Optional]
     *
     * @return array[int]
     */
    public function getFollowerIDs(
        $user_id = null, 
        $screen_name = null, 
        $cursor = -1, 
        $stringify_ids = false, 
        $count = null
    )
    {
        $url = sprint('%s/followers/ids.json', $this->base_url);

        if (! $this->_oauth_consumer) {
            throw new Exception("League\Twitter\Api instance must be authenticated");
        }

        $parameters = array();
        
        if (! is_null($user_id)) {
            $parameters['user_id'] = $user_id;
        }
        
        if (! is_null($screen_name)) {
            $parameters['screen_name'] = $screen_name;
        }
        
        if ($stringify_ids) {
            $parameters['stringify_ids'] = true;
        }
        
        if (! is_null($count)) {
            $parameters['count'] = $count;
        }

        $result = []
        while (true) {
            $parameters['cursor'] = $cursor;
            $json = $this->fetchUrl($url, 'GET', $parameters);
            $data = $this->parseAndCheckTwitter($json);
            $result += [x for x in data['ids']];
            if (array_key_exists('next_cursor', $data) {
                if ($data['next_cursor'] == 0 or $data['next_cursor'] == $data['previous_cursor']) {
                    break;
                }
              } else {
                $cursor = $data['next_cursor'];
            else {
                break;
            }
        }

        return $result;
    }

    /**
     * Fetch an array of users, one for each follower
     *
     * The League\Twitter\Api instance must be authenticated.
     *
     * @param int $user_id The twitter id of the user whose followers you are fetching.
     * @param string $screen_name The twitter name of the user whose followers you are fetching.
     * @param int $cursor Should be set to -1 for the initial call and then is used to control what result page Twitter returns 
     * @param bool $stringify_ids If true then twitter will return the ids as strings instead of integers.
     * @param bool $include_user_entities When true the user entities will be included.
     *
     * @return array[League\Twitter\User]
     */
    public function getFollowers($user_id = null, $screen_name = null, $cursor = -1, $skip_status = false, $include_user_entities = false)
    {
        if (! $this->_oauth_consumer) {
            throw new Exception("League\Twitter\Api instance must be authenticated");
        }
        $url = "{$this->base_url}/followers/list.json";
        result = []
        $parameters = array();
        
        if (! is_null($user_id)) {
            $parameters['user_id'] = $user_id;
        }

        if (! is_null($screen_name)) {
            $parameters['screen_name'] = $screen_name;
        }

        if ($skip_status !== false) {
            $parameters['skip_status'] = true;
        }

        if ($include_user_entities !== false) {
            $parameters['include_user_entities'] = true;
        }

        while (true) {
            $parameters['cursor'] = $cursor;
            $json = $this->fetchUrl($url, 'GET', $parameters);
            $data = $this->parseAndCheckTwitter($json);
            
            $result += [User::newFromJsonArray(x) for x in data['users']];
            
            if (isset($data['next_cursor'])) {
                if ($data['next_cursor'] == 0 or $data['next_cursor'] === $data['previous_cursor'] {
                    break;
                } else {
                    $cursor = $data['next_cursor'];
                }
            } else {
                break;
            }
        }
        return $result;
    }

    /**
     * Fetch extended information for the specified users.
     *
     * Users may be specified either as lists of either user_ids,
     * screen_names, or League\Twitter\User objects. The list of users that
     * are queried is the union of all specified parameters.
     *
     * The League\Twitter\Api instance must be authenticated.
     *
     * @param int $user_id The twitter id of the user you are looking up
     * @param string $screen_name The twitter csv string of users you are looking up
     * @param array[League\Twitter\User] $users User objects to look up
     * @param bool $include_user_entities The entities node that may appear within embedded 
     *   statuses will be disincluded when set to false
     *
     * @return array[League\Twitter\User]
     */
    public function usersLookup($user_id = null, $screen_name = null, $users = null, $include_entities  = true)
    {
        if (! $this->_oauth_consumer) {
            throw new Exception("The League\Twitter\Api instance must be authenticated.");
        }

        if (! $user_id and ! $screen_name and ! $users) {
            throw new Exception("Specify at least one of user_id, screen_name, or users.");
        }
        
        $url = "{$this->base_url}/users/lookup.json";
        
        $parameters = array();
        $uids = array();
        
        if ($user_id) {
            $uids[] = $user_id;
        }

        if ($users) {
            $uids = array_merge($uids, array_map(function($user) {
                return $user->id;
            }, $users));
        }
        
        if ($uids !== array()) {
            $parameters['user_id'] = implode(',', $uids);
        }
        
        if ($screen_name) {
            $parameters['screen_name'] = implode(',', $screen_name);
        }

        if (! $include_entities)
            $parameters['include_entities'] = 'false';
        }
        
        $json = $this->fetchUrl($url, 'GET', $parameters);
        try {
          $data = $this->parseAndCheckTwitter($json);
        } catch (Exception $e) {
            if ($e->getCode() == 34) {
                $data = array();
            } else {
                throw new Exception;
            }
        }

        return array_map(function($user) {
            return User::newFromJsonArray($user);
        }, $data);
    }

    /**
     * Fetch extended information for the specified users.
     *
     * Users may be specified either as arrays of either user_ids,
     * screen_names, or League\Twitter\User objects. The list of users that
     * are queried is the union of all specified parameters.
     *
     * The League\Twitter\Api instance must be authenticated.
     *
     * @param int $user_id The twitter id of the user to retrieve
     * @param string $screen_name The twitter name of the user whose followers you are fetching.
     * @param bool $include_user_entities If set to false, the 'entities' node will not be included
     *
     * @return League\Twitter\User
     */
    public function getUser($user_id = null, $screen_name = null, $include_entities = true)
    {
        $url  = "{$this->base_url}/users/show.json";
        $parameters = array();

        if (! $this->_oauth_consumer) {
            throw new Exception("The League\Twitter\Api instance must be authenticated.");
        }

        if ($user_id) {
            $parameters['user_id'] = $user_id;
        } elseif ($screen_name) {
            $parameters['screen_name'] = $screen_name;
        } else {
            throw new Exception("Specify at least one of user_id or screen_name.");
        }

        if (! $include_entities) {
            $parameters['include_entities'] = 'false';
        }

        $json = $this->fetchUrl($url, 'GET', $parameters);
        $data = $this->parseAndCheckTwitter($json);
        return User::newFromJsonArray(data);
    }

    /**
     * Returns a list of the direct messages sent to the authenticating user
     *
     * The League\Twitter\Api instance must be authenticated.
     *
     * @param int $since_id Returns results with an ID greater than (that is, more recent 
     *   than) the specified ID. There are limits to the number of Tweets which can be 
     *   accessed through the API. If the limit of Tweets has occurred since the since_id, 
     *   the since_id will be forced to the oldest ID available.
     * @param int $max_id Returns results with an ID less than (that is, older than) or
     *   equal to the specified ID.
     * @param int $count Specifies the number of direct messages to try and retrieve, 
     *   up to a maximum of 200. The value of count is best thought of as a limit to the
     *   number of Tweets to return because suspended or deleted content is removed after 
     *   the count has been applied.
     * @param bool $include_entities The entities node will not be included when set to false.
     * @param bool $skip_status When set to True statuses will not be included in the returned 
     *   user objects.
     *
     * @return array[League\Twitter\DirectMessage]
     */
    public function getDirectMessages($since_id = null, $max_id = null, $count = null, $include_entities = true, $skip_status = false)
    {
        $url = "{$this->base_url}/direct_messages.json";
        
        if (! $this->_oauth_consumer) {
            throw new Exception("The League\Twitter\Api instance must be authenticated.");
        }
     
        $parameters = array();
     
        if ($since_id) {
            $parameters['since_id'] = $since_id;
        }
        
        if ($max_id) {
            $parameters['max_id'] = $max_id;
        }
        
        if ($count) {
          if (! is_numeric($count)) {
              throw new Exception("count must be an integer");
          }
          $parameters['count'] = (int) $count;     
        }

        if (! $include_entities) {
            $parameters['include_entities'] = 'false';
        }

        if ($skip_status !== false) {
            $parameters['skip_status'] = 1;
        }

        $json = $this->fetchUrl($url, 'GET', $parameters);
        $data = $this->parseAndCheckTwitter($json);
        
        return array_map(function($message) {
            return DirectMessage::newFromJsonArray(x);
        }, $data);
    }
    
    /**
     * Returns a list of the direct messages sent by the authenticating user
     *
     * The League\Twitter\Api instance must be authenticated.
     *
     * @param int $since_id Returns results with an ID greater than (that is, more recent 
     *   than) the specified ID. There are limits to the number of Tweets which can be 
     *   accessed through the API. If the limit of Tweets has occurred since the $since_id, 
     *   the $since_id will be forced to the oldest ID available.
     * @param int $max_id Returns results with an ID less than (that is, older than) or
     *   equal to the specified ID.
     * @param int $count Specifies the number of direct messages to try and retrieve, 
     *   up to a maximum of 200. The value of count is best thought of as a limit to the
     *   number of Tweets to return because suspended or deleted content is removed after 
     *   the count has been applied.
     * @param int $page Specifies the page of results to retrieve. Note: there are pagination limits.
     * @param bool $include_entities The entities node will not be included when set to false.
     *
     * @return array[League\Twitter\DirectMessage]
     */
    public function getSentDirectMessages(
        $since_id = null,
        $max_id = null,
        $count = null,
        $page = null,
        $include_entities = true
    )
    {
        $url = "{$this->base_url}/direct_messages/sent.json";
        
        if (! $this->_oauth_consumer) {
            throw new Exception("The League\Twitter\Api instance must be authenticated.");
        }
        $parameters = array();
        
        if ($since_id) {
            $parameters['since_id'] = $since_id;
        }
        if ($page) {
            $parameters['page'] = $page;
        }
        if ($max_id) {
            $parameters['max_id'] = $max_id;
        }
        if ($count) {
            if (! is_numeric($count)) {
                throw new Exception("count must be an integer");
            }
            $parameters['count'] = (int) $count;
        }

        if (! $include_entities) {
            $parameters['include_entities'] = 'false';
        }

        $json = $this->fetchUrl($url, 'GET', $parameters);
        $data = $this->parseAndCheckTwitter($json);

        return array_map(function($message) {
            return DirectMessage::newFromJsonArray(x);
        }, $data);
    }

    /**
     * Returns a list of the direct messages sent by the authenticating user
     *
     * The League\Twitter\Api instance must be authenticated.
     *
     * @param string $text The message text to be posted.  Must be less than 140 characters.
     * @param int $user_id A list of user_ids to retrieve extended information.
     * @param string $screen_name A list of screen_names to retrieve extended information.
     *
     * @return League\Twitter\DirectMessage
     */
    public function postDirectMessage($text, $user_id = null, $screen_name = null)
    {
        if (! $this->_oauth_consumer) {
            throw new Exception("The League\Twitter\Api instance must be authenticated.");
        }

        $url  = "{$this->base_url}/direct_messages/new.json";
        $post = array('text' => $text);
        
        if ($user_id) {
            $post['user_id'] = $user_id;
        } elseif ($screen_name) {
            $post['screen_name'] = $screen_name;
        } else {
            throw new Exception("Specify at least one of user_id or screen_name.");
        }

        $json = $this->fetchUrl($url, 'POST', $post)
        $data = $this->parseAndCheckTwitter($json);

        return DirectMessage::newFromJsonArray($data);
    }

    /**
     * Destroys the direct message specified in the required ID parameter.
     *
     * The League\Twitter\Api instance must be authenticated, and the
     * authenticating user must be the recipient of the specified direct message.
     *
     * @param int $id The id of the direct message to be destroyed.
     * @param string $include_entities The entities node will not be included when set to false.
     *
     * @return League\Twitter\DirectMessage
     */
    public function destroyDirectMessage($id, $include_entities = true)
    {
        $url  = "{$this->base_url}/direct_messages/destroy.json";
        $post = array('id' => $id); 

        if (! $include_entities) {
            $post['include_entities'] = 'false';
        }

        $json = $this->fetchUrl($url, $post)
        $data = $this->parseAndCheckTwitter($json);
        
        return DirectMessage::newFromJsonArray($data);
    }

    /**
     * Befriends the user specified by the user_id or screen_name.
     *
     * The League\Twitter\Api instance must be authenticated.
     *
     * @param int $user_id A user_id to follow
     * @param string $screen_name A screen_name to follow
     * @param bool $follow Set to false to disable notifications for the target user
     *
     * @return League\Twitter\User
     */
    public function createFriendship($user_id = null, $screen_name = null, $follow = true)
    {
        $url  = "{$this->base_url}/friendships/create.json";
        
        $data = array();

        if ($user_id) {
            $data['user_id'] = $user_id;
        } elseif ($screen_name) {
            $data['screen_name'] = $screen_name;
        } else {
            throw new Exception("Specify at least one of user_id or screen_name.")
        }

        $data['follow'] = $follow ? 'true' : 'false';

        $json = $this->fetchUrl($url, 'POST', $data);
        $data = $this->parseAndCheckTwitter($json);

        return User::newFromJsonArray($data);
    }

    /**
     * Discontinues friendship with a user_id or screen_name.
     *
     * The League\Twitter\Api instance must be authenticated.
     *
     * @param int $user_id A user_id to follow
     * @param string $screen_name A screen_name to follow
     *
     * @return League\Twitter\User
     */
    public function destroyFriendship($user_id = null, $screen_name = null)
    {
        $url  = "{$this->base_url}/friendships/destroy.json";
        $data = array();
        if ($user_id) {
            $data['screen_name'] = $user_id;
        } elseif ($screen_name) {
            $data['screen_name'] = $screen_name;
        } else {
            throw new Exception("Specify at least one of user_id or screen_name.");
        }
        $json = $this->fetchUrl($url, 'POST', $data);
        $data = $this->parseAndCheckTwitter($json);
        return User::newFromJsonArray(data)
    }

    /**
     * Favorites the specified status object or id as the authenticating user.
     *
     * The League\Twitter\Api instance must be authenticated.
     *
     * @param int $id The id of the twitter status to mark as a favorite
     * @param League\Twitter\Status $status The League\Twitter\Status object to mark as a favorite
     * @param bool include_entities The entities node will be omitted when set to false.
     *
     * @return League\Twitter\Status
     */
    public function createFavorite(status=null, id=null, include_entities = true)
    {

      
    Returns:
      A twitter.Status instance representing the newly-marked favorite.
    '''
    $url  = "{$this->base_url}/favorites/create.json";
    $data = array();
    if id:
      data['id'] = id
    elseif status:
      data['id'] = status.id
    } else {
        throw new Exception("Specify id or status")
    if (! $include_entities) {
      data['include_entities'] = 'false'
    $json = $this->fetchUrl($url, $data)
    $data = $this->parseAndCheckTwitter($json);
    return Status::newFromJsonArray($data);

    def DestroyFavorite(status=null, id=null, include_entities = true):
    '''Un-Favorites the specified status object or id as the authenticating user.
    Returns the un-favorited status when successful.

    The League\Twitter\Api instance must be authenticated.

    Args:
      id:
        The id of the twitter status to unmark as a favorite.
        [Optional]
      status:
        The twitter.Status object to unmark as a favorite.
        [Optional]
      include_entities:
        The entities node will be omitted when set to false.
    Returns:
      A twitter.Status instance representing the newly-unmarked favorite.
    '''
    $url  = "{$this->base_url}/favorites/destroy.json";
    $data = array();
    if id:
      data['id'] = id
    elseif status:
      data['id'] = status.id
    } else {
        throw new Exception("Specify id or status")
    if (! $include_entities) {
      data['include_entities'] = 'false'
    $json = $this->fetchUrl($url, $data)
    $data = $this->parseAndCheckTwitter($json);
    return Status::newFromJsonArray($data);

    public function getFavorites(self,
                   user_id=null,
                   screen_name=null,
                   count=null,
                   since_id=null,
                   max_id=null,
                   include_entities = true):
    '''Return a list of Status objects representing favorited tweets.
    By default, returns the (up to) 20 most recent tweets for the
    authenticated user.

    Args:
      user:
        The twitter name or id of the user whose favorites you are fetching.
        If not specified, defaults to the authenticated user. [Optional]
      page:
        Specifies the page of results to retrieve.
        Note: there are pagination limits. [Optional]
    '''
    $parameters = array();

    $url = "{$this->base_url}/favorites/list.json";

    if ($user_id) {
        $parameters['user_id'] = $user_id;
    elseif ($screen_name) {
      parameters['screen_name'] = user_id

    if ($since_id) {
      try:
        parameters['since_id'] = long(since_id)
      except:
          throw new Exception("since_id must be an integer")

    if ($max_id) {
      try:
        parameters['max_id'] = long(max_id)
      except:
          throw new Exception("max_id must be an integer")

    if count:
      try:
        parameters['count'] = int(count)
      except:
          throw new Exception("count must be an integer")

    if include_entities:
        parameters['include_entities'] = True


    $json = $this->fetchUrl($url, 'GET', $parameters);
    $data = $this->parseAndCheckTwitter($json);
    return [Status.NewFromJsonDict(x) for x in data]

    public function getMentions(self,
                  count=null,
                  since_id=null,
                  max_id=null,
                  trim_user=false,
                  contributor_details=false,
                  include_entities = true):
    '''Returns the 20 most recent mentions (status containing @screen_name)
    for the authenticating user.

    Args:
      count:
        Specifies the number of tweets to try and retrieve, up to a maximum of
        200. The value of count is best thought of as a limit to the number of
        tweets to return because suspended or deleted content is removed after
        the count has been applied. [Optional]
      since_id:
        Returns results with an ID greater than (that is, more recent
        than) the specified ID. There are limits to the number of
        Tweets which can be accessed through the API. If the limit of
        Tweets has occurred since the since_id, the since_id will be
        forced to the oldest ID available. [Optional]
      max_id:
        Returns only statuses with an ID less than
        (that is, older than) the specified ID.  [Optional]
      trim_user:
        When set to True, each tweet returned in a timeline will include a user
        object including only the status authors numerical ID. Omit this
        parameter to receive the complete user object.
      contributor_details:
        If set to True, this parameter enhances the contributors element of the
        status response to include the screen_name of the contributor. By
        default only the user_id of the contributor is included.
      include_entities:
        The entities node will be disincluded when set to false.

    Returns:
      A sequence of twitter.Status instances, one for each mention of the user.
    '''

    $url = "{$this->base_url}/statuses/mentions_timeline.json";

    if (! $this->_oauth_consumer) {
        throw new Exception("The League\Twitter\Api instance must be authenticated.");

    $parameters = array();

    if count:
      try:
        parameters['count'] = int(count)
      except:
          throw new Exception("count must be an integer")
    if ($since_id) {
      try:
        parameters['since_id'] = long(since_id)
      except:
          throw new Exception("since_id must be an integer")
    if ($max_id) {
      try:
        parameters['max_id'] = long(max_id)
      except:
          throw new Exception("max_id must be an integer")
    if ($trim_user) {
      parameters['trim_user'] = 1
    if contributor_details:
      parameters['contributor_details'] = 'true'
    if (! $include_entities) {
        $parameters['include_entities'] = 'false';

    $json = $this->fetchUrl($url, 'GET', $parameters);
    $data = $this->parseAndCheckTwitter($json);
    return [Status.NewFromJsonDict(x) for x in data]

    public function createList(name, mode=null, description=null):
    '''Creates a new list with the give name for the authenticated user.

    The League\Twitter\Api instance must be authenticated.

    Args:
      name:
        New name for the list
      mode:
        'public' or 'private'.
        Defaults to 'public'. [Optional]
      description:
        Description of the list. [Optional]

    Returns:
      A twitter.List instance representing the new list
    '''
    $url = "{$this->base_url}/lists/create.json";

    if (! $this->_oauth_consumer) {
        throw new Exception("The League\Twitter\Api instance must be authenticated.");
    parameters = {'name': name}
    if mode is not null:
      parameters['mode'] = mode
    if description is not null:
      parameters['description'] = description
    json = $this->_FetchUrl(url, post_data=parameters)
    $data = $this->parseAndCheckTwitter($json);
    return List.NewFromJsonDict(data)

    def DestroyList(self,
                  owner_screen_name=false,
                  owner_id=false,
                  list_id=null,
                  slug=null):
    '''
    Destroys the list identified by list_id or owner_screen_name/owner_id and
    slug.

    The League\Twitter\Api instance must be authenticated.

    Args:
      owner_screen_name:
        The screen_name of the user who owns the list being requested by a slug.
      owner_id:
        The user ID of the user who owns the list being requested by a slug.
      list_id:
        The numerical id of the list.
      slug:
        You can identify a list by its slug instead of its numerical id. If you
        decide to do so, note that you'll also have to specify the list owner
        using the owner_id or owner_screen_name parameters.
    Returns:
      A twitter.List instance representing the removed list.
    '''
    $url  = "{$this->base_url}/lists/destroy.json";
    $data = array();
    if list_id:
      try:
        data['list_id']= long(list_id)
      except:
          throw new Exception("list_id must be an integer")
    elseif slug:
      data['slug'] = slug
      if owner_id:
        try:
          data['owner_id'] = long(owner_id)
        except:
            throw new Exception("owner_id must be an integer")
      elseif owner_screen_name:
        data['owner_screen_name'] = owner_screen_name
      } else {
          throw new Exception("Identify list by list_id or owner_screen_name/owner_id and slug")
    } else {
        throw new Exception("Identify list by list_id or owner_screen_name/owner_id and slug")

    $json = $this->fetchUrl($url, $data)
    $data = $this->parseAndCheckTwitter($json);
    return List.NewFromJsonDict(data)

    public function createSubscription(self,
                  owner_screen_name=false,
                  owner_id=false,
                  list_id=null,
                  slug=null):
    '''Creates a subscription to a list by the authenticated user

    The League\Twitter\Api instance must be authenticated.

    Args:
      owner_screen_name:
        The screen_name of the user who owns the list being requested by a slug.
      owner_id:
        The user ID of the user who owns the list being requested by a slug.
      list_id:
        The numerical id of the list.
      slug:
        You can identify a list by its slug instead of its numerical id. If you
        decide to do so, note that you'll also have to specify the list owner
        using the owner_id or owner_screen_name parameters.
    Returns:
      A twitter.List instance representing the list subscribed to
    '''
    $url  = "{$this->base_url}/lists/subscribers/create.json";
    if (! $this->_oauth_consumer) {
        throw new Exception("The League\Twitter\Api instance must be authenticated.");
    $data = array();
    if list_id:
      try:
        data['list_id']= long(list_id)
      except:
          throw new Exception("list_id must be an integer")
    elseif slug:
      data['slug'] = slug
      if owner_id:
        try:
          data['owner_id'] = long(owner_id)
        except:
            throw new Exception("owner_id must be an integer")
      elseif owner_screen_name:
        data['owner_screen_name'] = owner_screen_name
      } else {
          throw new Exception("Identify list by list_id or owner_screen_name/owner_id and slug")
    } else {
        throw new Exception("Identify list by list_id or owner_screen_name/owner_id and slug")
    $json = $this->fetchUrl($url, $data)
    $data = $this->parseAndCheckTwitter($json);
    return List.NewFromJsonDict(data)

    def DestroySubscription(self,
                  owner_screen_name=false,
                  owner_id=false,
                  list_id=null,
                  slug=null):
    '''Destroys the subscription to a list for the authenticated user

    The League\Twitter\Api instance must be authenticated.

    Args:
      owner_screen_name:
        The screen_name of the user who owns the list being requested by a slug.
      owner_id:
        The user ID of the user who owns the list being requested by a slug.
      list_id:
        The numerical id of the list.
      slug:
        You can identify a list by its slug instead of its numerical id. If you
        decide to do so, note that you'll also have to specify the list owner
        using the owner_id or owner_screen_name parameters.
    Returns:
      A twitter.List instance representing the removed list.
    '''
    $url  = "{$this->base_url}/lists/subscribers/destroy.json";
    if (! $this->_oauth_consumer) {
        throw new Exception("The League\Twitter\Api instance must be authenticated.");
    $data = array();
    if list_id:
      try:
        data['list_id']= long(list_id)
      except:
          throw new Exception("list_id must be an integer")
    elseif slug:
      data['slug'] = slug
      if owner_id:
        try:
          data['owner_id'] = long(owner_id)
        except:
            throw new Exception("owner_id must be an integer")
      elseif owner_screen_name:
        data['owner_screen_name'] = owner_screen_name
      } else {
          throw new Exception("Identify list by list_id or owner_screen_name/owner_id and slug")
    } else {
        throw new Exception("Identify list by list_id or owner_screen_name/owner_id and slug")
    $json = $this->fetchUrl($url, $data)
    $data = $this->parseAndCheckTwitter($json);
    return List.NewFromJsonDict(data)

    public function getSubscriptions(user_id=null, screen_name=null, count=20, cursor=-1):
    '''
    Obtain a collection of the lists the specified user is subscribed to, 20
    lists per page by default. Does not include the user's own lists.

    The League\Twitter\Api instance must be authenticated.

    Args:
      user_id:
        The ID of the user for whom to return results for. [Optional]
      screen_name:
        The screen name of the user for whom to return results for.
        [Optional]
      count:
       The amount of results to return per page. Defaults to 20.
       No more than 1000 results will ever be returned in a single page.
      cursor:
        "page" value that Twitter will use to start building the
        list sequence from.  -1 to start at the beginning.
        Twitter will return in the result the values for next_cursor
        and previous_cursor. [Optional]

    Returns:
      A sequence of twitter.List instances, one for each list
    '''
    if (! $this->_oauth_consumer) {
        throw new Exception("League\Twitter\Api instance must be authenticated")

    $url = "{$this->base_url}/lists/subscriptions.json";
    $parameters = array();

    try:
      parameters['cursor'] = int(cursor)
    except:
        throw new Exception("cursor must be an integer")

    try:
      parameters['count'] = int(count)
    except:
        throw new Exception("count must be an integer")

    if (! is_null($user_id)) {
      try:
        parameters['user_id'] = long(user_id)
      except:
          throw new Exception('user_id must be an integer')
    elseif (! is_null($screen_name)) {
        $parameters['screen_name'] = $screen_name;
    } else {
        throw new Exception('Specify user_id or screen_name')

    $json = $this->fetchUrl($url, 'GET', $parameters);
    $data = $this->parseAndCheckTwitter($json);
    return [List.NewFromJsonDict(x) for x in data['lists']]

    public function getLists(user_id=null, screen_name=null, count=null, cursor=-1):
    '''Fetch the sequence of lists for a user.

    The League\Twitter\Api instance must be authenticated.

    Args:
      user_id:
        The ID of the user for whom to return results for. [Optional]
      screen_name:
        The screen name of the user for whom to return results for.
        [Optional]
      count:
        The amount of results to return per page. Defaults to 20. No more than
        1000 results will ever be returned in a single page.
        [Optional]
      cursor:
        "page" value that Twitter will use to start building the
        list sequence from.  -1 to start at the beginning.
        Twitter will return in the result the values for next_cursor
        and previous_cursor. [Optional]

    Returns:
      A sequence of twitter.List instances, one for each list
    '''
    if (! $this->_oauth_consumer) {
        throw new Exception("League\Twitter\Api instance must be authenticated")

    $url = "{$this->base_url}/lists/ownerships.json";
    result = []
    $parameters = array();
    if (! is_null($user_id)) {
      try:
        parameters['user_id'] = long(user_id)
      except:
          throw new Exception('user_id must be an integer')
    elseif (! is_null($screen_name)) {
        $parameters['screen_name'] = $screen_name;
    } else {
        throw new Exception('Specify user_id or screen_name')
    if (! is_null($count)) {
      parameters['count'] = count

    while (true) {
        $parameters['cursor'] = $cursor;
      $json = $this->fetchUrl($url, 'GET', $parameters);
      $data = $this->parseAndCheckTwitter($json);
      result += [List.NewFromJsonDict(x) for x in data['lists']]
      if 'next_cursor' in data:
        if data['next_cursor'] == 0 or data['next_cursor'] == data['previous_cursor']:
          break
        } else {
          cursor = data['next_cursor']
      } else {
        break
    return $result;

    def VerifyCredentials(self):
    '''Returns a League\Twitter\User instance if the authenticating user is valid.

    Returns:
      A League\Twitter\User instance representing that user if the
      credentials are valid, null otherwise.
    '''
    if (! $this->_oauth_consumer) {
        throw new Exception("Api instance must first be given user credentials.")
    $url = "{$this->base_url}/account/verify_credentials.json";
    try:
      json = $this->_FetchUrl(url, no_cache = true)
    except urllib2.HTTPError, http_error:
      if http_error.code == httplib.UNAUTHORIZED:
        return null
      } else {
        raise http_error
    $data = $this->parseAndCheckTwitter($json);
    return User::newFromJsonArray(data)

    def SetCache(cache):
    '''Override the default cache.  Set to null to prevent caching.

    Args:
      cache:
        An instance that supports the same API as the twitter._FileCache
    '''
    if cache == DEFAULT_CACHE:
      $this->_cache = _FileCache()
    } else {
      $this->_cache = cache

    def SetUrllib(urllib):
    '''Override the default urllib implementation.

    Args:
      urllib:
        An instance that supports the same API as the urllib2 module
    '''
    $this->_urllib = urllib

    def SetCacheTimeout(cache_timeout):
    '''Override the default cache timeout.

    Args:
      cache_timeout:
        Time, in seconds, that responses should be reused.
    '''
    $this->_cache_timeout = cache_timeout

    def SetUserAgent(user_agent):
    '''Override the default user agent

    Args:
      user_agent:
        A string that should be send to the server as the User-agent
    '''
    $this->_request_headers['User-Agent'] = user_agent

    def SetXTwitterHeaders(client, url, version):
    '''Set the X-Twitter HTTP headers that will be sent to the server.

    Args:
      client:
         The client name as a string.  Will be sent to the server as
         the 'X-Twitter-Client' header.
      url:
         The URL of the meta.xml as a string.  Will be sent to the server
         as the 'X-Twitter-Client-URL' header.
      version:
         The client version as a string.  Will be sent to the server
         as the 'X-Twitter-Client-Version' header.
    '''
    $this->_request_headers['X-Twitter-Client'] = client
    $this->_request_headers['X-Twitter-Client-URL'] = url
    $this->_request_headers['X-Twitter-Client-Version'] = version

    def SetSource(source):
    '''Suggest the "from source" value to be displayed on the Twitter web site.

    The value of the 'source' parameter must be first recognized by
    the Twitter server.  New source values are authorized on a case by
    case basis by the Twitter development team.

    Args:
      source:
        The source name as a string.  Will be sent to the server as
        the 'source' parameter.
    '''
    $this->_default_params['source'] = source

    public function getRateLimitStatus(resources=null):
    '''Fetch the rate limit status for the currently authorized user.

    Args:
      resources:
        A comma seperated list of resource families you want to know the current
        rate limit disposition of.
        [Optional]

    Returns:
      A dictionary containing the time the limit will reset (reset_time),
      the number of remaining hits allowed before the reset (remaining_hits),
      the number of hits allowed in a 60-minute period (hourly_limit), and
      the time of the reset in seconds since The Epoch (reset_time_in_seconds).
    '''
    $parameters = array();
    if resources is not null:
      parameters['resources'] = resources

    $url  = "{$this->base_url}/application/rate_limit_status.json";
    json = $this->_FetchUrl(url, parameters=parameters, no_cache = true)
    $data = $this->parseAndCheckTwitter($json);
    return data

    def MaximumHitFrequency(self):
    '''Determines the minimum number of seconds that a program must wait
    before hitting the server again without exceeding the rate_limit
    imposed for the currently authenticated user.

    Returns:
      The minimum second interval that a program must use so as to not
      exceed the rate_limit imposed for the user.
    '''
    rate_status = $this->GetRateLimitStatus()
    reset_time  = rate_status.get('reset_time', null)
    limit       = rate_status.get('remaining_hits', null)

    if reset_time:
      # put the reset time into a datetime object
      reset = datetime.datetime(*rfc822.parsedate(reset_time)[:7])

      # find the difference in time between now and the reset time + 1 hour
      delta = reset + datetime.timedelta(hours=1) - datetime.datetime.utcnow()

      if not limit:
          return int(delta.seconds)

      # determine the minimum number of seconds allowed as a regular interval
      max_frequency = int(delta.seconds / limit) + 1

      # return the number of seconds
      return max_frequency

    return 60

    def _BuildUrl(url, path_elements=null, extra_params=null):
    # Break url into constituent parts
    (scheme, netloc, path, params, query, fragment) = urlparse.urlparse(url)

    # Add any additional path elements to the path
    if path_elements:
      # Filter out the path elements that have a value of null
      p = [i for i in path_elements if i]
      if not path.endswith('/'):
        path += '/'
      path += '/'.join(p)

    # Add any additional query parameters to the query string
    if extra_params and len(extra_params) > 0:
      extra_query = $this->_EncodeParameters(extra_params)
      # Add it to the existing query
      if query:
        query += '&' + extra_query
      } else {
        query = extra_query

    # Return the rebuilt URL
    return urlparse.urlunparse((scheme, netloc, path, params, query, fragment))

    def _InitializeRequestHeaders(request_headers):
    if request_headers:
      $this->_request_headers = request_headers
    } else {
      $this->_request_headers = {}

    def _InitializeUserAgent(self):
    user_agent = 'Python-urllib/%s (python-twitter/%s)' % \
                 ($this->_urllib.__version__, __version__)
    $this->SetUserAgent(user_agent)

    def _InitializeDefaultParameters(self):
    $this->_default_params = array();

    def _DecompressGzippedResponse(response):
    raw_data = response.read()
    if response.headers.get('content-encoding', null) == 'gzip':
      url_data = gzip.GzipFile(fileobj=StringIO.StringIO(raw_data)).read()
    } else {
      url_data = raw_data
    return url_data

    def _Encode(s):
    if $this->_input_encoding:
      return unicode(s, $this->_input_encoding).encode('utf-8')
    } else {
      return unicode(s).encode('utf-8')

    def _EncodeParameters(parameters):
    '''Return a string in key=value&key=value form

    Values of null are not included in the output string.

    Args:
      parameters:
        A dict of (key, value) tuples, where value is encoded as
        specified by $this->_encoding

    Returns:
      A URL-encoded string in "key=value&key=value" form
    '''
    if parameters is null:
      return null
    } else {
      return urllib.urlencode(dict([(k, $this->_Encode(v)) for k, v in parameters.items() if v is not null]))

    def _EncodePostData(post_data):
    '''Return a string in key=value&key=value form

    Values are assumed to be encoded in the format specified by $this->_encoding,
    and are subsequently URL encoded.

    Args:
      post_data:
        A dict of (key, value) tuples, where value is encoded as
        specified by $this->_encoding

    Returns:
      A URL-encoded string in "key=value&key=value" form
    '''
    if post_data is null:
      return null
    } else {
      return urllib.urlencode(dict([(k, $this->_Encode(v)) for k, v in post_data.items()]))

    def _ParseAndCheckTwitter(json):
    """Try and parse the JSON returned from Twitter and return
    an empty dictionary if there is any error. This is a purely
    defensive check because during some Twitter network outages
    it will return an HTML failwhale page."""
    try:
      data = simplejson.loads(json)
      $this->_CheckForTwitterError(data)
    except ValueError:
      if "<title>Twitter / Over capacity</title>" in json:
          throw new Exception("Capacity Error")
      if "<title>Twitter / Error</title>" in json:
          throw new Exception("Technical Error")
        throw new Exception("json decoding")

    return data

    def _CheckForTwitterError(data):
    """Raises a TwitterError if twitter returns an error message.

    Args:
      data:
        A python dict created from the Twitter json response

    Raises:
      TwitterError wrapping the twitter error message if one exists.
    """
    # Twitter errors are relatively unlikely, so it is faster
    # to check first, rather than try and catch the exception
    if 'error' in data:
        throw new Exception(data['error'])
    if 'errors' in data:
        throw new Exception(data['errors'])

    def _FetchUrl(self,
                url,
                post_data=null,
                parameters=null,
                no_cache=null,
                use_gzip_compression=null):
    '''Fetch a URL, optionally caching for a specified time.

    Args:
      url:
        The URL to retrieve
      post_data:
        A dict of (str, unicode) key/value pairs.
        If set, POST will be used.
      parameters:
        A dict whose key/value pairs should encoded and added
        to the query string. [Optional]
      no_cache:
        If true, overrides the cache on the current request
      use_gzip_compression:
        If True, tells the server to gzip-compress the response.
        It does not apply to POST requests.
        Defaults to null, which will get the value to use from
        the instance variable $this->_use_gzip [Optional]

    Returns:
      A string containing the body of the response.
    '''
    # Build the extra parameters dict
    extra_params = {}
    if $this->_default_params:
      extra_params.update($this->_default_params)
    if parameters:
      extra_params.update(parameters)

    if post_data:
      http_method = "POST"
    } else {
      http_method = "GET"

    if $this->_debug_http:
      _debug = 1
    } else {
      _debug = 0

    http_handler  = $this->_urllib.HTTPHandler(debuglevel=_debug)
    https_handler = $this->_urllib.HTTPSHandler(debuglevel=_debug)
    http_proxy = os.environ.get('http_proxy')
    https_proxy = os.environ.get('https_proxy')

    if http_proxy is null or  https_proxy is null :
      proxy_status = false
    else :
      proxy_status = True

    opener = $this->_urllib.OpenerDirector()
    opener.add_handler(http_handler)
    opener.add_handler(https_handler)

    if proxy_status is True :
      proxy_handler = $this->_urllib.ProxyHandler({'http':str(http_proxy),'https': str(https_proxy)})
      opener.add_handler(proxy_handler)

    if use_gzip_compression is null:
      use_gzip = $this->_use_gzip
    } else {
      use_gzip = use_gzip_compression

    # Set up compression
    if use_gzip and not post_data:
      opener.addheaders.append(('Accept-Encoding', 'gzip'))

    if $this->_oauth_consumer is not null:
      if post_data and http_method == "POST":
        parameters = post_data.copy()

      req = oauth.Request.from_consumer_and_token($this->_oauth_consumer,
                                                  token=$this->_oauth_token,
                                                  http_method=http_method,
                                                  http_url=url, parameters=parameters)

      req.sign_request($this->_signature_method_hmac_sha1, $this->_oauth_consumer, $this->_oauth_token)

      headers = req.to_header()

      if http_method == "POST":
        encoded_post_data = req.to_postdata()
      } else {
        encoded_post_data = null
        url = req.to_url()
    } else {
      url = $this->_BuildUrl(url, extra_params=extra_params)
      encoded_post_data = $this->_EncodePostData(post_data)

    # Open and return the URL immediately if we're not going to cache
    if encoded_post_data or no_cache or not $this->_cache or not $this->_cache_timeout:
      response = opener.open(url, encoded_post_data)
      url_data = $this->_DecompressGzippedResponse(response)
      opener.close()
    } else {
      # Unique keys are a combination of the url and the oAuth Consumer Key
      if $this->_consumer_key:
        key = $this->_consumer_key + ':' + url
      } else {
        key = url

      # See if it has been cached before
      last_cached = $this->_cache.GetCachedTime(key)

      # If the cached version is outdated then fetch another and store it
      if not last_cached or time.time() >= last_cached + $this->_cache_timeout:
        try:
          response = opener.open(url, encoded_post_data)
          url_data = $this->_DecompressGzippedResponse(response)
          $this->_cache.Set(key, url_data)
        except urllib2.HTTPError, e:
          print e
        opener.close()
      } else {
        url_data = $this->_cache.Get(key)

    # Always return the latest version
    return url_data
