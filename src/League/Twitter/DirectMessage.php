<?php
namespace League\Twitter;

/**
 * A class representing the DirectMessage structure used by the twitter API.
 */
class DirectMessage
{
    /**
     * @var int The unique id of this direct message.
     */
    protected $id;

    /**
     * @var int The time this direct message was posted.
     */
    protected $created_at;

    /**
     * @var int The id of the twitter user that sent this message.
     */
    protected $sender_id;

    /**
     * @var int The name of the twitter user that sent this message.
     */
    protected $sender_screen_name;

    /**
     * @var int The id of the twitter that received this message.
     */
    protected $recipient_id;

    /**
     * @var int The name of the twitter that received this message.
     */
    protected $recipient_screen_name;

    /**
     * @var int The text of this direct message.
     */
    protected $text;

    /**
     * Constructor
     */
    public function __construct(array $data)
    {
        $this->text = isset($data['text']) ? $data['text'] : null;
        $this->created_at = isset($data['created_at']) ? $data['created_at'] : null;
        $this->recipient_id = isset($data['recipient_id']) ? $data['recipient_id'] : null;
        $this->sender_id = isset($data['sender_id']) ? $data['sender_id'] : null;
        $this->sender_screen_name = isset($data['sender_screen_name']) ? $data['sender_screen_name'] : null;
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->recipient_screen_name = isset($data['recipient_screen_name']) ? $data['recipient_screen_name'] : null;
    }

    /**
     * Get the unique id of this direct message.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the unique id of this direct message.
     *
     * @return void
     */
    public function setId($id)
    {
        return $this->id = $id;
    }

    /**
     * Get the time this direct message was posted.
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set the time this direct message was posted.
     *
     * @param DateTime $created_at 
     */
    public function setCreatedAt(DateTime $created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * Get the time this direct message was posted, in seconds since the epoch.
     *
     * @return int
     */
    public function getCreatedAtInSeconds()
    {
        $now = new DateTime();
        return abs($now->getTimestamp() - $this->created_at->getTimestamp());
    }

    /**
     * Get the unique sender id of this direct message.
     */
    public function getSenderId()
    {
        return $this->sender_id;
    }

    /**
     * Set the unique sender id of this direct message
     */
    public function setSenderId($sender_id)
    {
        $this->sender_id = $sender_id;
    }

    /**
     * Get the unique sender screen name of this direct message.
     */
    public function getSenderScreenName()
    {
        return $this->sender_screen_name;
    }

    /**
     * Sets the unique sender screen name of this direct message
     */
    public function setSenderScreenName($sender_screen_name)
    {
        $this->sender_screen_name = $sender_screen_name;
    }

    /**
     * Get the unique recipient id of this direct message.
     */
    public function getRecipientId()
    {
        return $this->recipient_id;
    }

    /**
     * Set the unique recipient id of this direct message
     */
    public function setRecipientId($recipient_id)
    {
        $this->recipient_id = $recipient_id;
    }

    /**
     * Get the unique recipient screen name of this direct message.
     */
    public function getRecipientScreenName()
    {
        return $this->recipient_screen_name;
    }

    /**
     * Set the unique recipient screen name of this direct message.
     */
    public function setRecipientScreenName($recipient_screen_name)
    {
        $this->recipient_screen_name = $recipient_screen_name;
    }

    /**
     * Get the text of this direct message.
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set the text of this direct message
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * This may not be necessary to bring over from the Python lib - thoughts?
     */
    public function notEquals($other)
    {
        return (!$this->equals($other));
    }

    /**
     * This may not be necessary to bring over from the Python lib - thoughts?
     */
    public function equals($other)
    {
        return $this == $other;
    }

    /**
     * Returns a JSON string representation of this twitter.DirectMessage instance
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * Returns an array representation of this twitter.DirectMessage instance
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}
