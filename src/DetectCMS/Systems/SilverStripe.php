<?php
namespace DetectCMS\Systems;

class SilverStripe extends \DetectCMS\DetectCMS {

	public $methods = array(
		"generator_meta",
	);

	public $home_html = "";
        public $home_headers = array();
	public $url = "";

        function __construct($home_html, $home_headers, $url) {
                $this->home_html = $home_html;
                $this->home_headers = $home_headers;
                $this->url = $url;
        }

	/**
	 * See if readme.html exists, and contains SilverStripe title
	 * @return [boolean]
	 */
	public function readme() {

		if($data = $this->fetch($this->url."/readme.html")) {

			if($html = str_get_html($data)) {

				if($title = $html->find("title",0)) {

					return strpos($title->plaintext, "SilverStripe &rsaquo; ReadMe") !== FALSE;

				}

			}

		}

		return FALSE;

	}

	/**
	 * Check for Generator header
	 * @return [boolean]
	 */
	public function generator_header() {

		if(is_array($this->home_headers)) {

			foreach($this->home_headers as $line) {

				if(strpos($line, "X-Generator") !== FALSE) {

					return strpos($line, "SilverStripe") !== FALSE;

				}

			}

		}

		return FALSE;

	}

	/**
	 * Check meta tags for generator
	 * @return [boolean]
	 */
	public function generator_meta() {

		if($this->home_html) {

			if($html = str_get_html($this->home_html)) {

				if($meta = $html->find("meta[name='generator']",0)) {

					return strpos($meta->content, "SilverStripe") !== FALSE;

				}

			}

		}

		return FALSE;

	}


	/**
	 * Check for SilverStripe Core scripts
	 * @return [boolean]
	 */
	public function scripts() {

		if($this->home_html) {

			if($html = str_get_html($this->home_html)) {

				foreach($html->find('script') as $element) {
					if (strpos($element->src, 'wp-includes') !==FALSE)
						return true;
				}

			}

		}

		return FALSE;

	}

	/**
	 * Check for SilverStripe Core API
	 * @return [boolean]
	 */
	public function api() {

		if($this->home_html) {

			if($html = str_get_html($this->home_html)) {

				foreach($html->find('link') as $element) {
					if (strpos($element->href, 'wp-json') !==FALSE)
						return true;
				}
			}

		}

		return FALSE;

	}

}
