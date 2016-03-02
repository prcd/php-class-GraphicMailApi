<?php

class GraphicMailApi
{
	/**
	  * The base URL for GraphicMail's API.
	  *
	  * @var string
	  */
	private $apiUrl = 'https://www.graphicmail.co.uk/api.aspx?SID=6';
	
	/**
	 * Return debug info or formatted response?
	 *
	 * @var string (params/response/off)
	 */
	private $debug = 'off';
	
	/**
	 * GraphicMail API account username.
	 *
	 * @var string
	 */
	private $username = NULL;

	/**
	  * GraphicMail API account password.
	  *
	  * @var string
	  */
	private $password = NULL;
	
	/**
	  * The folder where all related class files can be found (relative to this file).
	  *
	  * @var string
	  */
	private $classFolder = 'class-files';
	
	/**
	  * The URL used to make the API call, with username and password omitted.
	  *
	  * @var string
	  */
	private $safeRequestUrl = NULL;
	
	/**
	  * Format for returned parameter names.
	  *
	  * @var string
	  */
	private $format = 'snake_case';
	
	/**
	  * The folder to use to process the function.
	  *
	  * @var string
	  */
	private $functionFolder = NULL;
	
	/**
	  * The function name in lowercase with hyphens and underscroes removed.
	  *
	  * @var string
	  */
	private $functionName = NULL;
	
	/**
	  * Function name as expected by GraphicMail API.
	  *
	  * @var string
	  */
	private $functionNameGm = NULL;
	
	/**
	  * Function name as submitted to class.
	  *
	  * @var string
	  */
	private $functionNameOrig = NULL;
	
	/**
	  * Parameters submitted to class.
	  *
	  * @var array
	  */
	private $inputParams = array();
	
	/**
	  * Parameters after processing.
	  *
	  * @var array
	  */
	private $queryParams = array();
	
	/**
	  * How long GraphicMail took to respond.
	  *
	  * @var float
	  */
	private $responseTime = NULL;
	
	/**
	  * The string returned by GraphicMail.
	  *
	  * @var string
	  */
	private $result = NULL;
		
	function __construct($username = NULL, $password = NULL, $format = NULL)
	{
		if ($username != NULL) {
			$this->setUsername($username);
		}
		
		if ($password != NULL) {
			$this->setPassword($password);
		}
		
		if ($format != NULL) {
			$this->setFormat($format);
		}
	}
	
	private function setUsername($username)
	{
		if (is_string($username)) {
			$this->username = trim($username);
		}
		else {
			throw new Exception('Username must be a string');
		}
	}
	
	private function setPassword($password)
	{
		if (is_string($password)) {
			$this->password = trim($password);
		}
		else {
			throw new Exception('Password must be a string');
		}
	}
	
	private function checkUserPass()
	{
		if ($this->username == '' || $this->password == '') {
			throw new Exception ('Cannot make call, username and password not set');
		}
	}
	
	private function contactGraphicMail()
	{
		// create URL to make call
		$params = array_merge(array(
			'Username' => $this->username,
			'Password' => $this->password,
			'Function' => $this->functionNameGm
		), $this->queryParams);
		
		foreach ($params as $k => $v) {
			$l[] = urlencode($k).'='.urlencode($v);
		}
		
		$url = $this->apiUrl.'&'.implode('&',$l);
		
		// create safe URL to use in log
		$params = array_merge(array(
			'Username' => 'XXXXX',
			'Password' => 'XXXXX',
			'Function' => $this->functionNameGm
		), $this->queryParams);
		
		foreach ($params as $k => $v) {
			$a[] = urlencode($k).'='.urlencode($v);
		}
		
		$this->safeRequestUrl = $this->apiUrl.'&'.implode('&',$a);
		
		// you're being timed GraphicMail
		$start = microtime(true);
		
		$this->result = file_get_contents($url);
		
		// how long did it take?
		$this->responseTime = microtime(true) - $start;
	}
	
	private function createQueryParam($userInput, $graphicMailParamName, $extra = false)
	{
		// $userInput
		//  - cleaned version of user input (lowercase with _ & - removed)
		//  - may be NULL if $extra is string
		// $extra
		// - if bool indicates if param is required
		// - if string cannot be input by user, is a forced value
		
		if (!is_bool($extra)) {
			$this->queryParams[$graphicMailParamName] = $extra;
		}
		else {
			if (array_key_exists($userInput, $this->inputParams)) {
				$this->queryParams[$graphicMailParamName] = $this->inputParams[$userInput];
				unset($this->inputParams[$userInput]);
			}
			else if ($extra) {
				throw new Exception ('Required parameter not set for function '.$this->functionNameOrig.' ('.$userInput.')');
			}
		}
	}
	
