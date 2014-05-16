<?php
!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

$error = $input = array();

if(!$this->form_submit()) {
	$do = core::gpc('do', 'R');
	$qiniu = $this->kv->get('qiniu');
	
	!isset($qiniu['enable']) && $qiniu['enable'] = 1;
	!isset($qiniu['mode']) && $qiniu['mode'] = 0;
	!isset($qiniu['access']) && $qiniu['access'] = '';
	!isset($qiniu['key']) && $qiniu['key'] = '';
	!isset($qiniu['name']) && $qiniu['name'] = '';
	!isset($qiniu['url']) && $qiniu['url'] = '';
	
	$input['enable'] = form::get_radio_yes_no('enable', $qiniu['enable']);
	$input['mode'] = form::get_radio('mode', array('本地','七牛云 (<a href="https://portal.qiniu.com/signup?code=3lp25xlpdlv82" target="_blank">申请七牛</a>)','七牛(主)+本地(备)','本地(主)+七牛(备)'), $qiniu['mode']);
	$input['access'] = form::get_text('access', $qiniu['access'], 300);
	$input['key'] = form::get_text('key', $qiniu['key'], 300);
	$input['name'] = form::get_text('name', $qiniu['name'], 300);
	$input['url'] = form::get_text('url', $qiniu['url'], 300);
	
	$this->view->assign('dir', $dir);
	$this->view->assign('input', $input);
	$this->view->display('qiniu_setting.htm');
} else {
	$enable = core::gpc('enable', 'R');
	$mode = core::gpc('mode', 'R');
	$access = core::gpc('access', 'R');
	$key = core::gpc('key', 'R');
	$name = core::gpc('name', 'R');
	$url = core::gpc('url', 'R');
	
	$this->kv->set('qiniu', array('enable'=>$enable, 'mode'=>$mode, 'access'=>$access, 'key'=>$key, 'name'=>$name, 'url'=>$url));
	$this->message('设置成功！', 1, $this->url('plugin-setting-dir-xn_qiniu.htm'));
}
?>
