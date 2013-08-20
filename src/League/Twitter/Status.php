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
    public function GetCreatedAt($this->:
    '''Get the time this status message was posted.

    Returns:
      The time this status message was posted
    '''
    return $this->_created_at

    public function SetCreatedAt($this-> created_at):
    '''Set the time this status message was posted.

    Args:
      created_at:
        The time this status message was created
    '''
    $this->_created_at = created_at

  created_at = property(GetCreatedAt, SetCreatedAt,
                        doc='The time this status message was posted.')

    public function GetCreatedAtInSeconds($this->:
    '''Get the time this status message was posted, in seconds since the epoch.

    Returns:
      The time this status message was posted, in seconds since the epoch.
    '''
    return calendar.timegm(rfc822.parsedate($this->created_at))

  created_at_in_seconds = property(GetCreatedAtInSeconds,
                                   doc="The time this status message was "
                                       "posted, in seconds since the epoch")

    public function GetFavorited($this->:
    '''Get the favorited setting of this status message.

    Returns:
      True if this status message is favorited; False otherwise
    '''
    return $this->_favorited

    public function SetFavorited($this-> favorited):
    '''Set the favorited state of this status message.

    Args:
      favorited:
        boolean True/False favorited state of this status message
    '''
    $this->_favorited = favorited

  favorited = property(GetFavorited, SetFavorited,
                       doc='The favorited state of this status message.')

    public function GetFavoriteCount($this->:
    '''Get the favorite count of this status message.

    Returns:
      number of times this status message has been favorited
    '''
    return $this->_favorite_count

    public function SetFavoriteCount($this-> favorite_count):
    '''Set the favorited state of this status message.

    Args:
      favorite_count:
        int number of favorites for this status message
    '''
    $this->_favorite_count = favorite_count

  favorite_count = property(GetFavoriteCount, SetFavoriteCount,
                       doc='The number of favorites for this status message.')

    public function GetId($this->:
    '''Get the unique id of this status message.

    Returns:
      The unique id of this status message
    '''
    return $this->_id

    public function SetId($this-> id):
    '''Set the unique id of this status message.

    Args:
      id:
        The unique id of this status message
    '''
    $this->_id = id

  id = property(GetId, SetId,
                doc='The unique id of this status message.')

    public function GetInReplyToScreenName($this->:
    return $this->_in_reply_to_screen_name

    public function SetInReplyToScreenName($this-> in_reply_to_screen_name):
    $this->_in_reply_to_screen_name = in_reply_to_screen_name

  in_reply_to_screen_name = property(GetInReplyToScreenName, SetInReplyToScreenName,
                                     doc='')

    public function GetInReplyToUserId($this->:
    return $this->_in_reply_to_user_id

    public function SetInReplyToUserId($this-> in_reply_to_user_id):
    $this->_in_reply_to_user_id = in_reply_to_user_id

  in_reply_to_user_id = property(GetInReplyToUserId, SetInReplyToUserId,
                                 doc='')

    public function GetInReplyToStatusId($this->:
    return $this->_in_reply_to_status_id

    public function SetInReplyToStatusId($this-> in_reply_to_status_id):
    $this->_in_reply_to_status_id = in_reply_to_status_id

  in_reply_to_status_id = property(GetInReplyToStatusId, SetInReplyToStatusId,
                                   doc='')

    public function GetTruncated($this->:
    return $this->_truncated

    public function SetTruncated($this-> truncated):
    $this->_truncated = truncated

  truncated = property(GetTruncated, SetTruncated,
                       doc='')

    public function GetRetweeted($this->:
    return $this->_retweeted

    public function SetRetweeted($this-> retweeted):
    $this->_retweeted = retweeted

  retweeted = property(GetRetweeted, SetRetweeted,
                       doc='')

    public function GetSource($this->:
    return $this->_source

    public function SetSource($this-> source):
    $this->_source = source

  source = property(GetSource, SetSource,
                    doc='')

    public function GetText($this->:
    '''Get the text of this status message.

    Returns:
      The text of this status message.
    '''
    return $this->_text

    public function SetText($this-> text):
    '''Set the text of this status message.

    Args:
      text:
        The text of this status message
    '''
    $this->_text = text

  text = property(GetText, SetText,
                  doc='The text of this status message')

    public function GetLocation($this->:
    '''Get the geolocation associated with this status message

    Returns:
      The geolocation string of this status message.
    '''
    return $this->_location

    public function SetLocation($this-> location):
    '''Set the geolocation associated with this status message

    Args:
      location:
        The geolocation string of this status message
    '''
    $this->_location = location

  location = property(GetLocation, SetLocation,
                      doc='The geolocation string of this status message')

    public function GetRelativeCreatedAt($this->:
    '''Get a human readable string representing the posting time

    Returns:
      A human readable string representing the posting time
    '''
    fudge = 1.25
    delta  = long($this->now) - long($this->created_at_in_seconds)

    if delta < (1 * fudge):
      return 'about a second ago'
    elif delta < (60 * (1/fudge)):
      return 'about %d seconds ago' % (delta)
    elif delta < (60 * fudge):
      return 'about a minute ago'
    elif delta < (60 * 60 * (1/fudge)):
      return 'about %d minutes ago' % (delta / 60)
    elif delta < (60 * 60 * fudge) or delta / (60 * 60) == 1:
      return 'about an hour ago'
    elif delta < (60 * 60 * 24 * (1/fudge)):
      return 'about %d hours ago' % (delta / (60 * 60))
    elif delta < (60 * 60 * 24 * fudge) or delta / (60 * 60 * 24) == 1:
      return 'about a day ago'
    else:
      return 'about %d days ago' % (delta / (60 * 60 * 24))

  relative_created_at = property(GetRelativeCreatedAt,
                                 doc='Get a human readable string representing '
                                     'the posting time')

    public function GetUser($this->:
    '''Get a twitter.User representing the entity posting this status message.

    Returns:
      A twitter.User representing the entity posting this status message
    '''
    return $this->_user

    public function SetUser($this-> user):
    '''Set a twitter.User representing the entity posting this status message.

    Args:
      user:
        A twitter.User representing the entity posting this status message
    '''
    $this->_user = user

  user = property(GetUser, SetUser,
                  doc='A twitter.User representing the entity posting this '
                      'status message')

    public function GetNow($this->:
    '''Get the wallclock time for this status message.

    Used to calculate relative_created_at.    public functionaults to the time
    the object was instantiated.

    Returns:
      Whatever the status instance believes the current time to be,
      in seconds since the epoch.
    '''
    if $this->_now is None:
      $this->_now = time.time()
    return $this->_now

    public function SetNow($this-> now):
    '''Set the wallclock time for this status message.

    Used to calculate relative_created_at.    public functionaults to the time
    the object was instantiated.

    Args:
      now:
        The wallclock time for this instance.
    '''
    $this->_now = now

  now = property(GetNow, SetNow,
                 doc='The wallclock time for this status instance.')

    public function GetGeo($this->:
    return $this->_geo

    public function SetGeo($this-> geo):
    $this->_geo = geo

  geo = property(GetGeo, SetGeo,
                 doc='')

    public function GetPlace($this->:
    return $this->_place

    public function SetPlace($this-> place):
    $this->_place = place

  place = property(GetPlace, SetPlace,
                   doc='')

    public function GetCoordinates($this->:
    return $this->_coordinates

    public function SetCoordinates($this-> coordinates):
    $this->_coordinates = coordinates

  coordinates = property(GetCoordinates, SetCoordinates,
                         doc='')

    public function GetContributors($this->:
    return $this->_contributors

    public function SetContributors($this-> contributors):
    $this->_contributors = contributors

  contributors = property(GetContributors, SetContributors,
                          doc='')

    public function GetRetweeted_status($this->:
    return $this->_retweeted_status

    public function SetRetweeted_status($this-> retweeted_status):
    $this->_retweeted_status = retweeted_status

  retweeted_status = property(GetRetweeted_status, SetRetweeted_status,
                              doc='')

    public function GetRetweetCount($this->:
    return $this->_retweet_count

    public function SetRetweetCount($this-> retweet_count):
    $this->_retweet_count = retweet_count

  retweet_count = property(GetRetweetCount, SetRetweetCount,
                           doc='')

    public function GetCurrent_user_retweet($this->:
    return $this->_current_user_retweet

    public function SetCurrent_user_retweet($this-> current_user_retweet):
    $this->_current_user_retweet = current_user_retweet

  current_user_retweet = property(GetCurrent_user_retweet, SetCurrent_user_retweet,
                                  doc='')

    public function GetPossibly_sensitive($this->:
    return $this->_possibly_sensitive

    public function SetPossibly_sensitive($this-> possibly_sensitive):
    $this->_possibly_sensitive = possibly_sensitive

  possibly_sensitive = property(GetPossibly_sensitive, SetPossibly_sensitive,
                                doc='')

    public function GetScopes($this->:
    return $this->_scopes

    public function SetScopes($this-> scopes):
    $this->_scopes = scopes

  scopes = property(GetScopes, SetScopes, doc='')

    public function GetWithheld_copyright($this->:
    return $this->_withheld_copyright

    public function SetWithheld_copyright($this-> withheld_copyright):
    $this->_withheld_copyright = withheld_copyright

  withheld_copyright = property(GetWithheld_copyright, SetWithheld_copyright,
                                doc='')

    public function GetWithheld_in_countries($this->:
    return $this->_withheld_in_countries

    public function SetWithheld_in_countries($this-> withheld_in_countries):
    $this->_withheld_in_countries = withheld_in_countries

  withheld_in_countries = property(GetWithheld_in_countries, SetWithheld_in_countries,
                                doc='')

    public function GetWithheld_scope($this->:
    return $this->_withheld_scope

    public function SetWithheld_scope($this-> withheld_scope):
    $this->_withheld_scope = withheld_scope

  withheld_scope = property(GetWithheld_scope, SetWithheld_scope,
                                doc='')

    public function __ne__($this-> other):
    return not $this->__eq__(other)

    public function __eq__($this-> other):
    try:
      return other and \
             $this->created_at == other.created_at and \
             $this->id == other.id and \
             $this->text == other.text and \
             $this->location == other.location and \
             $this->user == other.user and \
             $this->in_reply_to_screen_name == other.in_reply_to_screen_name and \
             $this->in_reply_to_user_id == other.in_reply_to_user_id and \
             $this->in_reply_to_status_id == other.in_reply_to_status_id and \
             $this->truncated == other.truncated and \
             $this->retweeted == other.retweeted and \
             $this->favorited == other.favorited and \
             $this->favorite_count == other.favorite_count and \
             $this->source == other.source and \
             $this->geo == other.geo and \
             $this->place == other.place and \
             $this->coordinates == other.coordinates and \
             $this->contributors == other.contributors and \
             $this->retweeted_status == other.retweeted_status and \
             $this->retweet_count == other.retweet_count and \
             $this->current_user_retweet == other.current_user_retweet and \
             $this->possibly_sensitive == other.possibly_sensitive and \
             $this->scopes == other.scopes and \
             $this->withheld_copyright == other.withheld_copyright and \
             $this->withheld_in_countries == other.withheld_in_countries and \
             $this->withheld_scope == other.withheld_scope
    except AttributeError:
      return False

    public function __str__($this->:
    '''A string representation of this twitter.Status instance.

    The return value is the same as the JSON string representation.

    Returns:
      A string representation of this twitter.Status instance.
    '''
    return $this->AsJsonString()

    public function AsJsonString($this->:
    '''A JSON string representation of this twitter.Status instance.

    Returns:
      A JSON string representation of this twitter.Status instance
   '''
    return simplejson.dumps($this->AsDict(), sort_keys=True)

    public function AsDict($this->:
    '''A dict representation of this twitter.Status instance.

    The return value uses the same key names as the JSON representation.

    Return:
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
    public function NewFromJsonDict(data):
    '''Create a new instance based on a JSON dict.

    Args:
      data: A JSON dict, as converted from the JSON in the twitter API
    Returns:
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
