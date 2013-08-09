<?php namespace League\Twitter;

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
        $this->created_at = isset($data['created_at']) : $data['created_at'] : null;
        $this->recipient_id = isset($data['recipient_id']) : $data['recipient_id'] : null;
        $this->sender_id = isset($data['sender_id']) : $data['sender_id'] : null;
        $this->sender_screen_name = isset($data['sender_screen_name']) : $data['sender_screen_name'] : null;
        $this->id = isset($data['id']) : $data['id'] : null;
        $this->recipient_screen_name = isset($data['recipient_screen_name']) : $data['recipient_screen_name'] : null;
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
    public function setId(id) {
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
    

    Returns:
      The unique sender id of this direct message
    '''
    return $this->_sender_id

    public function setSenderId($sender_id)
    {

    Args:
      sender_id:
        The unique sender id of this direct message
    '''
    $this->_sender_id = sender_id

  sender_id = property(GetSenderId, SetSenderId,
                doc='The unique sender id of this direct message.')

    /**
     * Get the unique sender screen name of this direct message.
     */
    public function getSenderScreenName()
    {
    

    Returns:
      The unique sender screen name of this direct message
    '''
    return $this->_sender_screen_name

    public function setSenderScreenName($sender_screen_name)
    {

    Args:
      sender_screen_name:
        The unique sender screen name of this direct message
    '''
    $this->_sender_screen_name = sender_screen_name

  sender_screen_name = property(GetSenderScreenName, SetSenderScreenName,
                doc='The unique sender screen name of this direct message.')

        /**
         * Get the unique recipient id of this direct message.
         */
    public function getRecipientId()
    {
    

    Returns:
      The unique recipient id of this direct message
    '''
    return $this->_recipient_id

    public function setRecipientId($recipient_id)
    {

    Args:
      recipient_id:
        The unique recipient id of this direct message
    '''
    $this->_recipient_id = recipient_id

  recipient_id = property(GetRecipientId, SetRecipientId,
                doc='The unique recipient id of this direct message.')

    /**
     * Get the unique recipient screen name of this direct message.
     */
    public function getRecipientScreenName()
    {
    

    Returns:
      The unique recipient screen name of this direct message
    '''
    return $this->_recipient_screen_name

    public function setRecipientScreenName($recipient_screen_name)
    {

    Args:
      recipient_screen_name:
        The unique recipient screen name of this direct message
    '''
    $this->_recipient_screen_name = recipient_screen_name

  recipient_screen_name = property(GetRecipientScreenName, SetRecipientScreenName,
                doc='The unique recipient screen name of this direct message.')

        /**
         * Get the text of this direct message.
         */
    public function getText()
    {
    

    Returns:
      The text of this direct message.
    '''
    return $this->_tex



    Args:
      text:
        The text of this direct message
    '''
    $this->_text = text

  text = property(GetText, SetText,
                  doc='The text of this direct message')

  def __ne__(self, other):
    return not $this->__eq__(other)

  def __eq__(self, other):
    try:
      return other and \
          $this->id == other.id and \
          $this->created_at == other.created_at and \
          $this->sender_id == other.sender_id and \
          $this->sender_screen_name == other.sender_screen_name and \
          $this->recipient_id == other.recipient_id and \
          $this->recipient_screen_name == other.recipient_screen_name and \
          $this->text == other.text
    except AttributeError:
      return False

  def __str__(self):
    '''A string representation of this twitter.DirectMessage instance.

    The return value is the same as the JSON string representation.

    Returns:
      A string representation of this twitter.DirectMessage instance.
    '''
    return $this->AsJsonString()

  def AsJsonString(self):
    '''A JSON string representation of this twitter.DirectMessage instance.

    Returns:
      A JSON string representation of this twitter.DirectMessage instance
   '''
    return simplejson.dumps($this->AsDict(), sort_keys=True)

    public function toArray() {
        '''A dict representation of this twitter.DirectMessage instance.

        The return value uses the same key names as the JSON representation.

        Return:
          A dict representing this twitter.DirectMessage instance
        '''
        data = {}
        if $this->id:
          data['id'] = $this->id
        if $this->created_at:
          data['created_at'] = $this->created_at
        if $this->sender_id:
          data['sender_id'] = $this->sender_id
        if $this->sender_screen_name:
          data['sender_screen_name'] = $this->sender_screen_name
        if $this->recipient_id:
          data['recipient_id'] = $this->recipient_id
        if $this->recipient_screen_name:
          data['recipient_screen_name'] = $this->recipient_screen_name
        if $this->text:
          data['text'] = $this->text
        return data
    }

