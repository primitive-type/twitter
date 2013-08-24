<?php
namespace League\Twitter;

/**
 * A class representing a trending topic
 */
class Trend
{
     protected $name;
     protected $query;
     protected $timestamp;
     protected $url;

    /**
     * Constructor
     * @param string $name
     * @param string $query
     * @param int $timestamp
     * @param string $url
     */
    public function __construct($name=null, $query=null, $timestamp=null, $url=null)
    {
        $this->name = $name;
        $this->query = $query;
        $this->timestamp = $timestamp;
        $this->url = $url;
    }

    /**
     * Magic method use to output the instance as a string, using formatted string
     * @return string 
     */
    public function __toString()
    {
        return sprintf('Name: %s\nQuery: %s\nTimestamp: %s\nSearch URL: %s\n', $this->name, $this->query, $this->timestamp, $this->url);
    }

    /**
     * Method to determine if 2 Trend instances are equal to each other
     * @param \League\Twitter\Trend $other
     * @return boolean
     */
    public function isEqual(Trend $other)
    {
        if($this == $other) {
	    return true;
	}
	return false;
    }

    /**
     * Creates an instance of Trend from a data array parameter
     * @param array $data
     * @return \League\Twitter\Trend
     */
    public static function newFromJsonDict($data)
    {
        $name = (isset($data['name'])) ? $data['name'] : null;
	$query = (isset($data['query'])) ? $data['query'] : null;
	$url = (isset($data['url'])) ? $data['url'] : null;
	$timestamp (isset($data['timestamp'])) ? $data['timestamp'] : null;
	return new static($name, $query, $timestamp, $url); 
    }
}
