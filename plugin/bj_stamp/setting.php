<?php
	if($this->form_submit()) {
		$opacity = core::gpc('opacity', 'P');
		$top = core::gpc('top', 'P');
		$right = core::gpc('right', 'P');
		$spacing = core::gpc('spacing', 'P');

		$config['opacity'] = $opacity;
		$config['top'] = $top;
		$config['right'] = $right;
		$config['spacing'] = $spacing;
		
		$this->kv->set('stamp_config', $config);
		
		$this->message('设置成功！', 1, $this->url('plugin-setting-dir-bj_stamp.htm'));
	}else{
		$config = $this->kv->get('stamp_config');
		
		!isset($config['opacity']) && $config['opacity'] = 100;
		!isset($config['top']) && $config['top'] = 0;
		!isset($config['right']) && $config['right'] = 50;
		!isset($config['spacing']) && $config['spacing'] = 0;

		$input['opacity'] = form::get_text('opacity', $config['opacity'], 50);
		$input['top'] = form::get_text('top', $config['top'], 50);
		$input['right'] = form::get_text('right', $config['right'], 50);
		$input['spacing'] = form::get_text('spacing', $config['spacing'], 50);

		$this->view->assign('dir', $dir);
		$this->view->assign('input', $input);

		$this->view->display('plugin_stamp_config.htm');
	}

?> 