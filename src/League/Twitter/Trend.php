<?php
namespace League\Twitter;

/**
 * A class representing a trending topic
 */
class Trend
{
    /**
     * @param string $name
     * @param string $query
     * @param int $timestamp
     * @param string $url
     *
     * Constructor
     */
    public function __construct($name=null, $query=null, $timestamp=null, $url=null)
    {
        $this->name = $name;
        $this->query = $query;
        $this->timestamp = $timestamp;
        $this->url = $url;
    }

    /**
     * @return string 
     *
     * Magic method use to output the instance as a string, using formatted string
     */
    public function __toString()
    {
        return sprintf('Name: %s\nQuery: %s\nTimestamp: %s\nSearch URL: %s\n', $this->name, $this->query, $this->timestamp, $this->url);
    }

    /**
     * @param \League\Twitter\Trend $other
     * @return boolean
     *
     * Method to determine if 2 Trend instances are equal to each other
     */
    public function isEqual(Trend $other)
    {
        if($this == $other) {
	    return true;
	}
	return false;
    }

    /**
     * @param array $data
     * @return \League\Twitter\Trend
     *
     * Creates an instance of Trend from a data array parameter
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
