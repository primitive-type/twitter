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

    /**
     * Constructor
     */
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
    }

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
     * Returns profile background color of the user
     */
    public function getProfileBackgroundColor()
    {
        return $this->profile_background_color;
    }

    /**
     * @param $profile_background_color
     * Set profile background color to specified value
     */
    public function setProfileBackgroundColor($profile_background_color)
    {
        $this->profile_background_color = $profile_background_color;
    }

    /**
     * @returns $profile_link_color
     * Returns profile link color of the user
     */
    public function getProfileLinkColor()
    {
        return $this->profile_link_color;
    }

    /**
     * @param $profile_link_color
     * Set profile link color to specified value
     */
    public function setProfileLinkColor($profile_link_color)
    {
        $this->profile_link_color = $profile_link_color;
    }

    /**
     * @returns $profile_text_color
     * Returns profile text color of the user
     */
    public function getProfileTextColor()
    {
        return $this->profile_text_color;
    }

    /**
     * @param $profile_text_color
     * Set profile text color to specified value
     */
    public function setProfileTextColor($profile_text_color)
    {
        $this->profile_text_color = $profile_text_color;
    }

    /**
     * @returns $protected
     * Returns protected status of the user
     */
    public function getProtected()
    {
        return $this->protected;
    }

    /**
     * @param $protected
     * Set protected status to specified value
     */
    public function setProtected($protected)
    {
        $this->protected = $protected;
    }

    /**
     * @returns $utc_offset
     * Returns the UTC offset of the user
     */
    public function getUtcOffset()
    {
        return $this->utc_offset;
    }

    /**
     * @param $utc_offset
     * Set UTC offset to specified value
     */
    public function setUtcOffset($utc_offset)
    {
        $this->utc_offset = $utc_offset;
    }

    /**
     * @returns $time_zone
     * Returns time zone of the user
     */
    public function getTimeZone()
    {
        return $this->time_zone;
    }

    /**
     * @param $time_zone
     * Set time zone to specified value
     */
    public function setTimeZone($time_zone)
    {
        $this->time_zone = $time_zone;
    }

    /**
     * @returns $status
     * Returns most recent status of the user
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $status
     * Set status of specified value
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @returns $friends_count
     * Returns the friend count of the user
     */
    public function getFriendsCount()
    {
        return $this->friends_count;
    }

    /**
     * @param $friends_count
     * Set the friends_count to specified value
     */
    public function setFriendsCount($count)
    {
        $this->friends_count = $count;
    }

    /**
     * @returns $listed_count
     * Returns the number of lists the user is a member of
     */
    public function getListedCount()
    {
        return $this->listed_count;
    }

    /**
     * @param $listed_count
     * Set the number of lists the user is a member of
     */
    public function setListedCount($listed_count)
    {
        $this->listed_count = $listed_count;
    }

    /**
     * @returns $followers_count
     * Returns the number of followers the user has
     */
    public function getFollowersCount()
    {
        return $this->followers_count;
    }

    /**
     * @param $followers_count
     * Set number of followers the user has
     */
    public function setFollowersCount($followers_count)
    {
        $this->followers_count = $followers_count;
    }

    /**
     * @returns $statuses_count
     * Returns the number of status updates the user has made
     */
    public function getStatusesCount()
    {
        return $this->statuses_count;
    }

    /**
     * @param $statuses_count
     * Set protected status to specified value 
     */
    public function setStatusesCount($statuses_count)
    {
        $this->statuses_count = $statuses_count;
    }

    /**
     * @returns $favourites_count
     * Get the number of favourites for this user.
     */
    public function getFavouritesCount()
    {
        return $this->favourites_count;
    }

    /**
     * @param $favourites_count
     * Set protected status to specified value
     */
    public function setFavouritesCount($count)
    {
        $this->favourites_count = $favourites_count;
    }

    public function getGeoEnabled()
    {
        return $this->geo_enabled;
    }

    public function setGeoEnabled($geo_enabled)
    {
        $this->geo_enabled = $geo_enabled;
    }

    public function getVerified()
    {
        return $this->verified;
    }

    public function setVerified($verified)
    {
        $this->verified = $verified;
    }

    public function getLang()
    {
        return $this->lang;
    }

    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    public function getNotifications()
    {
        return $this->notifications;
    }

    public function setNotifications($notifications)
    {
        $this->notifications = $notifications;
    }

    public function getContributorsEnabled()
    {
        return $this->contributors_enabled;
    }

    public function setContributorsEnabled($contributors_enabled)
    {
        $this->contributors_enabled = $contributors_enabled;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function isEqual($other)
    {
        return ($this == $other);
    }

    public function __toString()
    {
        return json_encode($this);
    }

    public function AsJsonString()
    {
    '''A JSON string representation of this twitter.User instance.

        returns:
      A JSON string representation of this twitter.User instance
   '''
        return simplejson.dumps(.AsDict(), sort_keys=True)
    }

    public function asArray()
    {
        data = array();
        $json = $this->__toString();
	$data = json_decode($json, true);
	foreach($data as $key => $value) {
	    if($value == "") {
	        unset($data[$key]);
	    }
	}
        return $data;
    }

    public function NewFromJsonDict(data)
    {
    '''Create a new instance based on a JSON dict.

    Args:
      data:
        A JSON dict, as converted from the JSON in the twitter API

        returns:
      A twitter.User instance
    '''
    if 'status' in data:
      status = $Status.NewFromJsonDict(data['status'])
    else:
      status = $None
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
                profile_background_tile = $data.get('profile_background_tile', None),
                profile_background_image_url = $data.get('profile_background_image_url', None),
                profile_sidebar_fill_color = $data.get('profile_sidebar_fill_color', None),
                profile_background_color = $data.get('profile_background_color', None),
                profile_link_color = $data.get('profile_link_color', None),
                profile_text_color = $data.get('profile_text_color', None),
                protected = $data.get('protected', None),
                utc_offset = $data.get('utc_offset', None),
                time_zone = $data.get('time_zone', None),
                url=data.get('url', None),
                status=status,
                geo_enabled=data.get('geo_enabled', None),
                verified=data.get('verified', None),
                lang=data.get('lang', None),
                notifications=data.get('notifications', None),
                contributors_enabled=data.get('contributors_enabled', None),
                created_at=data.get('created_at', None),
                listed_count=data.get('listed_count', None))
