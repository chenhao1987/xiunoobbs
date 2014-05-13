public function on_ping(){
	$pluginlist = core::get_plugins($this->conf);
	$data = array();
	$ck = 'checked="checked"';
	foreach($pluginlist as $dir=>&$pconf) {
		if(!isset($pconf['pingid']))
			continue;
		if(strcasecmp($pconf['pingid'],'krabs')==0)
			
			if($pconf['status']=='1'){
				$this->view->assign('status',$ck);
			}
			if($pconf['thread']=='1'){
				$this->view->assign('thread',$ck);
			}
			if($pconf['portread']=='1'){
				$this->view->assign('portread',$ck);
			}
				
			
			$data = $pconf;
	}
	$this->view->assign('data',$data);
	$this->view->display("admin_ping.htm");
}

private function getconfarrayping(){
	$pluginlist = core::get_plugins($this->conf);
	$data = array();
	foreach($pluginlist as $dir=>&$pconf) {
		if(!isset($pconf['pingid']))
			continue;
		if(strcasecmp($pconf['pingid'],'pingid')==0)
			return $pconf;
	}
}
public function on_modipingconf(){
//checkbox 0登录 1注册 3发表文章 4回复文章 5全局开启
$data = $this->getconfarrayping();
if(isset($_POST['status']))
	$data['status'] = 1;
else
	$data['status'] = 0;
	
if(isset($_POST['thread']))
	$data['thread'] = 1;
else
	$data['thread'] = 0;
	
if(isset($_POST['portread']))
	$data['portread'] = 1;
else
	$data['portread'] = 0;
	

	
if(isset($_POST['title'])) //验证码组合
	$data['title'] = $_POST['title'];
if(isset($_POST['domain']))
	$data['domain'] = $_POST['domain'];
if(isset($_POST['rss']))
	$data['rss'] = $_POST['rss'];

	

$html = <<<EOD
<?php
 
return     array (
	'pingid'=>'krabs',
    'name'=>'Xiuno 百度ping',      
    'brief'=>'ing是基于XML_RPC标准协议的更新通告服务，用于博客把内容更新快速通知给百度，以便百度及时进行抓取和更新,常用于SEO者使用,ping通告会自动采取,在用户评论和用户发表文章开始更新通告',
    'version'=>'1.1',                 
    'bbs_version'=>'2.1.0',          
	'Author'=>'krabs',
	
	'status'=>'%s',
	'thread'=>'%s',
	'portread'=>'%s',
	'title'=>'%s',
	'domain'=>'%s',
	'rss'=>'%s',
	'baidu'=>'http://ping.baidu.com/ping/RPC2',
	
);
 
?>
EOD;
$html = sprintf($html,
$data['status'],
$data['thread'],
$data['portread'],
$data['title'],
$data['domain'],
$data['rss']
);
if(file_put_contents('../plugin/xn_baidu_ping/conf.php',$html))
	echo '更新成功,请在后台刷新缓存!';
	else
	echo '更新失败,可能是无法写入文件到目录,请检查目录权限';

}