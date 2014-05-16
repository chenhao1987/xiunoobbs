<?php
$qiniu = $this->kv->get('qiniu');
/*
模式介绍
0,本地模式 (不使用七牛)
1,七牛模式 (本地不保存)
2,七牛主模式 (七牛为源,本地有备份)
3,本地主模式 (本地为源,七牛为备份)
*/

if(isset($qiniu) && is_array($qiniu) && $qiniu['enable'] == 1){
	require_once($this->conf['plugin_path']."xn_qiniu/sdk/io.php");
	require_once($this->conf['plugin_path']."xn_qiniu/sdk/rs.php");
	if($qiniu['mode'] != 0){
		//开始上传到七牛
		$_tempfile = $this->conf['upload_path'].'attach/'.$_file;
		Qiniu_setKeys($qiniu['access'], $qiniu['key']);
		$putPolicy = new Qiniu_RS_PutPolicy($qiniu['name']);
		$upToken = $putPolicy->Token(null);
		$putExtra = new Qiniu_PutExtra();
		$putExtra->Crc32 = 1;
		list($ret, $err) = Qiniu_PutFile($upToken, $_file, $_tempfile, $putExtra);
		if ($err == null) {
			//上传成功
			if($qiniu['mode'] == 1 || $qiniu['mode'] == 2){
				$uploadurl = $qiniu['url'];
			}
			if($qiniu['mode'] == 1){
				is_file($_tempfile) && unlink($_tempfile);
			}
		}
	}
}