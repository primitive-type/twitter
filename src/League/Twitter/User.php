<?php
namespace League\Twitter;

class User
{
    protected $id;
    protected $name;
    protected $screen_name;
    protected $location;
    protected $description;
    protected $profile_image_url;
    protected $profile_background_tile;
    protected $profile_background_image_url;
    protected $profile_sidebar_fill_color;
    protected $profile_background_color;
    protected $profile_link_color;
    protected $profile_text_color;
    protected $protected;
    protected $utc_offset;
    protected $time_zone;
    protected $url;
    protected $status;
    protected $statuses_count;
    protected $followers_count;
    protected $friends_count;
    protected $favourites_count;
    protected $geo_enabled;
    protected $verified;
    protected $lang;
    protected $notifications;
    protected $contributors_enabled;
    protected $created_at;
    protected $listed_count;
    public function __construct(
        $id = null,
        $name = null,
        $screen_name = null,
        $location = null,
        $description = null,
        $profile_image_url = null,
        $profile_background_tile = null,
        $profile_background_image_url = null,
        $profile_sidebar_fill_color = null,
        $profile_background_color = null,
        $profile_link_color = null,
        $profile_text_color = null,
        $protected = null,
        $utc_offset = null,
        $time_zone = null,
        $followers_count = null,
        $friends_count = null,
        $statuses_count = null,
        $favourites_count = null,
        $url = null,
        $status = null,
        $geo_enabled = null,
        $verified = null,
        $lang = null,
        $notifications = null,
        $contributors_enabled = null,
        $created_at = null,
        $listed_count = null
    ) {
    $this->id = $id;
    $this->name = $name;
    $this->screen_name = $screen_name;
    $this->location = $location;
    $this->description = $description;
    $this->profile_image_url = $profile_image_url;
    $this->profile_background_tile = $profile_background_tile;
    $this->profile_background_image_url = $profile_background_image_url;
    $this->profile_sidebar_fill_color = $profile_sidebar_fill_color;
    $this->profile_background_color = $profile_background_color;
    $this->profile_link_color = $profile_link_color;
    $this->profile_text_color = $profile_text_color;
    $this->protected = $protected;
    $this->utc_offset = $utc_offset;
    $this->time_zone = $time_zone;
    $this->followers_count = $followers_count;
    $this->friends_count = $friends_count;
    $this->statuses_count = $statuses_count;
    $this->favourites_count = $favourites_count;
    $this->url = $url;
    $this->status = $status;
    $this->geo_enabled = $geo_enabled;
    $this->verified = $verified;
    $this->lang = $lang;
    $this->notifications = $notifications;
    $this->contributors_enabled = $contributors_enabled;
    $this->created_at = $created_at;
    $this->listed_count = $listed_count;

    /**
     * @returns $id
     * Get the Id of the user
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * Set the id of the user to the provided parameter
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @returns $name
     * Return the name of the user
     */
    public function getName()
    {
        return $this->name;
    } 

    /**
     * @param $name
     * Set the name of the user to the specified value
     */
    public function setName($name)
    {
        $this->name = $name;
    }	

    /**
     * @returns $screen_name
     * Return the value of users screen name
     */
    public function getScreenName()
    {
        return $this->screen_name;
    }

    /**
     * @param $screen_name
     * Set the value of users screen name to the specified value
     */
    public function setScreenName($screen_name)
    { 
        $this->screen_name = $screen_name;
    }

    /**
     * @returns $location
     * Return the location of user
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param $location
     * Set location of user to specified value
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @returns $description
     * Return the description of the user
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param $description
     * Set the description of the user to the specified value
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @returns $url
     * Get the url associated with the user
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $url
     * Set url of user to specified value
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @returns $profile_image_url
     * Return the profile image url of the user
     */
    public function getProfileImageUrl()
    {
        return $this->profile_image_url;
    }

    /**
     * @param $profile_image_url
     * Set profile image url of user to specified value
     */
    public function setProfileImageUrl($profile_image_url)
    {
        $this->profile_image_url = $profile_image_url;
    }

    /**
     * @returns $profile_background_tile
     * Return the profile background tile of the user
     */
    public function getProfileBackgroundTile()
    {
        return $this->profile_background_tile;
    }

    /**
     * @param $profile_background_tile
     * Set profile background tile to specified value
     */
    public function setProfileBackgroundTile($profile_background_tile)
    {
        $this->profile_background_tile = $profile_background_tile;
    }

    /**
     * @returns $profile_background_image_url
     * Returns profile background image url of the user
     */
    public function getProfileBackgroundImageUrl()
    {
        return $this->profile_background_image_url;
    }

    /**
     * @param $profile_background_image_url
     * Set profile background image url to specified value
     */
    public function setProfileBackgroundImageUrl($profile_background_image_url)
    {
        $this->profile_background_image_url = $profile_background_image_url;
    }

    /**
     * @returns $profile_sidebar_fill_color
     * Returns profile sidebar fill color of the user
     */
    public function getProfileSidebarFillColor()
    {
        return $this->profile_sidebar_fill_color;
    }

    /**
     * @param $profile_sidebar_fill_color
     * Set profile sidebar fill color to specified value
     */
    public function setProfileSidebarFillColor($profile_sidebar_fill_color)
    {
        $this->profile_sidebar_fill_color = $profile_sidebar_fill_color;
    }

    /**
     * @returns $profile_background_color
     * Returns profile background color url of the user
     */
    public function getProfileBackgroundColor()
    {
        return $this->profile_background_color;
    }

    /**
     * @param $profile_background_color
     * Set profile background color to specified value
     */
    public function SetProfileBackgroundColor($profile_background_color)
    {
        $this->profile_background_color = $profile_background_color;
    }

    /**
     * @returns $profile_link_color
     * Returns profile link color url of the user
     */
    public function GetProfileLinkColor()
    {
        return $this->profile_link_color;
    }

  def SetProfileLinkColor(self, profile_link_color):
    self._profile_link_color = profile_link_color

  def GetProfileTextColor(self):
    return self._profile_text_color

  def SetProfileTextColor(self, profile_text_color):
    self._profile_text_color = profile_text_color

  def GetProtected(self):
    return self._protected

  def SetProtected(self, protected):
    self._protected = protected

  def GetUtcOffset(self):
    return self._utc_offset

  def SetUtcOffset(self, utc_offset):
    self._utc_offset = utc_offset

  def GetTimeZone(self):
    '''Returns the current time zone string for the user.

    Returns:
      The descriptive time zone string for the user.
    '''
    return self._time_zone

  def SetTimeZone(self, time_zone):
    '''Sets the user's time zone string.

    Args:
      time_zone:
        The descriptive time zone to assign for the user.
    '''
    self._time_zone = time_zone

  def GetStatus(self):
    '''Get the latest twitter.Status of this user.

    Returns:
      The latest twitter.Status of this user
    '''
    return self._status

  def SetStatus(self, status):
    '''Set the latest twitter.Status of this user.

    Args:
      status:
        The latest twitter.Status of this user
    '''
    self._status = status


  def GetFriendsCount(self):
    '''Get the friend count for this user.

    Returns:
      The number of users this user has befriended.
    '''
    return self._friends_count

  def SetFriendsCount(self, count):
    '''Set the friend count for this user.

    Args:
      count:
        The number of users this user has befriended.
    '''
    self._friends_count = count


  def GetListedCount(self):
    '''Get the listed count for this user.

    Returns:
      The number of lists this user belongs to.
    '''
    return self._listed_count

  def SetListedCount(self, count):
    '''Set the listed count for this user.

    Args:
      count:
        The number of lists this user belongs to.
    '''
    self._listed_count = count


  def GetFollowersCount(self):
    '''Get the follower count for this user.

    Returns:
      The number of users following this user.
    '''
    return self._followers_count

  def SetFollowersCount(self, count):
    '''Set the follower count for this user.

    Args:
      count:
        The number of users following this user.
    '''
    self._followers_count = count


  def GetStatusesCount(self):
    '''Get the number of status updates for this user.

    Returns:
      The number of status updates for this user.
    '''
    return self._statuses_count

  def SetStatusesCount(self, count):
    '''Set the status update count for this user.

    Args:
      count:
        The number of updates for this user.
    '''
    self._statuses_count = count


  def GetFavouritesCount(self):
    '''Get the number of favourites for this user.

    Returns:
      The number of favourites for this user.
    '''
    return self._favourites_count

  def SetFavouritesCount(self, count):
    '''Set the favourite count for this user.

    Args:
      count:
        The number of favourites for this user.
    '''
    self._favourites_count = count


  def GetGeoEnabled(self):
    '''Get the setting of geo_enabled for this user.

    Returns:
      True/False if Geo tagging is enabled
    '''
    return self._geo_enabled

  def SetGeoEnabled(self, geo_enabled):
    '''Set the latest twitter.geo_enabled of this user.

    Args:
      geo_enabled:
        True/False if Geo tagging is to be enabled
    '''
    self._geo_enabled = geo_enabled


  def GetVerified(self):
    '''Get the setting of verified for this user.

    Returns:
      True/False if user is a verified account
    '''
    return self._verified

  def SetVerified(self, verified):
    '''Set twitter.verified for this user.

    Args:
      verified:
        True/False if user is a verified account
    '''
    self._verified = verified


  def GetLang(self):
    '''Get the setting of lang for this user.

    Returns:
      language code of the user
    '''
    return self._lang

  def SetLang(self, lang):
    '''Set twitter.lang for this user.

    Args:
      lang:
        language code for the user
    '''
    self._lang = lang


  def GetNotifications(self):
    '''Get the setting of notifications for this user.

    Returns:
      True/False for the notifications setting of the user
    '''
    return self._notifications

  def SetNotifications(self, notifications):
    '''Set twitter.notifications for this user.

    Args:
      notifications:
        True/False notifications setting for the user
    '''
    self._notifications = notifications


  def GetContributorsEnabled(self):
    '''Get the setting of contributors_enabled for this user.

    Returns:
      True/False contributors_enabled of the user
    '''
    return self._contributors_enabled

  def SetContributorsEnabled(self, contributors_enabled):
    '''Set twitter.contributors_enabled for this user.

    Args:
      contributors_enabled:
        True/False contributors_enabled setting for the user
    '''
    self._contributors_enabled = contributors_enabled


  def GetCreatedAt(self):
    '''Get the setting of created_at for this user.

    Returns:
      created_at value of the user
    '''
    return self._created_at

  def SetCreatedAt(self, created_at):
    '''Set twitter.created_at for this user.

    Args:
      created_at:
        created_at value for the user
    '''
    self._created_at = created_at


  def __ne__(self, other):
    return not self.__eq__(other)

  def __eq__(self, other):
    try:
      return other and \
             self.id == other.id and \
             self.name == other.name and \
             self.screen_name == other.screen_name and \
             self.location == other.location and \
             self.description == other.description and \
             self.profile_image_url == other.profile_image_url and \
             self.profile_background_tile == other.profile_background_tile and \
             self.profile_background_image_url == other.profile_background_image_url and \
             self.profile_sidebar_fill_color == other.profile_sidebar_fill_color and \
             self.profile_background_color == other.profile_background_color and \
             self.profile_link_color == other.profile_link_color and \
             self.profile_text_color == other.profile_text_color and \
             self.protected == other.protected and \
             self.utc_offset == other.utc_offset and \
             self.time_zone == other.time_zone and \
             self.url == other.url and \
             self.statuses_count == other.statuses_count and \
             self.followers_count == other.followers_count and \
             self.favourites_count == other.favourites_count and \
             self.friends_count == other.friends_count and \
             self.status == other.status and \
             self.geo_enabled == other.geo_enabled and \
             self.verified == other.verified and \
             self.lang == other.lang and \
             self.notifications == other.notifications and \
             self.contributors_enabled == other.contributors_enabled and \
             self.created_at == other.created_at and \
             self.listed_count == other.listed_count

    except AttributeError:
      return False

  def __str__(self):
    '''A string representation of this twitter.User instance.

    The return value is the same as the JSON string representation.

    Returns:
      A string representation of this twitter.User instance.
    '''
    return self.AsJsonString()

  def AsJsonString(self):
    '''A JSON string representation of this twitter.User instance.

    Returns:
      A JSON string representation of this twitter.User instance
   '''
    return simplejson.dumps(self.AsDict(), sort_keys=True)

  def AsDict(self):
    '''A dict representation of this twitter.User instance.

    The return value uses the same key names as the JSON representation.

    Return:
      A dict representing this twitter.User instance
    '''
    data = {}
    if self.id:
      data['id'] = self.id
    if self.name:
      data['name'] = self.name
    if self.screen_name:
      data['screen_name'] = self.screen_name
    if self.location:
      data['location'] = self.location
    if self.description:
      data['description'] = self.description
    if self.profile_image_url:
      data['profile_image_url'] = self.profile_image_url
    if self.profile_background_tile is not None:
      data['profile_background_tile'] = self.profile_background_tile
    if self.profile_background_image_url:
      data['profile_sidebar_fill_color'] = self.profile_background_image_url
    if self.profile_background_color:
      data['profile_background_color'] = self.profile_background_color
    if self.profile_link_color:
      data['profile_link_color'] = self.profile_link_color
    if self.profile_text_color:
      data['profile_text_color'] = self.profile_text_color
    if self.protected is not None:
      data['protected'] = self.protected
    if self.utc_offset:
      data['utc_offset'] = self.utc_offset
    if self.time_zone:
      data['time_zone'] = self.time_zone
    if self.url:
      data['url'] = self.url
    if self.status:
      data['status'] = self.status.AsDict()
    if self.friends_count:
      data['friends_count'] = self.friends_count
    if self.followers_count:
      data['followers_count'] = self.followers_count
    if self.statuses_count:
      data['statuses_count'] = self.statuses_count
    if self.favourites_count:
      data['favourites_count'] = self.favourites_count
    if self.geo_enabled:
      data['geo_enabled'] = self.geo_enabled
    if self.verified:
      data['verified'] = self.verified
    if self.lang:
      data['lang'] = self.lang
    if self.notifications:
      data['notifications'] = self.notifications
    if self.contributors_enabled:
      data['contributors_enabled'] = self.contributors_enabled
    if self.created_at:
      data['created_at'] = self.created_at
    if self.listed_count:
      data['listed_count'] = self.listed_count

    return data

  @staticmethod
  def NewFromJsonDict(data):
    '''Create a new instance based on a JSON dict.

    Args:
      data:
        A JSON dict, as converted from the JSON in the twitter API

    Returns:
      A twitter.User instance
    '''
    if 'status' in data:
      status = Status.NewFromJsonDict(data['status'])
    else:
      status = None
    return User(id=data.get('id', None),
                name=data.get('name', None),
                screen_name=data.get('screen_name', None),
                location=data.get('location', None),
                description=data.get('description', None),
                statuses_count=data.get('statuses_count', None),
                followers_count=data.get('followers_count', None),
                favourites_count=data.get('favourites_count', None),
                friends_count=data.get('friends_count', None),
                profile_image_url=data.get('profile_image_url_https', data.get('profile_image_url', None)),
                profile_background_tile = data.get('profile_background_tile', None),
                profile_background_image_url = data.get('profile_background_image_url', None),
                profile_sidebar_fill_color = data.get('profile_sidebar_fill_color', None),
                profile_background_color = data.get('profile_background_color', None),
                profile_link_color = data.get('profile_link_color', None),
                profile_text_color = data.get('profile_text_color', None),
                protected = data.get('protected', None),
                utc_offset = data.get('utc_offset', None),
                time_zone = data.get('time_zone', None),
                url=data.get('url', None),
                status=status,
                geo_enabled=data.get('geo_enabled', None),
                verified=data.get('verified', None),
                lang=data.get('lang', None),
                notifications=data.get('notifications', None),
                contributors_enabled=data.get('contributors_enabled', None),
                created_at=data.get('created_at', None),
                listed_count=data.get('listed_count', None))
