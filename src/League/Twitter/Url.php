<?php 
namespace League\Twitter;

/**
 * A class representing a URL contained in a tweet
 */
class Url
{
    protected $url;
    protected $expanded_url;

    /**
     * constructor
     * @param string $url
     * @param string $expanded_url
     */
    public function __construct($url, $expanded_url)
    {
        $this->url = $url;
	$this->expanded_url = $expanded_url;
    }

    /**
     * Create a new instance based on an array
     * @param Array $data
     * @return \League\Twitter\Url
     */
    public static function newFromArray($data)
    {
        $url = (isset($data['url'])) ? $data['url'] : null;
	$expanded_url = (isset($data['expanded_url'])) ? $data['expanded_url'] : null;
	return new static($url, $expanded_url);
    }
}
