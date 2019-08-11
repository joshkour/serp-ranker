<?php

namespace App\Controllers;

use App\Facades\SerpFacade;
use App\Traits\Validator;

/**
 * SeoRankerController class.
 *
 * @author Josh Kour <josh.kour@gmail.com>
 */
class SeoRankerController extends BaseController {
	use Validator;
	private $serpFacade;

    /**
     * Class constructor.
     *
     * @param SerpFacade $serpFacade
     * @return void
     */
	public function __construct(SerpFacade $serpFacade)
    {   
        $this->serpFacade = $serpFacade;
    }

    /**
     * Index method.
     *
     * @param void
     * @return void
     */
    public function index() 
    {
        $keyword = '';
        $url = '';

    	$messages = [];
    	$ranks = [];
    	if ($this->post('submit')) {
            $keyword = $this->post('keyword');
            $url = $this->post('url');

            // Validate the keyword and url parameter.
            $error = FALSE;
            $messages = [];
            if ($keyword === '' || ($keyword && strlen($keyword) < 3)) {
                $error = TRUE;
                $this->setErrorMessage('Please enter at least 3 characters for keyword.');
            }
            if (!$this->isValidUrl($url)) {
                $error = TRUE;
                $this->setErrorMessage('Please enter a valid url.');
            }

            // If no errors, grab the ranks by keyword and url.
            if (!$error) {
                $ranks = $this->serpFacade->getRankByKeywordMatchingUrl($keyword, $url);
            }
		}

        // Set the view data and display.
		$viewData = ['messages' => $this->getMessages(), 'keyword' => $keyword, 'url' => $url, 'ranks' => $ranks];
    	$this->view('seo-ranker.php', $viewData);
    }
}