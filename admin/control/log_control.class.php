<?php

/*
 * Copyright (C) xiuno.com
 */

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

include BBS_PATH.'admin/control/admin_control.class.php';

class log_control extends admin_control {
	
	function __construct(&$conf) {
		parent::__construct($conf);
		$this->check_admin_group();
		
		if(IN_SAE) {
			$this->message('对不起，SAE 版本不支持日志功能！');
		}
	}
	
	public function on_phperror() {
		$this->_title[] = 'PHP错误日志';
		$this->_nav[] = '<a href="./">PHP错误日志</a>';
		$page = misc::page();
		$logfile = $this->conf['log_path'].'phperror.php';
		if(!is_file($logfile)) file_put_contents($logfile, '');
		
		$filesize = filesize($logfile);
		$totalpage = ceil($filesize / 50000);
		$pages = misc::pages('?log-phperror.htm', $totalpage * 20, $page, 20);
		
		$loglist = $this->get_loglist($logfile, $page);
		
		$logtype = 'phperror';
		$this->view->assign('logtype', $logtype);
		$this->view->assign('logfile', $logfile);
		$this->view->assign('filesize', $filesize);
		$this->view->assign('loglist', $loglist);
		$this->view->assign('pages', $pages);
		
		// hook admin_log_phperror_view_before.php
		
		$this->view->display('log_list.htm');
	}
	
	public function on_login() {
		$this->_title[] = '错误登录日志';
		$this->_nav[] = '<a href="./">错误登录日志</a>';
		$page = misc::page();
		$logfile = $this->conf['log_path'].'login.php';
		if(!is_file($logfile)) file_put_contents($logfile, '');
		
		$filesize = filesize($logfile);
		$totalpage = ceil($filesize / 50000);
		$pages = misc::pages('?log-login.htm', $totalpage * 20, $page, 20);
		
		$loglist = $this->get_loglist($logfile, $page);
		
		$logtype = 'login';
		$this->view->assign('logtype', $logtype);
		$this->view->assign('logfile', $logfile);
		$this->view->assign('filesize', $filesize);
		$this->view->assign('loglist', $loglist);
		$this->view->assign('pages', $pages);
		
		// hook admin_log_login_view_before.php
		
		$this->view->display('log_list.htm');
	}
	
	public function on_cron() {
		$this->_title[] = '计划任务日志';
		$this->_nav[] = '<a href="./">计划任务日志</a>';
		$page = misc::page();
		$logfile = $this->conf['log_path'].'cron.php';
		if(!is_file($logfile)) file_put_contents($logfile, '');
		
		$filesize = filesize($logfile);
		$totalpage = ceil($filesize / 50000);
		$pages = misc::pages('?log-cron.htm', $totalpage * 20, $page, 20);
		
		$loglist = $this->get_loglist($logfile, $page);
		
		$logtype = 'cron';
		$this->view->assign('logtype', $logtype);
		$this->view->assign('logfile', $logfile);
		$this->view->assign('filesize', $filesize);
		$this->view->assign('loglist', $loglist);
		$this->view->assign('pages', $pages);
		
		// hook admin_log_cron_view_before.php
		
		$this->view->display('log_list.htm');
	}
	
	// 清空日志文件
	public function on_truncate() {
		$file = core::gpc('file');
		!in_array($file, array('phperror', 'login', 'cron')) && $file = 'login';
		$logfile = $this->conf['log_path'].$file.'.php';
		is_file($logfile) && unlink($logfile) && touch($logfile);
		
		// hook admin_truncate_after.php
		
		$this->message('日志文件'.$logfile.'清空完毕。', 1, "?log-$file.htm");
	}
	
	// 每页50K！ 可能会漏掉翻页中间的半条！
	private function get_loglist($logfile, $page) {
		$offset = $page * 50000;
		$filesize = filesize($logfile);
		$offset = max(0, $filesize - $offset);
		$fp = fopen($logfile, 'r');
		fseek($fp, $offset);
		$s = fread($fp, 50000);
		$arr = explode("\r\n", $s);
		$return = array();
		foreach($arr as $v) {
			$arr2 = explode("\t", $v);
			if(isset($arr2[4])) {
				$arr2[3] = htmlspecialchars($arr2[3]);
				$arr2[4] = htmlspecialchars($arr2[4]);
				$return[] = $arr2;
			}
		}
		krsort($return);
		return $return;
	}
	
	//hook admin_log_control_after.php
	
}

?>