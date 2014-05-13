$data = $this->getconfarrayping();
if($data['status']=='1'){
	if($data['portread']=='1'){
		if($this->addPing($this->conf['app_url'].'thread-index-fid-'.$fid.'-tid-'.$tid.'.htm'))
			echo '';
			else
			echo '';
	}
}