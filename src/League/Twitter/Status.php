<?php 
namespace League\Twitter;

/**
 * A class representing the Status structure used by the twitter API.
 */
class Status
{
    protected $created_at;
    protected $created_at_in_seconds;
    protected $favorited;
    protected $favorite_count;
    protected $in_reply_to_screen_name;
    protected $in_reply_to_user_id;
    protected $in_reply_to_status_id;
    protected $truncated;
    protected $source;
    protected $id;
    protected $text;
    protected $location;
    protected $relative_created_at # read only;
    protected $user;
    protected $urls;
    protected $user_mentions;
    protected $hashtags;
    protected $geo;
    protected $place;
    protected $coordinates;
    protected $contributors;

    /**
     * Constructor - An object to hold a Twitter status message.
     * @param Array $data An array of all the status properties
     */
    public function __construct(Array $data)
    {
        $this->created_at = isset($data['created_at']) ? $data['created_at'] : null;
        $this->favorited = isset($data['favorited']) ? $data['favorited'] : null;
        $this->favorite_count = isset($data['favorite_count']) ? $data['favorite_count'] : null;
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->text = isset($data['text']) ? $data['text'] : null;
        $this->location = isset($data['location']) ? $data['location'] : null;
        $this->user = isset($data['user']) ? $data['user'] : null;
        $this->now = isset($data['now']) ? $data['now'] : null;
        $this->in_reply_to_screen_name = isset($data['in_reply_to_screen_name']) ? $data['in_reply_to_screen_name'] : null;
        $this->in_reply_to_user_id = isset($data['in_reply_to_user_id']) ? $data['in_reply_to_user_id'] : null;
        $this->in_reply_to_status_id = isset($data['in_reply_to_status_id']) ? $data['in_reply_to_status_id'] : null;
        $this->truncated = isset($data['truncated']) ? $data['truncated'] : null;
        $this->retweeted = isset($data['retweeted']) ? $data['retweeted'] : null;
        $this->source = isset($data['source']) ? $data['source'] : null;
        $this->urls = isset($data['urls']) ? $data['urls'] : null;
        $this->user_mentions = isset($data['user_mentions']) ? $data['user_mentions'] : null;
        $this->hashtags = isset($data['hashtags']) ? $data['hashtags'] : null;
        $this->media = isset($data['media']) ? $data['media'] : null;
        $this->geo = isset($data['geo']) ? $data['geo'] : null;
        $this->place = isset($data['place']) ? $data['place'] : null;
        $this->coordinates = isset($data['coordinates']) ? $data['coordinates'] : null;
        $this->contributors = isset($data['contributors']) ? $data['contributors'] : null;
        $this->retweeted_status = isset($data['retweeted_status']) ? $data['retweeted_status'] : null;
        $this->current_user_retweet = isset($data['current_user_retweet']) ? $data['current_user_retweet'] : null;
        $this->retweet_count = isset($data['retweet_count']) ? $data['retweet_count'] : null;
        $this->possibly_sensitive = isset($data['possibly_sensitive']) ? $data['possibly_sensitive'] : null;
        $this->scopes = isset($data['scopes']) ? $data['scopes'] : null;
        $this->withheld_copyright = isset($data['withheld_copyright']) ? $data['withheld_copyright'] : null;
        $this->withheld_in_countries = isset($data['withheld_in_countries']) ? $data['withheld_in_countries'] : null;
        $this->withheld_scope = isset($data['withheld_scope']) ? $data['withheld_scope'] : null;
    }
    
    /**
     * Get the time this status message was posted.
     * @return $get_created_at 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set the time this status message was posted
     * @param string $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * Get the time this status message was posted, in seconds since the epoch
     * @return int 
     */
    public function getCreatedAtInSeconds()
    {
        return strtotime($this->created_at);
    }

    /**
     * Get the favorited setting of this status message. 
     * @return boolean
     */
    public function getFavorited()
    {
        return $this->favorited;
    }

