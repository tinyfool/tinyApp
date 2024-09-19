<?php
	
class tinyApp_Controller {
	
	protected $_view=null;
	protected $_mainContent=null;
	protected $_pathinfo=null;
	protected $_layout=null;
	protected $_controller;
	public    $_useSession=true;
  
	public function __construct($pathinfo,$controller){
		
		$this->_pathinfo=$pathinfo;
		$this->_controller=$controller;
		
		$this->_view=new Smarty();
		$this->_view->template_dir=$this->_pathinfo['layouts'];
		$this->_view->compile_dir=$this->_pathinfo['compile'];
		$this->_view->cache_dir=$this->_pathinfo['cache'];

		$this->_mainContent=new Smarty();
		$this->_mainContent->template_dir=$this->_pathinfo['views'];
		$this->_mainContent->compile_dir=$this->_pathinfo['compile'];
		$this->_mainContent->cache_dir=$this->_pathinfo['cache'];
		$this->__uriparts=explode("/",$this->_controller['uri']);
    $langsArray = array("zh","en","ja","ko","hi","es","pt");
		$this->_lang = "zh";
		if(in_array($this->__uriparts[1],$langsArray))
		{
			$this->_lang = $this->__uriparts[1];
			array_shift($this->__uriparts);
		}
	}
  
  public function intVal($index,$default=-1) {

    if(empty($this->__uriparts[$index]))
      if($default!=-1)
        return $default;
      else
        return 0;
    $ret = intval($this->__uriparts[$index]);
    if($ret==0)
      if($default!=-1)
        return $default;
    return $ret;
  }
  
  public function strVal($index,$default="") {
    
    if(empty($this->__uriparts[$index]))
      return $default;
    else
      return $this->__uriparts[$index];
  }
  
  public function makePage($dir,$file,$array){
  
  	$smarty = new Smarty();
	  $smarty->debugging = true;
  	$smarty->template_dir = $this->_pathinfo['views'];
  	$smarty->compile_dir = $this->_pathinfo['compile'];
	  foreach($array as $key=>$value) {
		
		  $smarty->assign($key,$value);
	  }
  	return $smarty->fetch("$dir/$file.html");	
  }
	
	public function display() {
		$this->_view->display($this->_layout . '.html');
	}
}