	private function fromSnakeCase($string)
	{
		$format = $this->format;
		
		if ($format == 'snake_case') {
			return $string;
		}
		
		preg_match_all("!_?([a-z0-9]+)!", $string, $matches);
		
		$l = array();
		
		foreach ($matches[1] as $v) {
			$l[] = ucfirst($v);
		}
		
		$string = implode('',$l);
	
		if ($format == 'PascalCase') {
			return $string;
		}
		else if ($format == 'camelCase') {
			return lcfirst($string);
		}
		else {
			throw new Exception ('Unexpected format set');
		}
	}
	
	private function format($input)
	{
		if (!is_array($input)) {
			throw new Exception ('Input must be array for '.__METHOD__);
		}

		$input = $this->formatIterator($input);

		return $input;
	}
	
	private function formatIterator($a)
	{
		foreach ($a as $k => $v) {
			if (is_array($v)) {
				$v = $this->formatIterator($v);
			}
			$out[$this->fromSnakeCase($k)] = $v;
		}
		
		return $out;
	}

	private function inputLeftOver()
	{
		if (count($this->inputParams) > 0) {
			foreach ($this->inputParams as $k => $v) {
				$list[] = $k;
			}
			
			throw new Exception('Invalid parameters submitted for call '.$this->functionNameOrig.' ('.implode(', ', $list).')');
		}
	}
	
	private function processFunctionName($functionName)
	{
		$this->functionNameOrig = $functionName;
		
		$fn = $functionName;
				
		$fn = strtolower($fn);
		$fn = str_replace(array('_','-'), '', $fn);
		if (substr($fn, 0, 4) == 'post') {
			$fn = substr($fn, 3);
		}
		
		$this->functionName = $fn;
		
		// function folder
		$path = __DIR__.'/'.$this->classFolder.'/'.$fn.'/';
		
		if (!is_dir($path)) {
			throw new Exception ('Requested function ('.$this->functionNameOrig.') does not have a processing folder');
		}
		
		$this->functionFolder = $path;
		
		// function list
		$path = __DIR__.'/'.$this->classFolder.'/functionList.php';
		require $path;
		
		if (array_key_exists($fn, $functionList)) {
			$this->functionNameGm = $functionList[$fn];
		}
		else {
			throw new Exception ('Function ('.$fn.') not found in /'.$this->classFolder.'/functionList.php');
		}
	}
	
	private function processInput($input)
	{
		if ($input !== NULL && !is_array($input)) {
			throw new Exception('Function input must be an array');
		}

		if ($input !== NULL) {
			foreach ($input as $k => $v) {
				$k = str_replace(array('_','-'), '', $k);
				$k = strtolower($k);
				$this->inputParams[$k] = $v;
			}
		}
		
		// reset queryParams
		$this->queryParams = array();
	}
	
	public function call($functionName, $params = NULL)
	{
		$start = microtime(true);
		
		$this->checkUserPass();
		
		$this->processFunctionName($functionName);
		
		$this->processInput($params);
		
		if (file_exists($this->functionFolder.'params.php')) {
			require $this->functionFolder.'params.php';
		}
		
		$this->inputLeftOver();
		
		if ($this->debug == 'params') {
			return json_decode(json_encode($this->queryParams));
		}

		$this->contactGraphicMail();

		if ($this->debug == 'response') {
			return $this->result;
		}

		require $this->functionFolder.'handler.php';
		
		$time = microtime(true) - $start;
		
		$rtn['log']['api_url']         = $this->safeRequestUrl;
		$rtn['log']['function']        = $this->functionNameOrig;
		$rtn['log']['response_sample'] = substr($this->result,0,100);
		$rtn['log']['time']['api']     = $this->responseTime;
		$rtn['log']['time']['script']  = $time - $this->responseTime;
		$rtn['log']['time']['total']   = $time;
		
		$rtn = $this->format($rtn);
		
		return json_decode(json_encode($rtn));
	}
	
	public function setDebug($option)
	{
		if (in_array($option, array('params', 'response', 'off'))) {
			$this->debug = $option;
			return true;
		}
		else {
			throw new Exception ('Invalid return option requested ('.$option.')');
		}
	}

	public function setFormat($option)
	{
		$allow = array(
			'PascalCase',
			'camelCase',
			'snake_case'
		);
		
		if (in_array($option, $allow)) {
			$this->format = $option;
		}
		else {
			throw new Exception ('Invalid format submitted ('.$option.')');
		}
	}
	
	public function setUserPass($username, $password)
	{
		$this->setUsername($username);
		$this->setPassword($password);
	}
}