    /**
     * Set the favorited state of this status message. 
     * @param boolean $favorited
     */
    public function setFavorited($favorited)
    {
        $this->favorited = $favorited;
    }

    /**
     * Get the favorite count of this status message.
     * @return int
     */
    public function getFavoriteCount()
    {
        return $this->favorite_count;
    }

    /**
     * Set the favorited state of this status message.
     * @param int $favorite_count
     */
    public function setFavoriteCount($favorite_count)
    {
        $this->favorite_count = $favorite_count;
    }

    /**
     * Get the unique id of this status message.
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the unique id of this status message.
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the screen name of the status that the current status is in reply to
     * @return string
     */
    public function getInReplyToScreenName()
    {
        return $this->in_reply_to_screen_name;
    }

    /**
     * Set the screen name of the status this status is in reply to
     * @param string $in_reply_to_screen_name
     */
    public function setInReplyToScreenName($in_reply_to_screen_name)
    {
        $this->in_reply_to_screen_name = in_reply_to_screen_name;
    }

    public function getInReplyToUserId()
    {
        return $this->in_reply_to_user_id;
    }

    public function setInReplyToUserId($in_reply_to_user_id)
    {
        $this->in_reply_to_user_id = in_reply_to_user_id;
    }

    /**
     * Get the id of the status that this status is in reply to
     * @return int
     */
    public function getInReplyToStatusId()
    {
        return $this->in_reply_to_status_id;
    }

    /**
     * Set the id of the status that this status is in reply to
     * @param int $in_reply_to_status_id
     */
    public function setInReplyToStatusId($in_reply_to_status_id)
    {
        $this->in_reply_to_status_id = in_reply_to_status_id;
    }

    /**
     * Return a truncated version of the status
     * @return string
     */
    public function getTruncated()
    {
        return $this->truncated;
    }

    /**
     * Set the truncated version of the status
     * @param string $truncated
     */
    public function setTruncated($truncated)
    {
        $this->truncated = $truncated;
    }

    /**
     * Get the retweeted state of this status
     * @return boolean
     */
    public function getRetweeted()
    {
        return $this->retweeted;
    }

    /**
     * Set the retweeted staet of this status
     * @param boolean $retweeted
     */
    public function setRetweeted($retweeted)
    {
        $this->retweeted = $retweeted;
    }

