<?php
	
class tinyApp_Controller {
	
	protected $_view=null;
	protected $_mainContent=null;
	protected $_pathinfo=null;
	protected $_layout=null;
	protected $_controller;
	public $_useSession=true;
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
	}
	
	public function display() {
		$this->_view->display($this->_layout . '.html');
	}
}