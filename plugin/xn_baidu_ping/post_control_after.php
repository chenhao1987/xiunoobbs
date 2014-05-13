private function getconfarrayping(){
	$pluginlist = core::get_plugins($this->conf);
	$data = array();
	foreach($pluginlist as $dir=>&$pconf) {
		if(!isset($pconf['pingid']))
			continue;
		if(strcasecmp($pconf['pingid'],'krabs')==0)
			return $pconf;
	}
}
public function addPing($url){
	
	$data = $this->getconfarrayping();

	$baiduXML = "<?xml version=\"1.0\" encoding=\"gb2312\"?>
	<methodCall>
	<methodName>weblogUpdates.extendedPing</methodName>
	<params>
	<param><value><string>%s</string></value></param>
	<param><value><string>%s</string></value></param>
	<param><value><string>%s</string></value></param>
	<param><value><string>%s</string></value></param>
	</params>
	</methodCall>";
	
	$baiduXML = sprintf($baiduXML,$data['title'],$data['domain'],$url,$data['rss']);

	$ch = curl_init();
	$headers = array(
	"POST http://ping.baidu.com/ping/RPC2 HTTP/1.0",
	"Content-type: text/xml; charset=\"gb2312\"",
	"Accept: text/xml",
	"Content-length: ".strlen($baiduXML)
	);
	curl_setopt($ch, CURLOPT_URL, 'http://ping.baidu.com/ping/RPC2');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $baiduXML);
	$res = curl_exec ($ch);
	curl_close ($ch);
	if ( strpos($res, "<int>0</int>") )
		return true;
	else
		return false;
}