    /**
     * Get the source of this tweet
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set the source of this tweet
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * Get the text of this status message.
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set the text of this status message.
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Get the geolocation associated with this status message
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set the geolocation associated with this status message
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * Get a human readable string representing the posting time
     * @return string
     */
    public function getRelativeCreatedAt()
    {
        $fudge = 1.25;
        $delta  = $this->now - $this->created_at_in_seconds;

        if($delta < (1 * $fudge)) {
            return 'about a second ago';
        } elseif ($delta < (60 * (1/$fudge))) {
            return sprintf('about %d seconds ago', $delta); 
        } elseif ($delta < (60 * $fudge)) {
            return 'about a minute ago';
        } elseif ($delta < (60 * 60 * (1/$fudge))) {
            return sprintf('about %d minutes ago', ($delta / 60));
        } elseif ($delta < (60 * 60 * $fudge) || $delta / (60 * 60) == 1) {
            return 'about an hour ago';
        } elseif ($delta < (60 * 60 * 24 * (1/$fudge))) {
	    return sprintf('about %d hours ago', ($delta / (60 * 60));
        } elseif ($delta < (60 * 60 * 24 * $fudge) || $delta / (60 * 60 * 24) == 1) {
            return 'about a day ago'
        } else {
            return sprintf('about %d days ago', ($delta / (60 * 60 * 24));
	}
    }

    /**
     * Get a twitter.User representing the entity posting this status message.
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set a twitter.User representing the entity posting this status message.
     * @param string $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Get the wallclock time for this status message.
     * @return int
     */
    public function getNow()
    {
        if($this->now === null) {
            $this->now = time();
	}
        return $this->now;
    }

    /**
     * Set the wallclock time for this status message.
     * @param int $now
     */
    public function setNow($now)
    {
        $this->now = $now;
    }

    /**
     * Get the geo string for this status
     * @return string
     */
    public function getGeo()
    {
        return $this->geo;
    }

    /**
     * Get the geo string for this status
     * @param string $geo
     */
    public function setGeo($geo)
    {
        $this->geo = $geo;
    }

    public function getPlace()
    {
        return $this->place;
    }

    public function setPlace($place)
    {
        $this->place = $place;
    }

    public function getCoordinates()
    {
        return $this->coordinates;
    }

    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;
    }

    public function getContributors()
    {
        return $this->contributors;
    }

    public function setContributors($contributors)
    {
        $this->contributors = $contributors;
    }

    public function getRetweeted_status()
    {
        return $this->retweeted_status;
    }

    public function setRetweeted_status($retweeted_status)
    {
        $this->retweeted_status = $retweeted_status;
    }

    public function getRetweetCount()
    {
        return $this->retweet_count;
    }

    public function setRetweetCount($retweet_count)
    {
        $this->retweet_count = $retweet_count;
    }

    public function getCurrent_user_retweet()
    {
        return $this->current_user_retweet;
    }

    public function setCurrent_user_retweet($current_user_retweet)
    {
        $this->current_user_retweet = $current_user_retweet;
    }

    public function getPossibly_sensitive()
    {
        return $this->possibly_sensitive;
    }

    public function setPossibly_sensitive($possibly_sensitive)
    {
        $this->possibly_sensitive = $possibly_sensitive;
    }

    public function getScopes()
    {
        return $this->scopes;
    }

    public function setScopes($scopes)
    {
        $this->scopes = $scopes;
    }

    public function getWithheld_copyright()
    {
        return $this->withheld_copyright;
    }

    public function setWithheld_copyright($withheld_copyright)
    {
        $this->withheld_copyright = $withheld_copyright;
    }

    public function getWithheld_in_countries()
    {
        return $this->withheld_in_countries;
    }

    public function setWithheld_in_countries($withheld_in_countries)
    {
        $this->withheld_in_countries = $withheld_in_countries;
    }

    public function getWithheld_scope()
    {
        return $this->withheld_scope;
    }

    public function setWithheld_scope($withheld_scope)
    {
        $this->withheld_scope = $withheld_scope;
    }

    public function __toString()
    {
    '''A string representation of this twitter.Status instance.

    The return value is the same as the JSON string representation.

        returns:
      A string representation of this twitter.Status instance.
    '''
        return $this->AsJsonString();
    }

    public function AsJsonString($this->:
    '''A JSON string representation of this twitter.Status instance.

        returns:
      A JSON string representation of this twitter.Status instance
   '''
        return simplejson.dumps($this->AsDict(), sort_keys=True);
    }

    public function AsDict()
    '''A dict representation of this twitter.Status instance.

    The return value uses the same key names as the JSON representation.

        return:
      A dict representing this twitter.Status instance
    '''
    data = {}
    if $this->created_at:
      data['created_at'] = $this->created_at
    if $this->favorited:
      data['favorited'] = $this->favorited
    if $this->favorite_count:
      data['favorite_count'] = $this->favorite_count
    if $this->id:
      data['id'] = $this->id
    if $this->text:
      data['text'] = $this->text
    if $this->location:
      data['location'] = $this->location
    if $this->user:
      data['user'] = $this->user.AsDict()
    if $this->in_reply_to_screen_name:
      data['in_reply_to_screen_name'] = $this->in_reply_to_screen_name
    if $this->in_reply_to_user_id:
      data['in_reply_to_user_id'] = $this->in_reply_to_user_id
    if $this->in_reply_to_status_id:
      data['in_reply_to_status_id'] = $this->in_reply_to_status_id
    if $this->truncated is not None:
      data['truncated'] = $this->truncated
    if $this->retweeted is not None:
      data['retweeted'] = $this->retweeted
    if $this->favorited is not None:
      data['favorited'] = $this->favorited
    if $this->source:
      data['source'] = $this->source
    if $this->geo:
      data['geo'] = $this->geo
    if $this->place:
      data['place'] = $this->place
    if $this->coordinates:
      data['coordinates'] = $this->coordinates
    if $this->contributors:
      data['contributors'] = $this->contributors
    if $this->hashtags:
      data['hashtags'] = [h.text for h in $this->hashtags]
    if $this->retweeted_status:
      data['retweeted_status'] = $this->retweeted_status.AsDict()
    if $this->retweet_count:
      data['retweet_count'] = $this->retweet_count
    if $this->urls:
      data['urls'] = dict([(url.url, url.expanded_url) for url in $this->urls])
    if $this->user_mentions:
      data['user_mentions'] = [um.AsDict() for um in $this->user_mentions]
    if $this->current_user_retweet:
      data['current_user_retweet'] = $this->current_user_retweet
    if $this->possibly_sensitive:
      data['possibly_sensitive'] = $this->possibly_sensitive
    if $this->scopes:
      data['scopes'] = $this->scopes
    if $this->withheld_copyright:
      data['withheld_copyright'] = $this->withheld_copyright
    if $this->withheld_in_countries:
      data['withheld_in_countries'] = $this->withheld_in_countries
    if $this->withheld_scope:
      data['withheld_scope'] = $this->withheld_scope
        return data

  @staticmethod
    }

