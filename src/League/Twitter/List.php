<?php namespace League\Twitter;

/**
 * A class representing the List structure used by the twitter API.
 */
class List
{
    public function __construct(
      $id = null,
      $name = null,
      $slug = null,
      $description = null,
      $full_name = null,
      $mode = null,
      $uri = null,
      $member_count = null,
      $subscriber_count = null,
      $following = null,
      $user = null
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->description = $description;
        $this->full_name = $full_name;
        $this->mode = $mode;
        $this->uri = $uri;
        $this->member_count = $member_count;
        $this->subscriber_count = $subscriber_count;
        $this->following = $following;
        $this->user = $user;
    }

    /**
     * Get the unique id of this list.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the unique id of this list.
     */
    public function setId($id)
    {
        return $this->id = $id;
    }

    /**
     * Get the real name of this list.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the real name of this list.
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the slug of this list.
     */
    public function getSlug(self)
    {
        return $this->slug;
    }

    /**
     * Set the slug of this list.
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get the description of this list.
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the description of this list.
     */
    public function setDescription($description):
    {
        $this->description = $description;
    }

    /**
     * Get the full_name of this list.
     */
    public function getFullName()
    {
        return $this->full_name;
    }

    /**
     * Set the full_name of this list.
     */
    public function setFullName($full_name)
    {
        $this->full_name = $full_name;
    }

    /**
     * Get the mode of this list.
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set the mode of this list.
     */
    public function setMode($mode):
    {
        $this->mode = $mode;
    }

    /**
     * Get the uri of this list.
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set the uri of this list.
     */
    public function setUri($uri):
    {
        $this->uri = $uri;
    }

    /**
     * Get the member_count of this list.
     */
    public function getMemberCount()
    {
        return $this->member_count;
    }

    /**
    * Set the member_count of this list.
    */
    public function setMemberCount($member_count):
    {
        $this->member_count = member_count;
    }

    /**
     * Get the subscriber_count of this list.
     */
    public function getSubscriber_count()
    {
        return $this->subscriber_count;
    }

    /**
     * Set the subscriber_count of this list.
     */
    public function setSubscriber_count($subscriber_count):
    {
        $this->subscriber_count = $subscriber_count;
    }

    /**
     * Get the following status of this list.
     */
    public function getFollowing()
    {
        return $this->following;
    }

    /**
    * Set the following status of this list.
    */
    public function setFollowing($following):
    {
        $this->following = following;
    }

    /**
     * Get the user of this list.
     */
    public function getUser()
    {
        return $this->user

    /**
     * Set the user of this list.
     */
    public function setUser($user):
    {
        $this->user = $user;
    }

    /**
     * Method to determine if 2 Trend instances are equal to each other
     *
     * @param \League\Twitter\Trend $other
     *
     * @return boolean
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
     * Method to return the List object as an array
     * @return array $data
     */
    public function toArray()
    {
        $data = array();

        if ($this->id) {
            $data['id'] = $this->id;
        }
        if ($this->name) {
            $data['name'] = $this->name;
        }
        if ($this->slug) {
            $data['slug'] = $this->slug;
        }
        if ($this->description) {
            $data['description'] = $this->description;
        }
        if ($this->full_name) {
            $data['full_name'] = $this->full_name;
        }
        if ($this->mode) {
            $data['mode'] = $this->mode;
        }
        if ($this->uri) {
            $data['uri'] = $this->uri;
        }
        if ($this->member_count) {
            $data['member_count'] = $this->member_count;
        }
        if ($this->subscriber_count) {
            $data['subscriber_count'] = $this->subscriber_count;
        }
        if ($this->following) {
            $data['following'] = $this->following;
        }
        if ($this->user) {
            $data['user'] = $this->user->toArray();
        }
        return $data;
    }

    /**
     * Create a new instance based on a JSON array.
     *
     * @return League\Twitter\List
     */
    public static function newFromJsonArray($data)
    {
        if (empty($data['user']) {
            $user =  null;
        } else {
            $user = User::newFromJsonArray($data['user']);
        }
        
        return new static(
            $data['id'],
            $data['name'],
            $data['slug'],
            $data['description'],
            $data['full_name'],
            $data['mode'],
            $data['uri'],
            $data['member_count'],
            $data['subscriber_count'],
            $data['following'],
            $user
        );
    }
}
