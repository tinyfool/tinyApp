<?php
	
$g_pathinfo=null;
	
function myAutoload($class_name) {
	
	global $g_pathinfo;
	if(substr($class_name,-10)=='Controller')
	{
		require_once $g_pathinfo['controllers'] . '/' . $class_name . '.php';
	}else if(substr($class_name,-5)=='Model')
	{
		require_once $g_pathinfo['models'] . '/' . $class_name . '.php';
	}
	

}
	
class tinyApp_Application {
	
	protected $_requestUri=null;
	protected $_controller=null;
	protected $_pathinfo=null;
	
	public function __construct($pathinfo) {

		global $g_pathinfo;
		$this->_pathinfo=$pathinfo;
		$g_pathinfo=$this->_pathinfo;
    spl_autoload_register("myAutoload");
		$this->SetController();
	}
	
	public function dispatch() {
		
		session_start();
		$controller=$this->_controller;
		$name=$controller['name'];
		$action=$controller['action'];
		$className=ucwords($name.'Controller');
		$methodName=ucwords($action.'Action');
		if(!file_exists($this->_pathinfo['controllers'] . '/' . $className . '.php'))
		{
			die($this->out_error("404"));
		}
		$runner=new $className($this->_pathinfo,$controller);
		call_user_func(array($runner,$methodName));
	}
	
	private function out_error($error)
	{
		
		$controller=$this->_controller;
		$name=$controller['name'];
		$action=$controller['action'];
		$className=ucwords($name.'Controller');
		$methodName=ucwords($action.'Action');

		switch($error)
		{
			case "404":
				header("HTTP/1.1 404 Not Found");
				echo '<html>
						<head>
							<meta http-equiv="Content-type" content="text/html; charset=utf-8">
							<title>404 您访问的页面并不存在</title>
						</head>
						<body>
							<h1>404</h1><p>您访问的页面并不存在</p>
							<script type="text/javascript">
							  var GOOG_FIXURL_LANG = \'zh-CN\';
							  var GOOG_FIXURL_SITE = \'http://www.codechina.org\'
							</script>
							<script type="text/javascript"
							  src="http://linkhelp.clients.google.com/tbproxy/lh/wm/fixurl.js">
							</script>
						';
				echo "<!--";
				echo $this->_pathinfo['controllers'] . '/' . $className . '.php';
				echo "-->";
				echo '</body>
					</html>';
				break;
		}
	
	}
	
	private function SetController() {
		
		if($this->_controller===null){
			
			$urlArray = parse_url($this->SetRequestUri());
			$uri = $urlArray["path"];
			$uriparts = explode("/",$uri);
			$langs = array("zh","en","ja","ko","hi","es","pt");
			if(in_array($uriparts[1],$langs))
			{
				array_shift($uriparts);
			}
			if(!isset($uriparts[1])) {
				$name="index";
				$action="index";
			}
			elseif(!isset($uriparts[2])) {
				$name=trim(strtolower($uriparts[1]));
				if($name=="")
					$name="index";
				$action="index";
			}
			else {
				$name=trim(strtolower($uriparts[1]));
				if($name=="")
					$name="index";
				$action=trim(strtolower($uriparts[2]));
				if($action=="")
					$action="index";
			}
      if($name=="index")
          $name = "home";
			$this->_controller=array('name'=>$name,'action'=>$action,'uri'=>$uri);
		}
		return $this->_controller;
	}
	
	private function SetRequestUri() {
	
		if($this->_requestUri===null) {
			if (isset($_SERVER['HTTP_X_REWRITE_URL']))
				$this->_requestUri=$_SERVER['HTTP_X_REWRITE_URL'];
			elseif (isset($_SERVER['REQUEST_URI']))
				$this->_requestUri=$_SERVER['REQUEST_URI'];
			elseif (isset($_SERVER['ORIG_PATH_INFO']))
				$this->_requestUri=$_SERVER['ORIG_PATH_INFO'];
			else
				$this->_requestUri='/';
		}
		return $this->_requestUri;
	
	}
	
}
