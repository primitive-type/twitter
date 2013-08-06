<?php namespace League\Twitter;

class HashTag
{
    /**
     * A class representing a Twitter HashTag.
     */
    public function __construct($text) {
      $this->text = $text;
    }

    /**
     * Create a new instance based on a JSON dict.
     * 
     * @param array data Array containing the JSON data from the twitter API
     * 
     * @return League\Twitter\HashTag
     */
    public static function newFromJsonArray($data) {
        $text = isset($data['text']) ? $data['text'] : null;
        
        return new static($text);
    }
}