    public function NewFromJsonDict(data)
    {
    '''Create a new instance based on a JSON dict.

    Args:
      data: A JSON dict, as converted from the JSON in the twitter API
        returns:
      A twitter.Status instance
    '''
    if 'user' in data:
      user = User.NewFromJsonDict(data['user'])
    else:
      user = None
    if 'retweeted_status' in data:
      retweeted_status = Status.NewFromJsonDict(data['retweeted_status'])
    else:
      retweeted_status = None

    if 'current_user_retweet' in data:
      current_user_retweet = data['current_user_retweet']['id']
    else:
      current_user_retweet = None

    urls = None
    user_mentions = None
    hashtags = None
    media = None
    if 'entities' in data:
      if 'urls' in data['entities']:
        urls = [Url.NewFromJsonDict(u) for u in data['entities']['urls']]
      if 'user_mentions' in data['entities']:
        user_mentions = [User.NewFromJsonDict(u) for u in data['entities']['user_mentions']]
      if 'hashtags' in data['entities']:
        hashtags = [Hashtag.NewFromJsonDict(h) for h in data['entities']['hashtags']]
      if 'media' in data['entities']:
        media = data['entities']['media']
      else:
        media = []
        return Status(created_at=data.get('created_at', None),
                  favorited=data.get('favorited', None),
                  favorite_count=data.get('favorite_count', None),
                  id=data.get('id', None),
                  text=data.get('text', None),
                  location=data.get('location', None),
                  in_reply_to_screen_name=data.get('in_reply_to_screen_name', None),
                  in_reply_to_user_id=data.get('in_reply_to_user_id', None),
                  in_reply_to_status_id=data.get('in_reply_to_status_id', None),
                  truncated=data.get('truncated', None),
                  retweeted=data.get('retweeted', None),
                  source=data.get('source', None),
                  user=user,
                  urls=urls,
                  user_mentions=user_mentions,
                  hashtags=hashtags,
                  media=media,
                  geo=data.get('geo', None),
                  place=data.get('place', None),
                  coordinates=data.get('coordinates', None),
                  contributors=data.get('contributors', None),
                  retweeted_status=retweeted_status,
                  current_user_retweet=current_user_retweet,
                  retweet_count=data.get('retweet_count', None),
                  possibly_sensitive=data.get('possibly_sensitive', None),
                  scopes=data.get('scopes', None),
                  withheld_copyright=data.get('withheld_copyright', None),
                  withheld_in_countries=data.get('withheld_in_countries', None),
                  withheld_scope=data.get('withheld_scope', None))
