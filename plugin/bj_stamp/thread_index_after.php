$config = $this->kv->get('stamp_config');

!isset($config['opacity']) && $config['opacity'] = 100;
!isset($config['top']) && $config['top'] = 0;
!isset($config['right']) && $config['right'] = 50;
!isset($config['spacing']) && $config['spacing'] = 0;

$this->view->assign('_stamp', $config);