<?php

namespace App\Traits;

/**
 * Validator trait.
 *
 * Custom validator to help validate inputs for our need.
 *
 * This can be expanded to provide futher validation methods when required.
 *
 * @author Josh Kour <josh.kour@gmail.com>
 */
trait Validator 
{
	/**
     * Check if a url is valid.
     *
     * @param string $url
     * @return bool
     */
	public function isValidUrl(string $url) : bool 
	{
		if ($parts = parse_url($url)) {
		   if (!isset($parts['scheme'])) {
		       $url = 'http://'.$url;
		   }
		}

		$url = filter_var($url, FILTER_SANITIZE_URL);
		if (filter_var($url, FILTER_VALIDATE_URL)) {
		    return TRUE;
		}

		return FALSE;
	}
}