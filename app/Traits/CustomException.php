<?php

namespace App\Traits;

/**
 * CustomException trait.
 *
 * Custom exception class to help provide a central location to throw exception.
 *
 * This can be expanded to provide futher methods when required.
 *
 * @author Josh Kour <josh.kour@gmail.com>
 */
trait CustomException 
{

	/**
     * Throw an excception given a message.
     *
     * @throws Exception
     * @param string $message
     * @return void
     */
	public function exception(string $message) : \Exception 
	{
		throw new \Exception($message);
	}
}