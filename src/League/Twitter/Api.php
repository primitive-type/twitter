<?php namespace League\Twitter;

class Api
{
    # cache for 1 minute
    public const DEFAULT_CACHE_TIMEOUT = 60;
  
    private const API_REALM = 'Twitter API';

    protected $consumer_key;
    protected $consumer_secret;
    protected $access_token_key;
    protected $access_token_secret;

    protected $oauth_token
    protected $oauth_consumer;

    protected $signature_method_plaintext;
    protected $signature_method_hmac_sha1;

    /**
     * Instantiate a new League\Twitter\Api object
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
        $this->urllib         = urllib2;
        $this->cache_timeout  = static::DEFAULT_CACHE_TIMEOUT;
        $this->input_encoding = $input_encoding;
        $this->use_gzip       = $use_gzip_compression;
        $this->debug_http     = $debug_http;
        $this->oauth_consumer = null;
        $this->shortlink_size = 19;

        $this->initializeRequestHeaders(request_headers);
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
     * Set the consumer_key and consumer_secret for this instance
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
     * @return array An array of League\Twitter\Status instances, one for each message containing the term
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
     * @param string $page Page of results to return. Default is 1
     * @param string $count Number of results to return.  Default is 20
     * @param string $include_entities If True, each tweet will include a node called "entities"
     * @return A sequence of twitter.User instances, one for each message containing the term
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
        $json = $this->_FetchUrl(url, parameters=parameters);
        $data = $this->_ParseAndCheckTwitter($json);
        return [User.NewFromJsonDict(x) for x in $data];
    }

    def GetTrendsCurrent(self, exclude=null):
    '''Get the current top trending topics (global)

    Args:
      exclude:
        Appends the exclude parameter as a request parameter.
        Currently only exclude=hashtags is supported. [Optional]

    Returns:
      A list with 10 entries. Each entry contains a trend.
    '''
    return $this->GetTrendsWoeid(id=1, exclude=exclude)

    def GetTrendsWoeid(self, id, exclude=null):
    '''Return the top 10 trending topics for a specific WOEID, if trending
    information is available for it.

    Args:
      woeid:
        the Yahoo! Where On Earth ID for a location.
      exclude:
        Appends the exclude parameter as a request parameter.
        Currently only exclude=hashtags is supported. [Optional]

    Returns:
      A list with 10 entries. Each entry contains a trend.
    '''
    url  = '%s/trends/place.json' % ($this->base_url)
    parameters = {'id': id}

    if exclude:
      parameters['exclude'] = exclude

    json = $this->_FetchUrl(url, parameters=parameters)
    data = $this->_ParseAndCheckTwitter(json)

    trends = []
    timestamp = data[0]['as_of']

    for trend in data[0]['trends']:
        trends.append(Trend.NewFromJsonDict(trend, timestamp = timestamp))
    return trends

    def GetHomeTimeline(self,
                         count=null,
                         since_id=null,
                         max_id=null,
                         trim_user=false,
                         exclude_replies=false,
                         contributor_details=false,
                         include_entities=True):
    '''
    Fetch a collection of the most recent Tweets and retweets posted by the
    authenticating user and the users they follow.

    The home timeline is central to how most users interact with the Twitter
    service.

    The twitter.Api instance must be authenticated.

    Args:
      count:
        Specifies the number of statuses to retrieve. May not be
        greater than 200. Defaults to 20. [Optional]
      since_id:
        Returns results with an ID greater than (that is, more recent
        than) the specified ID. There are limits to the number of
        Tweets which can be accessed through the API. If the limit of
        Tweets has occurred since the since_id, the since_id will be
        forced to the oldest ID available. [Optional]
      max_id:
        Returns results with an ID less than (that is, older than) or
        equal to the specified ID. [Optional]
      trim_user:
        When True, each tweet returned in a timeline will include a user
        object including only the status authors numerical ID. Omit this
        parameter to receive the complete user object. [Optional]
      exclude_replies:
        This parameter will prevent replies from appearing in the
        returned timeline. Using exclude_replies with the count
        parameter will mean you will receive up-to count tweets -
        this is because the count parameter retrieves that many
        tweets before filtering out retweets and replies.
        [Optional]
      contributor_details:
        This parameter enhances the contributors element of the
        status response to include the screen_name of the contributor.
        By default only the user_id of the contributor is included.
        [Optional]
      include_entities:
        The entities node will be disincluded when set to false.
        This node offers a variety of metadata about the tweet in a
        discreet structure, including: user_mentions, urls, and
        hashtags. [Optional]

    Returns:
      A sequence of twitter.Status instances, one for each message
    '''
    url = '%s/statuses/home_timeline.json' % $this->base_url

    if not $this->_oauth_consumer:
      raise TwitterError("API must be authenticated.")
    parameters = {}
    if count is not null:
      try:
        if int(count) > 200:
          raise TwitterError("'count' may not be greater than 200")
      except ValueError:
        raise TwitterError("'count' must be an integer")
      parameters['count'] = count
    if since_id:
      try:
        parameters['since_id'] = long(since_id)
      except ValueError:
        raise TwitterError("'since_id' must be an integer")
    if max_id:
      try:
        parameters['max_id'] = long(max_id)
      except ValueError:
        raise TwitterError("'max_id' must be an integer")
    if trim_user:
      parameters['trim_user'] = 1
    if exclude_replies:
      parameters['exclude_replies'] = 1
    if contributor_details:
      parameters['contributor_details'] = 1
    if not include_entities:
      parameters['include_entities'] = 'false'
    json = $this->_FetchUrl(url, parameters=parameters)
    data = $this->_ParseAndCheckTwitter(json)
    return [Status.NewFromJsonDict(x) for x in data]

    def GetUserTimeline(self,
                      user_id=null,
                      screen_name=null,
                      since_id=null,
                      max_id=null,
                      count=null,
                      include_rts=null,
                      trim_user=null,
                      exclude_replies=null):
    '''Fetch the sequence of public Status messages for a single user.

    The twitter.Api instance must be authenticated if the user is private.

    Args:
      user_id:
        Specifies the ID of the user for whom to return the
        user_timeline. Helpful for disambiguating when a valid user ID
        is also a valid screen name. [Optional]
      screen_name:
        Specifies the screen name of the user for whom to return the
        user_timeline. Helpful for disambiguating when a valid screen
        name is also a user ID. [Optional]
      since_id:
        Returns results with an ID greater than (that is, more recent
        than) the specified ID. There are limits to the number of
        Tweets which can be accessed through the API. If the limit of
        Tweets has occurred since the since_id, the since_id will be
        forced to the oldest ID available. [Optional]
      max_id:
        Returns only statuses with an ID less than (that is, older
        than) or equal to the specified ID. [Optional]
      count:
        Specifies the number of statuses to retrieve. May not be
        greater than 200.  [Optional]
      include_rts:
        If True, the timeline will contain native retweets (if they
        exist) in addition to the standard stream of tweets. [Optional]
      trim_user:
        If True, statuses will only contain the numerical user ID only.
        Otherwise a full user object will be returned for each status.
        [Optional]
      exclude_replies:
        If True, this will prevent replies from appearing in the returned
        timeline. Using exclude_replies with the count parameter will mean you
        will receive up-to count tweets - this is because the count parameter
        retrieves that many tweets before filtering out retweets and replies.
        This parameter is only supported for JSON and XML responses. [Optional]

    Returns:
      A sequence of Status instances, one for each message up to count
    '''
    parameters = {}

    url = '%s/statuses/user_timeline.json' % ($this->base_url)

    if user_id:
      parameters['user_id'] = user_id
    elif screen_name:
      parameters['screen_name'] = screen_name

    if since_id:
      try:
        parameters['since_id'] = long(since_id)
      except:
        raise TwitterError("since_id must be an integer")

    if max_id:
      try:
        parameters['max_id'] = long(max_id)
      except:
        raise TwitterError("max_id must be an integer")

    if count:
      try:
        parameters['count'] = int(count)
      except:
        raise TwitterError("count must be an integer")

    if include_rts:
      parameters['include_rts'] = 1

    if trim_user:
      parameters['trim_user'] = 1

    if exclude_replies:
      parameters['exclude_replies'] = 1

    json = $this->_FetchUrl(url, parameters=parameters)
    data = $this->_ParseAndCheckTwitter(json)
    return [Status.NewFromJsonDict(x) for x in data]

    def GetStatus(self,
                id,
                trim_user=false,
                include_my_retweet=True,
                include_entities=True):
    '''Returns a single status message, specified by the id parameter.

    The twitter.Api instance must be authenticated.

    Args:
      id:
        The numeric ID of the status you are trying to retrieve.
      trim_user:
        When set to True, each tweet returned in a timeline will include
        a user object including only the status authors numerical ID.
        Omit this parameter to receive the complete user object.
        [Optional]
      include_my_retweet:
        When set to True, any Tweets returned that have been retweeted by
        the authenticating user will include an additional
        current_user_retweet node, containing the ID of the source status
        for the retweet. [Optional]
      include_entities:
        If false, the entities node will be disincluded.
        This node offers a variety of metadata about the tweet in a
        discreet structure, including: user_mentions, urls, and
        hashtags. [Optional]
    Returns:
      A twitter.Status instance representing that status message
    '''
    url  = '%s/statuses/show.json' % ($this->base_url)

    if not $this->_oauth_consumer:
      raise TwitterError("API must be authenticated.")

    parameters = {}

    try:
      parameters['id'] = long(id)
    except ValueError:
      raise TwitterError("'id' must be an integer.")

    if trim_user:
      parameters['trim_user'] = 1
    if include_my_retweet:
      parameters['include_my_retweet'] = 1
    if not include_entities:
      parameters['include_entities'] = 'none'

    json = $this->_FetchUrl(url, parameters=parameters)
    data = $this->_ParseAndCheckTwitter(json)
    return Status.NewFromJsonDict(data)

    def DestroyStatus(self, id, trim_user=false):
    '''Destroys the status specified by the required ID parameter.

    The twitter.Api instance must be authenticated and the
    authenticating user must be the author of the specified status.

    Args:
      id:
        The numerical ID of the status you're trying to destroy.

    Returns:
      A twitter.Status instance representing the destroyed status message
    '''
    if not $this->_oauth_consumer:
      raise TwitterError("API must be authenticated.")

    try:
      post_data = {'id': long(id)}
    except:
      raise TwitterError("id must be an integer")
    url  = '%s/statuses/destroy/%s.json' % ($this->base_url, id)
    if trim_user:
      post_data['trim_user'] = 1
    json = $this->_FetchUrl(url, post_data=post_data)
    data = $this->_ParseAndCheckTwitter(json)
    return Status.NewFromJsonDict(data)

    @classmethod
    def _calculate_status_length(cls, status, linksize=19):
    dummy_link_replacement = 'https://-%d-chars%s/' % (linksize, '-'*(linksize - 18))
    shortened = ' '.join([x if not (x.startswith('http://') or
                                    x.startswith('https://'))
                            else
                                dummy_link_replacement
                            for x in status.split(' ')])
    return len(shortened)

    def PostUpdate(self, status, in_reply_to_status_id=null, latitude=null, longitude=null, place_id=null, display_coordinates=false, trim_user=false):
    '''Post a twitter status message from the authenticated user.

    The twitter.Api instance must be authenticated.

    https://dev.twitter.com/docs/api/1.1/post/statuses/update

    Args:
      status:
        The message text to be posted.
        Must be less than or equal to 140 characters.
      in_reply_to_status_id:
        The ID of an existing status that the status to be posted is
        in reply to.  This implicitly sets the in_reply_to_user_id
        attribute of the resulting status to the user ID of the
        message being replied to.  Invalid/missing status IDs will be
        ignored. [Optional]
      latitude:
        Latitude coordinate of the tweet in degrees. Will only work
        in conjunction with longitude argument. Both longitude and
        latitude will be ignored by twitter if the user has a false
        geo_enabled setting. [Optional]
      longitude:
        Longitude coordinate of the tweet in degrees. Will only work
        in conjunction with latitude argument. Both longitude and
        latitude will be ignored by twitter if the user has a false
        geo_enabled setting. [Optional]
      place_id:
        A place in the world. These IDs can be retrieved from
        GET geo/reverse_geocode. [Optional]
      display_coordinates:
        Whether or not to put a pin on the exact coordinates a tweet
        has been sent from. [Optional]
      trim_user:
        If True the returned payload will only contain the user IDs,
        otherwise the payload will contain the full user data item.
        [Optional]
    Returns:
      A twitter.Status instance representing the message posted.
    '''
    if not $this->_oauth_consumer:
      raise TwitterError("The twitter.Api instance must be authenticated.")

    url = '%s/statuses/update.json' % $this->base_url

    if isinstance(status, unicode) or $this->_input_encoding is null:
      u_status = status
    else:
      u_status = unicode(status, $this->_input_encoding)

    #if $this->_calculate_status_length(u_status, $this->_shortlink_size) > CHARACTER_LIMIT:
    #  raise TwitterError("Text must be less than or equal to %d characters. "
    #                     "Consider using PostUpdates." % CHARACTER_LIMIT)

    data = {'status': status}
    if in_reply_to_status_id:
      data['in_reply_to_status_id'] = in_reply_to_status_id
    if latitude is not null and longitude is not null:
      data['lat']     = str(latitude)
      data['long']    = str(longitude)
    if place_id is not null:
      data['place_id'] = str(place_id)
    if display_coordinates:
      data['display_coordinates'] = 'true'
    if trim_user:
      data['trim_user'] = 'true'
    json = $this->_FetchUrl(url, post_data=data)
    data = $this->_ParseAndCheckTwitter(json)
    return Status.NewFromJsonDict(data)

    def PostUpdates(self, status, continuation=null, **kwargs):
    '''Post one or more twitter status messages from the authenticated user.

    Unlike api.PostUpdate, this method will post multiple status updates
    if the message is longer than 140 characters.

    The twitter.Api instance must be authenticated.

    Args:
      status:
        The message text to be posted.
        May be longer than 140 characters.
      continuation:
        The character string, if any, to be appended to all but the
        last message.  Note that Twitter strips trailing '...' strings
        from messages.  Consider using the unicode \u2026 character
        (horizontal ellipsis) instead. [Defaults to null]
      **kwargs:
        See api.PostUpdate for a list of accepted parameters.

    Returns:
      A of list twitter.Status instance representing the messages posted.
    '''
    results = list()
    if continuation is null:
      continuation = ''
    line_length = CHARACTER_LIMIT - len(continuation)
    lines = textwrap.wrap(status, line_length)
    for line in lines[0:-1]:
      results.append($this->PostUpdate(line + continuation, **kwargs))
    results.append($this->PostUpdate(lines[-1], **kwargs))
    return results

    def PostRetweet(self, original_id, trim_user=false):
    '''Retweet a tweet with the Retweet API.

    The twitter.Api instance must be authenticated.

    Args:
      original_id:
        The numerical id of the tweet that will be retweeted
      trim_user:
        If True the returned payload will only contain the user IDs,
        otherwise the payload will contain the full user data item.
        [Optional]

    Returns:
      A twitter.Status instance representing the original tweet with retweet details embedded.
    '''
    if not $this->_oauth_consumer:
      raise TwitterError("The twitter.Api instance must be authenticated.")

    try:
      if int(original_id) <= 0:
        raise TwitterError("'original_id' must be a positive number")
    except ValueError:
        raise TwitterError("'original_id' must be an integer")

    url = '%s/statuses/retweet/%s.json' % ($this->base_url, original_id)

    data = {'id': original_id}
    if trim_user:
      data['trim_user'] = 'true'
    json = $this->_FetchUrl(url, post_data=data)
    data = $this->_ParseAndCheckTwitter(json)
    return Status.NewFromJsonDict(data)

    def GetUserRetweets(self, count=null, since_id=null, max_id=null, trim_user=false):
    '''Fetch the sequence of retweets made by the authenticated user.

    The twitter.Api instance must be authenticated.

    Args:
      count:
        The number of status messages to retrieve. [Optional]
      since_id:
        Returns results with an ID greater than (that is, more recent
        than) the specified ID. There are limits to the number of
        Tweets which can be accessed through the API. If the limit of
        Tweets has occurred since the since_id, the since_id will be
        forced to the oldest ID available. [Optional]
      max_id:
        Returns results with an ID less than (that is, older than) or
        equal to the specified ID. [Optional]
      trim_user:
        If True the returned payload will only contain the user IDs,
        otherwise the payload will contain the full user data item.
        [Optional]

    Returns:
      A sequence of twitter.Status instances, one for each message up to count
    '''
    return $this->GetUserTimeline(since_id=since_id, count=count, max_id=max_id, trim_user=trim_user, exclude_replies=True, include_rts=True)

    def GetReplies(self, since_id=null, count=null, max_id=null, trim_user=false):
    '''Get a sequence of status messages representing the 20 most
    recent replies (status updates prefixed with @twitterID) to the
    authenticating user.

    Args:
      since_id:
        Returns results with an ID greater than (that is, more recent
        than) the specified ID. There are limits to the number of
        Tweets which can be accessed through the API. If the limit of
        Tweets has occurred since the since_id, the since_id will be
        forced to the oldest ID available. [Optional]
      max_id:
        Returns results with an ID less than (that is, older than) or
        equal to the specified ID. [Optional]
      trim_user:
        If True the returned payload will only contain the user IDs,
        otherwise the payload will contain the full user data item.
        [Optional]

    Returns:
      A sequence of twitter.Status instances, one for each reply to the user.
    '''
    return $this->GetUserTimeline(since_id=since_id, count=count, max_id=max_id, trim_user=trim_user, exclude_replies=false, include_rts=false)

    def GetRetweets(self, statusid, count=null, trim_user=false):
    '''Returns up to 100 of the first retweets of the tweet identified
    by statusid

    Args:
      statusid:
        The ID of the tweet for which retweets should be searched for
      count:
        The number of status messages to retrieve. [Optional]
      trim_user:
        If True the returned payload will only contain the user IDs,
        otherwise the payload will contain the full user data item.
        [Optional]

    Returns:
      A list of twitter.Status instances, which are retweets of statusid
    '''
    if not $this->_oauth_consumer:
      raise TwitterError("The twitter.Api instsance must be authenticated.")
    url = '%s/statuses/retweets/%s.json' % ($this->base_url, statusid)
    parameters = {}
    if trim_user:
      parameters['trim_user'] = 'true'
    if count:
      try:
        parameters['count'] = int(count)
      except:
        raise TwitterError("count must be an integer")
    json = $this->_FetchUrl(url, parameters=parameters)
    data = $this->_ParseAndCheckTwitter(json)
    return [Status.NewFromJsonDict(s) for s in data]

    def GetRetweetsOfMe(self,
                      count=null,
                      since_id=null,
                      max_id=null,
                      trim_user=false,
                      include_entities=True,
                      include_user_entities=True):
    '''Returns up to 100 of the most recent tweets of the user that have been
    retweeted by others.

    Args:
      count:
        The number of retweets to retrieve, up to 100. If omitted, 20 is
        assumed.
      since_id:
        Returns results with an ID greater than (newer than) this ID.
      max_id:
        Returns results with an ID less than or equal to this ID.
      trim_user:
        When True, the user object for each tweet will only be an ID.
      include_entities:
        When True, the tweet entities will be included.
      include_user_entities:
        When True, the user entities will be included.
    '''
    if not $this->_oauth_consumer:
      raise TwitterError("The twitter.Api instance must be authenticated.")
    url = '%s/statuses/retweets_of_me.json' % $this->base_url
    parameters = {}
    if count is not null:
      try:
        if int(count) > 100:
          raise TwitterError("'count' may not be greater than 100")
      except ValueError:
        raise TwitterError("'count' must be an integer")
    if count:
      parameters['count'] = count
    if since_id:
      parameters['since_id'] = since_id
    if max_id:
      parameters['max_id'] = max_id
    if trim_user:
      parameters['trim_user'] = trim_user
    if not include_entities:
      parameters['include_entities'] = include_entities
    if not include_user_entities:
      parameters['include_user_entities'] = include_user_entities
    json = $this->_FetchUrl(url, parameters=parameters)
    data = $this->_ParseAndCheckTwitter(json)
    return [Status.NewFromJsonDict(s) for s in data]

    def GetFriends(self, user_id=null, screen_name=null, cursor=-1, skip_status=false, include_user_entities=false):
    '''Fetch the sequence of twitter.User instances, one for each friend.

    The twitter.Api instance must be authenticated.

    Args:
      user_id:
        The twitter id of the user whose friends you are fetching.
        If not specified, defaults to the authenticated user. [Optional]
      screen_name:
        The twitter name of the user whose friends you are fetching.
        If not specified, defaults to the authenticated user. [Optional]
      cursor:
        Should be set to -1 for the initial call and then is used to
        control what result page Twitter returns [Optional(ish)]
      skip_status:
        If True the statuses will not be returned in the user items.
        [Optional]
      include_user_entities:
        When True, the user entities will be included.

    Returns:
      A sequence of twitter.User instances, one for each friend
    '''
    if not $this->_oauth_consumer:
      raise TwitterError("twitter.Api instance must be authenticated")
    url = '%s/friends/list.json' % $this->base_url
    result = []
    parameters = {}
    if user_id is not null:
      parameters['user_id'] = user_id
    if screen_name is not null:
      parameters['screen_name'] = screen_name
    if skip_status:
      parameters['skip_status'] = True
    if include_user_entities:
      parameters['include_user_entities'] = True
    while True:
      parameters['cursor'] = cursor
      json = $this->_FetchUrl(url, parameters=parameters)
      data = $this->_ParseAndCheckTwitter(json)
      result += [User.NewFromJsonDict(x) for x in data['users']]
      if 'next_cursor' in data:
        if data['next_cursor'] == 0 or data['next_cursor'] == data['previous_cursor']:
          break
        else:
          cursor = data['next_cursor']
      else:
        break
    return result

    def GetFriendIDs(self, user_id=null, screen_name=null, cursor=-1, stringify_ids=false, count=null):
      '''Returns a list of twitter user id's for every person
      the specified user is following.

      Args:
        user_id:
          The id of the user to retrieve the id list for
          [Optional]
        screen_name:
          The screen_name of the user to retrieve the id list for
          [Optional]
        cursor:
          Specifies the Twitter API Cursor location to start at.
          Note: there are pagination limits.
          [Optional]
        stringify_ids:
          if True then twitter will return the ids as strings instead of integers.
          [Optional]
        count:
          The number of status messages to retrieve. [Optional]

      Returns:
        A list of integers, one for each user id.
      '''
      url = '%s/friends/ids.json' % $this->base_url
      if not $this->_oauth_consumer:
          raise TwitterError("twitter.Api instance must be authenticated")
      parameters = {}
      if user_id is not null:
        parameters['user_id'] = user_id
      if screen_name is not null:
        parameters['screen_name'] = screen_name
      if stringify_ids:
        parameters['stringify_ids'] = True
      if count is not null:
        parameters['count'] = count
      result = []
      while True:
        parameters['cursor'] = cursor
        json = $this->_FetchUrl(url, parameters=parameters)
        data = $this->_ParseAndCheckTwitter(json)
        result += [x for x in data['ids']]
        if 'next_cursor' in data:
          if data['next_cursor'] == 0 or data['next_cursor'] == data['previous_cursor']:
            break
          else:
            cursor = data['next_cursor']
        else:
          break
      return result


    def GetFollowerIDs(self, user_id=null, screen_name=null, cursor=-1, stringify_ids=false, count=null):
      '''Returns a list of twitter user id's for every person
      that is following the specified user.

      Args:
        user_id:
          The id of the user to retrieve the id list for
          [Optional]
        screen_name:
          The screen_name of the user to retrieve the id list for
          [Optional]
        cursor:
          Specifies the Twitter API Cursor location to start at.
          Note: there are pagination limits.
          [Optional]
        stringify_ids:
          if True then twitter will return the ids as strings instead of integers.
          [Optional]
        count:
          The number of status messages to retrieve. [Optional]


      Returns:
        A list of integers, one for each user id.
      '''
      url = '%s/followers/ids.json' % $this->base_url
      if not $this->_oauth_consumer:
          raise TwitterError("twitter.Api instance must be authenticated")
      parameters = {}
      if user_id is not null:
        parameters['user_id'] = user_id
      if screen_name is not null:
        parameters['screen_name'] = screen_name
      if stringify_ids:
        parameters['stringify_ids'] = True
      if count is not null:
        parameters['count'] = count
      result = []
      while True:
        parameters['cursor'] = cursor
        json = $this->_FetchUrl(url, parameters=parameters)
        data = $this->_ParseAndCheckTwitter(json)
        result += [x for x in data['ids']]
        if 'next_cursor' in data:
          if data['next_cursor'] == 0 or data['next_cursor'] == data['previous_cursor']:
            break
          else:
            cursor = data['next_cursor']
        else:
          break
      return result

    def GetFollowers(self, user_id=null, screen_name=null, cursor=-1, skip_status=false, include_user_entities=false):
    '''Fetch the sequence of twitter.User instances, one for each follower

    The twitter.Api instance must be authenticated.

    Args:
      user_id:
        The twitter id of the user whose followers you are fetching.
        If not specified, defaults to the authenticated user. [Optional]
      screen_name:
        The twitter name of the user whose followers you are fetching.
        If not specified, defaults to the authenticated user. [Optional]
      cursor:
        Should be set to -1 for the initial call and then is used to
        control what result page Twitter returns [Optional(ish)]
      skip_status:
        If True the statuses will not be returned in the user items.
        [Optional]
      include_user_entities:
        When True, the user entities will be included.

    Returns:
      A sequence of twitter.User instances, one for each follower
    '''
    if not $this->_oauth_consumer:
      raise TwitterError("twitter.Api instance must be authenticated")
    url = '%s/followers/list.json' % $this->base_url
    result = []
    parameters = {}
    if user_id is not null:
      parameters['user_id'] = user_id
    if screen_name is not null:
      parameters['screen_name'] = screen_name
    if skip_status:
      parameters['skip_status'] = True
    if include_user_entities:
      parameters['include_user_entities'] = True
    while True:
      parameters['cursor'] = cursor
      json = $this->_FetchUrl(url, parameters=parameters)
      data = $this->_ParseAndCheckTwitter(json)
      result += [User.NewFromJsonDict(x) for x in data['users']]
      if 'next_cursor' in data:
        if data['next_cursor'] == 0 or data['next_cursor'] == data['previous_cursor']:
          break
        else:
          cursor = data['next_cursor']
      else:
        break
    return result

    def UsersLookup(self, user_id=null, screen_name=null, users=null, include_entities=True):
    '''Fetch extended information for the specified users.

    Users may be specified either as lists of either user_ids,
    screen_names, or twitter.User objects. The list of users that
    are queried is the union of all specified parameters.

    The twitter.Api instance must be authenticated.

    Args:
      user_id:
        A list of user_ids to retrieve extended information.
        [Optional]
      screen_name:
        A list of screen_names to retrieve extended information.
        [Optional]
      users:
        A list of twitter.User objects to retrieve extended information.
        [Optional]
      include_entities:
        The entities node that may appear within embedded statuses will be
        disincluded when set to false.
        [Optional]

    Returns:
      A list of twitter.User objects for the requested users
    '''

    if not $this->_oauth_consumer:
      raise TwitterError("The twitter.Api instance must be authenticated.")
    if not user_id and not screen_name and not users:
      raise TwitterError("Specify at least one of user_id, screen_name, or users.")
    url = '%s/users/lookup.json' % $this->base_url
    parameters = {}
    uids = list()
    if user_id:
      uids.extend(user_id)
    if users:
      uids.extend([u.id for u in users])
    if len(uids):
      parameters['user_id'] = ','.join(["%s" % u for u in uids])
    if screen_name:
      parameters['screen_name'] = ','.join(screen_name)
    if not include_entities:
      parameters['include_entities'] = 'false'
    json = $this->_FetchUrl(url, parameters=parameters)
    try:
      data = $this->_ParseAndCheckTwitter(json)
    except TwitterError as e:
        t = e.args[0]
        if len(t) == 1 and ('code' in t[0]) and (t[0]['code'] == 34):
          data = []
        else:
            raise

    return [User.NewFromJsonDict(u) for u in data]

    def GetUser(self, user_id=null, screen_name=null, include_entities=True):
    '''Returns a single user.

    The twitter.Api instance must be authenticated.

    Args:
      user_id:
        The id of the user to retrieve.
        [Optional]
      screen_name:
        The screen name of the user for whom to return results for. Either a
        user_id or screen_name is required for this method.
        [Optional]
      include_entities:
        if set to false, the 'entities' node will not be included.
        [Optional]


    Returns:
      A twitter.User instance representing that user
    '''
    url  = '%s/users/show.json' % ($this->base_url)
    parameters = {}

    if not $this->_oauth_consumer:
      raise TwitterError("The twitter.Api instance must be authenticated.")

    if user_id:
      parameters['user_id'] = user_id
    elif screen_name:
      parameters['screen_name'] = screen_name
    else:
      raise TwitterError("Specify at least one of user_id or screen_name.")

    if not include_entities:
      parameters['include_entities'] = 'false'

    json = $this->_FetchUrl(url, parameters=parameters)
    data = $this->_ParseAndCheckTwitter(json)
    return User.NewFromJsonDict(data)

    def GetDirectMessages(self, since_id=null, max_id=null, count=null, include_entities=True, skip_status=false):
    '''Returns a list of the direct messages sent to the authenticating user.

    The twitter.Api instance must be authenticated.

    Args:
      since_id:
        Returns results with an ID greater than (that is, more recent
        than) the specified ID. There are limits to the number of
        Tweets which can be accessed through the API. If the limit of
        Tweets has occurred since the since_id, the since_id will be
        forced to the oldest ID available. [Optional]
      max_id:
        Returns results with an ID less than (that is, older than) or
        equal to the specified ID. [Optional]
      count:
        Specifies the number of direct messages to try and retrieve, up to a
        maximum of 200. The value of count is best thought of as a limit to the
        number of Tweets to return because suspended or deleted content is
        removed after the count has been applied. [Optional]
      include_entities:
        The entities node will not be included when set to false.
        [Optional]
      skip_status:
        When set to True statuses will not be included in the returned user
        objects. [Optional]

    Returns:
      A sequence of twitter.DirectMessage instances
    '''
    url = '%s/direct_messages.json' % $this->base_url
    if not $this->_oauth_consumer:
      raise TwitterError("The twitter.Api instance must be authenticated.")
    parameters = {}
    if since_id:
      parameters['since_id'] = since_id
    if max_id:
      parameters['max_id'] = max_id
    if count:
      try:
        parameters['count'] = int(count)
      except:
        raise TwitterError("count must be an integer")
    if not include_entities:
      parameters['include_entities'] = 'false'
    if skip_status:
      parameters['skip_status'] = 1
    json = $this->_FetchUrl(url, parameters=parameters)
    data = $this->_ParseAndCheckTwitter(json)
    return [DirectMessage.NewFromJsonDict(x) for x in data]

    def GetSentDirectMessages(self, since_id=null, max_id=null, count=null, page=null, include_entities=True):
    '''Returns a list of the direct messages sent by the authenticating user.

    The twitter.Api instance must be authenticated.

    Args:
      since_id:
        Returns results with an ID greater than (that is, more recent
        than) the specified ID. There are limits to the number of
        Tweets which can be accessed through the API. If the limit of
        Tweets has occured since the since_id, the since_id will be
        forced to the oldest ID available. [Optional]
      max_id:
        Returns results with an ID less than (that is, older than) or
        equal to the specified ID. [Optional]
      count:
        Specifies the number of direct messages to try and retrieve, up to a
        maximum of 200. The value of count is best thought of as a limit to the
        number of Tweets to return because suspended or deleted content is
        removed after the count has been applied. [Optional]
      page:
        Specifies the page of results to retrieve.
        Note: there are pagination limits. [Optional]
      include_entities:
        The entities node will not be included when set to false.
        [Optional]

    Returns:
      A sequence of twitter.DirectMessage instances
    '''
    url = '%s/direct_messages/sent.json' % $this->base_url
    if not $this->_oauth_consumer:
      raise TwitterError("The twitter.Api instance must be authenticated.")
    parameters = {}
    if since_id:
      parameters['since_id'] = since_id
    if page:
      parameters['page'] = page
    if max_id:
      parameters['max_id'] = max_id
    if count:
      try:
        parameters['count'] = int(count)
      except:
        raise TwitterError("count must be an integer")
    if not include_entities:
      parameters['include_entities'] = 'false'
    json = $this->_FetchUrl(url, parameters=parameters)
    data = $this->_ParseAndCheckTwitter(json)
    return [DirectMessage.NewFromJsonDict(x) for x in data]

    def PostDirectMessage(self, text, user_id=null, screen_name=null):
    '''Post a twitter direct message from the authenticated user

    The twitter.Api instance must be authenticated.

    Args:
      text: The message text to be posted.  Must be less than 140 characters.
      user_id:
        A list of user_ids to retrieve extended information.
        [Optional]
      screen_name:
        A list of screen_names to retrieve extended information.
        [Optional]

    Returns:
      A twitter.DirectMessage instance representing the message posted
    '''
    if not $this->_oauth_consumer:
      raise TwitterError("The twitter.Api instance must be authenticated.")
    url  = '%s/direct_messages/new.json' % $this->base_url
    data = {'text': text}
    if user_id:
      data['user_id'] = user_id
    elif screen_name:
      data['screen_name'] = screen_name
    else:
      raise TwitterError("Specify at least one of user_id or screen_name.")
    json = $this->_FetchUrl(url, post_data=data)
    data = $this->_ParseAndCheckTwitter(json)
    return DirectMessage.NewFromJsonDict(data)

    def DestroyDirectMessage(self, id, include_entities=True):
    '''Destroys the direct message specified in the required ID parameter.

    The twitter.Api instance must be authenticated, and the
    authenticating user must be the recipient of the specified direct
    message.

    Args:
      id: The id of the direct message to be destroyed

    Returns:
      A twitter.DirectMessage instance representing the message destroyed
    '''
    url  = '%s/direct_messages/destroy.json' % $this->base_url
    data = {'id': id}
    if not include_entities:
      data['include_entities'] = 'false'
    json = $this->_FetchUrl(url, post_data=data)
    data = $this->_ParseAndCheckTwitter(json)
    return DirectMessage.NewFromJsonDict(data)

    def CreateFriendship(self, user_id=null, screen_name=null, follow=True):
    '''Befriends the user specified by the user_id or screen_name.

    The twitter.Api instance must be authenticated.

    Args:
      user_id:
        A user_id to follow [Optional]
      screen_name:
        A screen_name to follow [Optional]
      follow:
        Set to false to disable notifications for the target user
    Returns:
      A twitter.User instance representing the befriended user.
    '''
    url  = '%s/friendships/create.json' % ($this->base_url)
    data = {}
    if user_id:
      data['user_id'] = user_id
    elif screen_name:
      data['screen_name'] = screen_name
    else:
      raise TwitterError("Specify at least one of user_id or screen_name.")
    if follow:
      data['follow'] = 'true'
    else:
      data['follow'] = 'false'
    json = $this->_FetchUrl(url, post_data=data)
    data = $this->_ParseAndCheckTwitter(json)
    return User.NewFromJsonDict(data)

    def DestroyFriendship(self, user_id=null, screen_name=null):
    '''Discontinues friendship with a user_id or screen_name.

    The twitter.Api instance must be authenticated.

    Args:
      user_id:
        A user_id to unfollow [Optional]
      screen_name:
        A screen_name to unfollow [Optional]
    Returns:
      A twitter.User instance representing the discontinued friend.
    '''
    url  = '%s/friendships/destroy.json' % $this->base_url
    data = {}
    if user_id:
      data['user_id'] = user_id
    elif screen_name:
      data['screen_name'] = screen_name
    else:
      raise TwitterError("Specify at least one of user_id or screen_name.")
    json = $this->_FetchUrl(url, post_data=data)
    data = $this->_ParseAndCheckTwitter(json)
    return User.NewFromJsonDict(data)

    def CreateFavorite(self, status=null, id=null, include_entities=True):
    '''Favorites the specified status object or id as the authenticating user.
    Returns the favorite status when successful.

    The twitter.Api instance must be authenticated.

    Args:
      id:
        The id of the twitter status to mark as a favorite.
        [Optional]
      status:
        The twitter.Status object to mark as a favorite.
        [Optional]
      include_entities:
        The entities node will be omitted when set to false.
    Returns:
      A twitter.Status instance representing the newly-marked favorite.
    '''
    url  = '%s/favorites/create.json' % $this->base_url
    data = {}
    if id:
      data['id'] = id
    elif status:
      data['id'] = status.id
    else:
      raise TwitterError("Specify id or status")
    if not include_entities:
      data['include_entities'] = 'false'
    json = $this->_FetchUrl(url, post_data=data)
    data = $this->_ParseAndCheckTwitter(json)
    return Status.NewFromJsonDict(data)

    def DestroyFavorite(self, status=null, id=null, include_entities=True):
    '''Un-Favorites the specified status object or id as the authenticating user.
    Returns the un-favorited status when successful.

    The twitter.Api instance must be authenticated.

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
    url  = '%s/favorites/destroy.json' % $this->base_url
    data = {}
    if id:
      data['id'] = id
    elif status:
      data['id'] = status.id
    else:
      raise TwitterError("Specify id or status")
    if not include_entities:
      data['include_entities'] = 'false'
    json = $this->_FetchUrl(url, post_data=data)
    data = $this->_ParseAndCheckTwitter(json)
    return Status.NewFromJsonDict(data)

    def GetFavorites(self,
                   user_id=null,
                   screen_name=null,
                   count=null,
                   since_id=null,
                   max_id=null,
                   include_entities=True):
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
    parameters = {}

    url = '%s/favorites/list.json' % $this->base_url

    if user_id:
      parameters['user_id'] = user_id
    elif screen_name:
      parameters['screen_name'] = user_id

    if since_id:
      try:
        parameters['since_id'] = long(since_id)
      except:
        raise TwitterError("since_id must be an integer")

    if max_id:
      try:
        parameters['max_id'] = long(max_id)
      except:
        raise TwitterError("max_id must be an integer")

    if count:
      try:
        parameters['count'] = int(count)
      except:
        raise TwitterError("count must be an integer")

    if include_entities:
        parameters['include_entities'] = True


    json = $this->_FetchUrl(url, parameters=parameters)
    data = $this->_ParseAndCheckTwitter(json)
    return [Status.NewFromJsonDict(x) for x in data]

    def GetMentions(self,
                  count=null,
                  since_id=null,
                  max_id=null,
                  trim_user=false,
                  contributor_details=false,
                  include_entities=True):
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

    url = '%s/statuses/mentions_timeline.json' % $this->base_url

    if not $this->_oauth_consumer:
      raise TwitterError("The twitter.Api instance must be authenticated.")

    parameters = {}

    if count:
      try:
        parameters['count'] = int(count)
      except:
        raise TwitterError("count must be an integer")
    if since_id:
      try:
        parameters['since_id'] = long(since_id)
      except:
        raise TwitterError("since_id must be an integer")
    if max_id:
      try:
        parameters['max_id'] = long(max_id)
      except:
        raise TwitterError("max_id must be an integer")
    if trim_user:
      parameters['trim_user'] = 1
    if contributor_details:
      parameters['contributor_details'] = 'true'
    if not include_entities:
      parameters['include_entities'] = 'false'

    json = $this->_FetchUrl(url, parameters=parameters)
    data = $this->_ParseAndCheckTwitter(json)
    return [Status.NewFromJsonDict(x) for x in data]

    def CreateList(self, name, mode=null, description=null):
    '''Creates a new list with the give name for the authenticated user.

    The twitter.Api instance must be authenticated.

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
    url = '%s/lists/create.json' % $this->base_url

    if not $this->_oauth_consumer:
      raise TwitterError("The twitter.Api instance must be authenticated.")
    parameters = {'name': name}
    if mode is not null:
      parameters['mode'] = mode
    if description is not null:
      parameters['description'] = description
    json = $this->_FetchUrl(url, post_data=parameters)
    data = $this->_ParseAndCheckTwitter(json)
    return List.NewFromJsonDict(data)

    def DestroyList(self,
                  owner_screen_name=false,
                  owner_id=false,
                  list_id=null,
                  slug=null):
    '''
    Destroys the list identified by list_id or owner_screen_name/owner_id and
    slug.

    The twitter.Api instance must be authenticated.

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
    url  = '%s/lists/destroy.json' % $this->base_url
    data = {}
    if list_id:
      try:
        data['list_id']= long(list_id)
      except:
        raise TwitterError("list_id must be an integer")
    elif slug:
      data['slug'] = slug
      if owner_id:
        try:
          data['owner_id'] = long(owner_id)
        except:
          raise TwitterError("owner_id must be an integer")
      elif owner_screen_name:
        data['owner_screen_name'] = owner_screen_name
      else:
        raise TwitterError("Identify list by list_id or owner_screen_name/owner_id and slug")
    else:
      raise TwitterError("Identify list by list_id or owner_screen_name/owner_id and slug")

    json = $this->_FetchUrl(url, post_data=data)
    data = $this->_ParseAndCheckTwitter(json)
    return List.NewFromJsonDict(data)

    def CreateSubscription(self,
                  owner_screen_name=false,
                  owner_id=false,
                  list_id=null,
                  slug=null):
    '''Creates a subscription to a list by the authenticated user

    The twitter.Api instance must be authenticated.

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
    url  = '%s/lists/subscribers/create.json' % ($this->base_url)
    if not $this->_oauth_consumer:
      raise TwitterError("The twitter.Api instance must be authenticated.")
    data = {}
    if list_id:
      try:
        data['list_id']= long(list_id)
      except:
        raise TwitterError("list_id must be an integer")
    elif slug:
      data['slug'] = slug
      if owner_id:
        try:
          data['owner_id'] = long(owner_id)
        except:
          raise TwitterError("owner_id must be an integer")
      elif owner_screen_name:
        data['owner_screen_name'] = owner_screen_name
      else:
        raise TwitterError("Identify list by list_id or owner_screen_name/owner_id and slug")
    else:
      raise TwitterError("Identify list by list_id or owner_screen_name/owner_id and slug")
    json = $this->_FetchUrl(url, post_data=data)
    data = $this->_ParseAndCheckTwitter(json)
    return List.NewFromJsonDict(data)

    def DestroySubscription(self,
                  owner_screen_name=false,
                  owner_id=false,
                  list_id=null,
                  slug=null):
    '''Destroys the subscription to a list for the authenticated user

    The twitter.Api instance must be authenticated.

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
    url  = '%s/lists/subscribers/destroy.json' % ($this->base_url)
    if not $this->_oauth_consumer:
      raise TwitterError("The twitter.Api instance must be authenticated.")
    data = {}
    if list_id:
      try:
        data['list_id']= long(list_id)
      except:
        raise TwitterError("list_id must be an integer")
    elif slug:
      data['slug'] = slug
      if owner_id:
        try:
          data['owner_id'] = long(owner_id)
        except:
          raise TwitterError("owner_id must be an integer")
      elif owner_screen_name:
        data['owner_screen_name'] = owner_screen_name
      else:
        raise TwitterError("Identify list by list_id or owner_screen_name/owner_id and slug")
    else:
      raise TwitterError("Identify list by list_id or owner_screen_name/owner_id and slug")
    json = $this->_FetchUrl(url, post_data=data)
    data = $this->_ParseAndCheckTwitter(json)
    return List.NewFromJsonDict(data)

    def GetSubscriptions(self, user_id=null, screen_name=null, count=20, cursor=-1):
    '''
    Obtain a collection of the lists the specified user is subscribed to, 20
    lists per page by default. Does not include the user's own lists.

    The twitter.Api instance must be authenticated.

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
    if not $this->_oauth_consumer:
      raise TwitterError("twitter.Api instance must be authenticated")

    url = '%s/lists/subscriptions.json' % ($this->base_url)
    parameters = {}

    try:
      parameters['cursor'] = int(cursor)
    except:
      raise TwitterError("cursor must be an integer")

    try:
      parameters['count'] = int(count)
    except:
      raise TwitterError("count must be an integer")

    if user_id is not null:
      try:
        parameters['user_id'] = long(user_id)
      except:
        raise TwitterError('user_id must be an integer')
    elif screen_name is not null:
      parameters['screen_name'] = screen_name
    else:
      raise TwitterError('Specify user_id or screen_name')

    json = $this->_FetchUrl(url, parameters=parameters)
    data = $this->_ParseAndCheckTwitter(json)
    return [List.NewFromJsonDict(x) for x in data['lists']]

    def GetLists(self, user_id=null, screen_name=null, count=null, cursor=-1):
    '''Fetch the sequence of lists for a user.

    The twitter.Api instance must be authenticated.

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
    if not $this->_oauth_consumer:
      raise TwitterError("twitter.Api instance must be authenticated")

    url = '%s/lists/ownerships.json' % $this->base_url
    result = []
    parameters = {}
    if user_id is not null:
      try:
        parameters['user_id'] = long(user_id)
      except:
        raise TwitterError('user_id must be an integer')
    elif screen_name is not null:
      parameters['screen_name'] = screen_name
    else:
      raise TwitterError('Specify user_id or screen_name')
    if count is not null:
      parameters['count'] = count

    while True:
      parameters['cursor'] = cursor
      json = $this->_FetchUrl(url, parameters=parameters)
      data = $this->_ParseAndCheckTwitter(json)
      result += [List.NewFromJsonDict(x) for x in data['lists']]
      if 'next_cursor' in data:
        if data['next_cursor'] == 0 or data['next_cursor'] == data['previous_cursor']:
          break
        else:
          cursor = data['next_cursor']
      else:
        break
    return result

    def VerifyCredentials(self):
    '''Returns a twitter.User instance if the authenticating user is valid.

    Returns:
      A twitter.User instance representing that user if the
      credentials are valid, null otherwise.
    '''
    if not $this->_oauth_consumer:
      raise TwitterError("Api instance must first be given user credentials.")
    url = '%s/account/verify_credentials.json' % $this->base_url
    try:
      json = $this->_FetchUrl(url, no_cache=True)
    except urllib2.HTTPError, http_error:
      if http_error.code == httplib.UNAUTHORIZED:
        return null
      else:
        raise http_error
    data = $this->_ParseAndCheckTwitter(json)
    return User.NewFromJsonDict(data)

    def SetCache(self, cache):
    '''Override the default cache.  Set to null to prevent caching.

    Args:
      cache:
        An instance that supports the same API as the twitter._FileCache
    '''
    if cache == DEFAULT_CACHE:
      $this->_cache = _FileCache()
    else:
      $this->_cache = cache

    def SetUrllib(self, urllib):
    '''Override the default urllib implementation.

    Args:
      urllib:
        An instance that supports the same API as the urllib2 module
    '''
    $this->_urllib = urllib

    def SetCacheTimeout(self, cache_timeout):
    '''Override the default cache timeout.

    Args:
      cache_timeout:
        Time, in seconds, that responses should be reused.
    '''
    $this->_cache_timeout = cache_timeout

    def SetUserAgent(self, user_agent):
    '''Override the default user agent

    Args:
      user_agent:
        A string that should be send to the server as the User-agent
    '''
    $this->_request_headers['User-Agent'] = user_agent

    def SetXTwitterHeaders(self, client, url, version):
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

    def SetSource(self, source):
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

    def GetRateLimitStatus(self, resources=null):
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
    parameters = {}
    if resources is not null:
      parameters['resources'] = resources

    url  = '%s/application/rate_limit_status.json' % $this->base_url
    json = $this->_FetchUrl(url, parameters=parameters, no_cache=True)
    data = $this->_ParseAndCheckTwitter(json)
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

    def _BuildUrl(self, url, path_elements=null, extra_params=null):
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
      else:
        query = extra_query

    # Return the rebuilt URL
    return urlparse.urlunparse((scheme, netloc, path, params, query, fragment))

    def _InitializeRequestHeaders(self, request_headers):
    if request_headers:
      $this->_request_headers = request_headers
    else:
      $this->_request_headers = {}

    def _InitializeUserAgent(self):
    user_agent = 'Python-urllib/%s (python-twitter/%s)' % \
                 ($this->_urllib.__version__, __version__)
    $this->SetUserAgent(user_agent)

    def _InitializeDefaultParameters(self):
    $this->_default_params = {}

    def _DecompressGzippedResponse(self, response):
    raw_data = response.read()
    if response.headers.get('content-encoding', null) == 'gzip':
      url_data = gzip.GzipFile(fileobj=StringIO.StringIO(raw_data)).read()
    else:
      url_data = raw_data
    return url_data

    def _Encode(self, s):
    if $this->_input_encoding:
      return unicode(s, $this->_input_encoding).encode('utf-8')
    else:
      return unicode(s).encode('utf-8')

    def _EncodeParameters(self, parameters):
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
    else:
      return urllib.urlencode(dict([(k, $this->_Encode(v)) for k, v in parameters.items() if v is not null]))

    def _EncodePostData(self, post_data):
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
    else:
      return urllib.urlencode(dict([(k, $this->_Encode(v)) for k, v in post_data.items()]))

    def _ParseAndCheckTwitter(self, json):
    """Try and parse the JSON returned from Twitter and return
    an empty dictionary if there is any error. This is a purely
    defensive check because during some Twitter network outages
    it will return an HTML failwhale page."""
    try:
      data = simplejson.loads(json)
      $this->_CheckForTwitterError(data)
    except ValueError:
      if "<title>Twitter / Over capacity</title>" in json:
        raise TwitterError("Capacity Error")
      if "<title>Twitter / Error</title>" in json:
        raise TwitterError("Technical Error")
      raise TwitterError("json decoding")

    return data

    def _CheckForTwitterError(self, data):
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
      raise TwitterError(data['error'])
    if 'errors' in data:
      raise TwitterError(data['errors'])

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
    else:
      http_method = "GET"

    if $this->_debug_http:
      _debug = 1
    else:
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
    else:
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
      else:
        encoded_post_data = null
        url = req.to_url()
    else:
      url = $this->_BuildUrl(url, extra_params=extra_params)
      encoded_post_data = $this->_EncodePostData(post_data)

    # Open and return the URL immediately if we're not going to cache
    if encoded_post_data or no_cache or not $this->_cache or not $this->_cache_timeout:
      response = opener.open(url, encoded_post_data)
      url_data = $this->_DecompressGzippedResponse(response)
      opener.close()
    else:
      # Unique keys are a combination of the url and the oAuth Consumer Key
      if $this->_consumer_key:
        key = $this->_consumer_key + ':' + url
      else:
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
      else:
        url_data = $this->_cache.Get(key)

    # Always return the latest version
    return url_data
