<?php

!defined('FRAMEWORK_PATH') && exit('FRAMEWORK_PATH not defined.');

$error = $input = array();

if(!$this->form_submit()) {
	$qqlogin = $this->kv->get('qqlogin');
	
	!isset($qqlogin['enable']) && $qqlogin['enable'] = 0;
	!isset($qqlogin['meta']) && $qqlogin['meta'] = '';
	!isset($qqlogin['appid']) && $qqlogin['appid'] = '';
	!isset($qqlogin['appkey']) && $qqlogin['appkey'] = '';
	$input['enable'] = form::get_radio_yes_no('enable', $qqlogin['enable']);
	$input['meta'] = form::get_text('meta', htmlspecialchars($qqlogin['meta']), 300);
	$input['appid'] = form::get_text('appid', $qqlogin['appid'], 300);
	$input['appkey'] = form::get_text('appkey', $qqlogin['appkey'], 300);
	$this->view->assign('dir', $dir);
	$this->view->assign('input', $input);
	$this->view->display('plugin_xn_qq_login.htm');
} else {
	
	$enable = core::gpc('enable', 'R');
	$meta = core::gpc('meta', 'R');
	$appid = core::gpc('appid', 'R');
	$appkey = core::gpc('appkey', 'R');
	
	if($meta) {
		if(!preg_match('#<meta[^<>]+/>#is', $meta)) {
			$this->message('meta标签格式不正确！');
		}
		$file = BBS_PATH.'plugin/xn_qq_login/header_css_before.htm';
		if(!file_put_contents($file, $meta)) {
			$this->message('写入文件 plugin/xn_qq_login/header_css_before.htm 失败，请检查文件是否可写<br />或者手工编辑此文件内容为：'.htmlspecialchars($meta));
		}
		// 删除 tmp 下的缓存文件
		misc::rmdir($this->conf['tmp_path'], 1);
	}
	
	$this->kv->set('qqlogin', array('enable'=>$enable, 'meta'=>$meta, 'appid'=>$appid, 'appkey'=>$appkey));
	$this->kv->xset('qqlogin_enable', $enable);
	$this->runtime->xset('qqlogin_enable', $enable);
	
	// 如果是 mysql 新建表
	
	$this->message('设置成功！', 1, $this->url('plugin-setting-dir-xn_qq_login.htm'));
	
}

?>