<?php namespace League\Twitter;

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
    public function __construct($data) {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->name = isset($data['name']) ? $data['name'] : null;
        $this->screen_name = isset($data['screen_name']) ? $data['screen_name'] : null;
        $this->location = isset($data['location']) ? $data['location'] : null;
        $this->description = isset($data['description']) ? $data['description'] : null;
        $this->profile_image_url = isset($data['profile_image_url']) ? $data['profile_image_url'] : null;
        $this->profile_background_tile = isset($data['profile_background_tile']) ? $data['profile_background_tile'] : null;
        $this->profile_background_image_url = isset($data['profile_background_image_url']) ? $data['profile_background_image_url'] : null;
        $this->profile_sidebar_fill_color = isset($data['profile_sidebar_fill_color']) ? $data['profile_sidebar_fill_color'] : null;
        $this->profile_background_color = isset($data['profile_background_color']) ? $data['profile_background_color'] : null;
        $this->profile_link_color = isset($data['profile_link_color']) ? $data['profile_link_color'] : null;
        $this->profile_text_color = isset($data['profile_text_color']) ? $data['profile_text_color'] : null;
        $this->protected = isset($data['protected']) ? $data['protected'] : null;
        $this->utc_offset = isset($data['utc_offset']) ? $data['utc_offset'] : null;
        $this->time_zone = isset($data['time_zone']) ? $data['time_zone'] : null;
        $this->followers_count = isset($data['followers_count']) ? $data['followers_count'] : null;
        $this->friends_count = isset($data['friends_count']) ? $data['friends_count'] : null;
        $this->statuses_count = isset($data['statuses_count']) ? $data['statuses_count'] : null;
        $this->favourites_count = isset($data['favourites_count']) ? $data['favourites_count'] : null;
        $this->url = isset($data['url']) ? $data['url'] : null;
        $this->status = isset($data['url']) ? $data['status'] : null;
        $this->geo_enabled = isset($data['geo_enabled']) ? $data['geo_enabled'] : null;
        $this->verified = isset($data['verified']) ? $data['verified'] : null;
        $this->lang = isset($data['lang']) ? $data['lang'] : null;
        $this->notifications = isset($data['notifications']) ? $data['notifications'] : null;
        $this->contributors_enabled = isset($data['contributors_enabled']) ? $data['contributors_enabled'] : null;
        $this->created_at = isset($data['created_at']) ? $data['created_at'] : null;
        $this->listed_count = isset($data['listed_count']) ? $data['listed_count'] : null;
    }

    /**
     * Static method to return a new instance of the user object
     *
     * @param array $data
     *
     * @return \League\Twitter\User 
     */
    public static function newFromJsonArray($data)
    {
        return new static($data);
    }

    /**
     * Get the Id of the user
     *
     * @return string $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the id of the user to the provided parameter
     *
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Return the name of the user
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    } 

    /**
     * Set the name of the user to the specified value
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }    

    /**
     * Return the value of users screen name
     *
     * @return string $screen_name
     */
    public function getScreenName()
    {
        return $this->screen_name;
    }

    /**
     * Set the value of users screen name to the specified value
     *
     * @param string $screen_name
     */
    public function setScreenName($screen_name)
    { 
        $this->screen_name = $screen_name;
    }

    /**
     * Return the location of user
     *
     * @return string $location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set location of user to specified value
     *
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * Return the description of the user
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the description of the user to the specified value
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get the url associated with the user
     *
     * @return string $url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set url of user to specified value
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Return the profile image url of the user
     *
     * @return string $profile_image_url
     */
    public function getProfileImageUrl()
    {
        return $this->profile_image_url;
    }

    /**
     * Set profile image url of user to specified value
     *
     * @param string $profile_image_url
     */
    public function setProfileImageUrl($profile_image_url)
    {
        $this->profile_image_url = $profile_image_url;
    }

    /**
     * Return the profile background tile of the user
     *
     * @return bool $profile_background_tile
     */
    public function getProfileBackgroundTile()
    {
        return $this->profile_background_tile;
    }

    /**
     * Set profile background tile to specified value
     *
     * @param bool $profile_background_tile
     */
    public function setProfileBackgroundTile($profile_background_tile)
    {
        $this->profile_background_tile = $profile_background_tile;
    }

    /**
     * Returns profile background image url of the user
     *
     * @return string  $profile_background_image_url
     */
    public function getProfileBackgroundImageUrl()
    {
        return $this->profile_background_image_url;
    }

    /**
     * Set profile background image url to specified value
     *
     * @param string $profile_background_image_url
     */
    public function setProfileBackgroundImageUrl($profile_background_image_url)
    {
        $this->profile_background_image_url = $profile_background_image_url;
    }

    /**
     * Returns profile sidebar fill color of the user
     *
     * @return string $profile_sidebar_fill_color
     */
    public function getProfileSidebarFillColor()
    {
        return $this->profile_sidebar_fill_color;
    }

    /**
     * Set profile sidebar fill color to specified value
     *
     * @param string $profile_sidebar_fill_color
     */
    public function setProfileSidebarFillColor($profile_sidebar_fill_color)
    {
        $this->profile_sidebar_fill_color = $profile_sidebar_fill_color;
    }

    /**
     * Returns profile background color of the user
     *
     * @return string $profile_background_color
     */
    public function getProfileBackgroundColor()
    {
        return $this->profile_background_color;
    }

    /**
     * Set profile background color to specified value
     *
     * @param string $profile_background_color
     */
    public function setProfileBackgroundColor($profile_background_color)
    {
        $this->profile_background_color = $profile_background_color;
    }

    /**
     * Returns profile link color of the user
     *
     * @return string $profile_link_color
     */
    public function getProfileLinkColor()
    {
        return $this->profile_link_color;
    }

    /**
     * Set profile link color to specified value
     *
     * @param string $profile_link_color
     */
    public function setProfileLinkColor($profile_link_color)
    {
        $this->profile_link_color = $profile_link_color;
    }

    /**
     * Returns profile text color of the user
     *
     * @return string $profile_text_color
     */
    public function getProfileTextColor()
    {
        return $this->profile_text_color;
    }

    /**
     * Set profile text color to specified value
     *
     * @param string $profile_text_color
     */
    public function setProfileTextColor($profile_text_color)
    {
        $this->profile_text_color = $profile_text_color;
    }

    /**
     * Returns protected status of the user
     *
     * @return bool $protected
     */
    public function getProtected()
    {
        return $this->protected;
    }

    /**
     * Set protected status to specified value
     *
     * @param bool $protected
     */
    public function setProtected($protected)
    {
        $this->protected = $protected;
    }

    /**
     * Returns the UTC offset of the user
     *
     * @return int $utc_offset
     */
    public function getUtcOffset()
    {
        return $this->utc_offset;
    }

    /**
     * Set UTC offset to specified value
     *
     * @param int $utc_offset
     */
    public function setUtcOffset($utc_offset)
    {
        $this->utc_offset = $utc_offset;
    }

    /**
     * Returns time zone of the user
     *
     * @return string $time_zone
     */
    public function getTimeZone()
    {
        return $this->time_zone;
    }

    /**
     * Set time zone to specified value
     *
     * @param string $time_zone
     */
    public function setTimeZone($time_zone)
    {
        $this->time_zone = $time_zone;
    }

    /**
     * Returns most recent status of the user
     *
     * @return \League\Twitter\Status $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status of specified value
     *
     * @param \League\Twitter\Status $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Returns the friend count of the user
     *
     * @return int $friends_count
     */
    public function getFriendsCount()
    {
        return $this->friends_count;
    }

    /**
     * Set the friends_count to specified value
     *
     * @param int $friends_count
     */
    public function setFriendsCount($count)
    {
        $this->friends_count = $count;
    }

    /**
     * Returns the number of lists the user is a member of
     *
     * @return int $listed_count
     */
    public function getListedCount()
    {
        return $this->listed_count;
    }

    /**
     * Set the number of lists the user is a member of
     *
     * @param int $listed_count
     */
    public function setListedCount($listed_count)
    {
        $this->listed_count = $listed_count;
    }

    /**
     * Returns the number of followers the user has
     *
     * @return int $followers_count
     */
    public function getFollowersCount()
    {
        return $this->followers_count;
    }

    /**
     * Set number of followers the user has
     *
     * @param int $followers_count
     */
    public function setFollowersCount($followers_count)
    {
        $this->followers_count = $followers_count;
    }

    /**
     * Returns the number of status updates the user has made
     *
     * @return int $statuses_count
     */
    public function getStatusesCount()
    {
        return $this->statuses_count;
    }

    /**
     * Set protected status to specified value
     *
     * @param int $statuses_count
     */
    public function setStatusesCount($statuses_count)
    {
        $this->statuses_count = $statuses_count;
    }

    /**
     * Get the number of favourites for this user
     *
     * @return int $favourites_count
     */
    public function getFavouritesCount()
    {
        return $this->favourites_count;
    }

    /**
     * Set protected status to specified value
     *
     * @param int $favourites_count
     */
    public function setFavouritesCount($count)
    {
        $this->favourites_count = $favourites_count;
    }

    /**
     * Get the GeoEnabled setting for this user
     * @return bool $geo_enabled
     */
    public function getGeoEnabled()
    {
        return $this->geo_enabled;
    }

    /**
     * Set Geo Enabled flag to provided value
     * @param bool $favourites_count
     */
    public function setGeoEnabled($geo_enabled)
    {
        $this->geo_enabled = $geo_enabled;
    }

    /**
     * Get the verified status for this user.
     * @return bool $verified
     */
    public function getVerified()
    {
        return $this->verified;
    }

    /**
     * Set verified status to specified value
     * @param bool $verified
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;
    }

    /**
     * Get the language for this user.
     * @return string $language
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Set language to specified value
     * @param string $language
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * Get the notifications for this user.
     * @return bool $notifications
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Set the notifications to specified value
     * @param bool $notifications
     */
    public function setNotifications($notifications)
    {
        $this->notifications = $notifications;
    }

    /**
     * Get the contributors enabled setting for this user.
     * @return bool $contributors_enabled
     */
    public function getContributorsEnabled()
    {
        return $this->contributors_enabled;
    }

    /**
     * Set contributors enabled setting to specified value
     * @param bool $contributors_enabled
     */
    public function setContributorsEnabled($contributors_enabled)
    {
        $this->contributors_enabled = $contributors_enabled;
    }

    /**
     * Get the date/time this user was created
     * @return string $created_at
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set date/time of user creation to specified value
     * @param string $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }
    
    /**
     * Comparison to see if the provided object equals the current instance
     * @param \League\Twitter\User $other
     */
    public function isEqual($other)
    {
        return ($this == $other);
    }

    /**
     * Method for printing the object as a string
     * @return string a json representation of the object
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Method for printing the object as a string
     * @return string a json representation of the object
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * Method to return the User object as an array
     * @return array $data
     */
    public function toArray()
    {
        $data = array();
        if ($this->id !== null) {
            $data['id'] = $this->id;
        }
        if ($this->name !== null) {
            $data['name'] = $this->name;
        }
        if ($this->screen_name !== null) {
            $data['screen_name'] = $this->screen_name;
        }
        if ($this->location !== null) {
            $data['location'] = $this->location;
        }
        if ($this->description !== null) {
            $data['description'] = $this->description;
        }
        if ($this->profile_image_url !== null) {
            $data['profile_image_url'] = $this->profile_image_url;
        }
        if ($this->profile_background_tile !== null) {
            $data['profile_background_tile'] = $this->profile_background_tile;
        }
        if ($this->profile_background_image_url !== null) {
            $data['profile_background_image_url'] = $this->profile_background_image_url;
        }
        if ($this->profile_sidebar_fill_color !== null) {
            $data['profile_sidebar_fill_color'] = $this->profile_sidebar_fill_color;
        }
        if ($this->background_color !== null) {
            $data['background_color'] = $this->background_color;
        }
        if ($this->profile_link_color !== null) {
            $data['profile_link_color'] = $this->profile_link_color;
        }
        if ($this->profile_text_color !== null) {
            $data['profile_text_color'] = $this->profile_text_color;
        }
        if ($this->protected !== null) {
            $data['protected'] = $this->protected;
        }
        if ($this->utc_offset !== null) {
            $data['utc_offset'] = $this->utc_offset;
        }
        if ($this->time_zone !== null) {
            $data['time_zone'] = $this->time_zone;
        }
        if ($this->url !== null) {
            $data['url'] = $this->url;
        }
        if ($this->status !== null) {
            $data['status'] = $this->status;
        }
        if ($this->friends_count !== null) {
            $data['friends_count'] = $this->friends_count;
        }
        if ($this->followers_count !== null) {
            $data['followers_count'] = $this->followers_count;
        }
        if ($this->statuses_count !== null) {
            $data['statuses_count'] = $this->statuses_count;
        }
        if ($this->favourites_count !== null) {
            $data['favourites_count'] = $this->favourites_count;
        }
        if ($this->geo_enabled !== null) {
            $data['geo_enabled'] = $this->geo_enabled;
        }
        if ($this->verified !== null) {
            $data['verified'] = $this->verified;
        }
        if ($this->lang !== null) {
            $data['lang'] = $this->lang;
        }
        if ($this->notifications !== null) {
            $data['notifications'] = $this->notifications;
        }
        if ($this->contributors_enabled !== null) {
            $data['contributors_enabled'] = $this->contributors_enabled;
        }
        if ($this->created_at !== null) {
            $data['created_at'] = $this->created_at;
        }
        if ($this->listed_count !== null) {
            $data['listed_count'] = $this->listed_count;
        }
        return $data;
    }
}
