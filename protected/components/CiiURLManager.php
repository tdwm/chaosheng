<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * This class provides functionality for a dynamic ruleset, allowing us to inject routing rules on the fly via the admin
 * panel rather than relying solely upon the main.php array
 *
 * PHP version 5
 *
 * MIT LICENSE Copyright (c) 2012-2013 Charles R. Portwood II
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation 
 * files (the "Software"), to deal in the Software without restriction, including without limitation the rights to 
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom 
 * the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE 
 * FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION 
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @package    CiiMS Content Management System
 * @author     Charles R. Portwood II <charlesportwoodii@ethreal.net>
 * @copyright  Charles R. Portwood II <https://www.erianna.com> 2012-2013
 * @license    http://opensource.org/licenses/MIT  MIT LICENSE
 * @link       https://github.com/charlesportwoodii/CiiMS
 */

class CiiURLManager extends CUrlManager
{

	/**
	 * Whether or not we should cache url rules
	 * Override in main.php
	 * @var boolean
	 */
	public $cache = true;
	
	/**
	 * The id for our content rules
	 * @var string
	 */
	public $contentUrlRulesId = 'WFF-content-url-rules';
	
	/**
	 * The id for our category rules
	 * @var string
	 */
	public $categoriesUrlRulesId = 'WFF-categories-url-rules';
	
	/**
	 * This is where our defaultRules are stored. This takes the place of the rules array in main.php
	 * This has been moved to here so that we can dynamically update rules without having to worry
	 * making sure the client updates their main.php file on updates.
	 * @var array
	 */
	public $defaultRules = array(
		'/sitemap.xml' 						=> '/site/sitemap',
        '/search/<page:\d+>' 				=> '/site/mysqlsearch',
        '/search' 							=> '/site/mysqlsearch',
        '/hybridauth/<provider:\w+>'		=> '/hybridauth',
        '/contact' 							=> '/site/contact',
        '/blog.rss' 						=> '/content/rss',
        '/blog/<page:\d+>' 					=> '/content/list',
       // '/' 								=> '/content/list',
        '/' 								=> '/site/login',
        '/blog' 							=> '/content/list',
        '/activation/<email:\w+>/<id:\w+>' 	=> '/site/activation',
        '/activation' 						=> '/site/activation',
        '/forgot/<id:\w+>' 					=> '/site/forgot',
        '/forgot' 							=> '/site/forgot',
        '/register' 						=> '/site/register',
        '/register-success' 				=> '/site/registersuccess',
        '/login'							=> '/site/login',
        '/logout' 							=> '/site/logout',
        '/profile/edit'						=> '/profile/edit',
        '/profile/<id:\w+>/<displayName:\w+>' => '/profile/index',
        '/profile/<id:\w+>' 				=> '/profile/index',
        '/admin' 							=> '/admin',
        '/crawler' 							=> '/crawler',
        '/webset' 							=> '/crawler/webset',
        //'/show/<categoryslug:[^/]+>/<contentslug:\w+>' => '/content/show/',
	);

	/**
	 * Overrides processRules, allowing us to inject our own ruleset into the URL Manager
	 * Takes no parameters
	 **/
	protected function processRules()
	{
		
		$this->addBasicRules();
		//$this->cacheRules('content', $this->contentUrlRulesId);
		$this->cacheRules('categories', $this->categoriesUrlRulesId);
		
		// Append our cache rules BEFORE we run the defaults
		$this->rules['<controller:\w+>/<action:\w+>/<id:\d+>'] = '<controller>/<action>';
		$this->rules['<controller:\w+>/<action:\w+>'] = '<controller>/<action>';

		parent::processRules();
	}
	
	/**
	 * Adds basic rules back to the default route
	 */
	private function addBasicRules()
	{
		$this->rules = CMap::mergeArray($this->defaultRules, $this->rules);
	}

	/**
	 * Method for retrieving rules from the database and caching them
	 * @param $fromString - The string to be used in our FROM query
	 * @param $item - Address of the caching rule
	 * @does - Adds to the url rules and caches the result
	 **/
	private function cacheRules($fromString, &$item)
	{
		$urlRules = Yii::app()->cache->get($item);
		if($urlRules===false)
		{
		    $urlRules = Yii::app()->db->createCommand("SELECT id, slug FROM {$fromString}")->queryAll();
			
			if ($this->cache)
		    	Yii::app()->cache->set($item, $urlRules);
		}
		
		$tmpRules = array();
		foreach ($urlRules as $route)
		{
			if ($route['slug'] == NULL)
				continue;
			
			$pageRule = $route['slug'] . '/<page:\d+>';
			$rule = $route['slug'];
			
			// Handle the case of the slug being just /
			if($route['slug'] == '/')
			{
				$pageRule = '';
				$rule = '';
			}
			
			$tmpRules[$pageRule] = "{$fromString}/index/id/{$route['id']}";
			
			if ($fromString == 'categories')
				$tmpRules[$rule.'.rss'] = "content/rss/id/{$route['id']}";
			
			$tmpRules[$rule] = "{$fromString}/index/id/{$route['id']}";
		}

		$this->rules = CMap::mergeArray($tmpRules, $this->rules);
	}
}
