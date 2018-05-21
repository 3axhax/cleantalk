<?php

	$bannersNew = new BannersNew();
	
	if($bannersNew->ckeckMainBanner())
		$this->renderPartial('banner_main', array());
	else
	{
		$actionItems = $bannersNew->getActionItems();
		
		if(count($actionItems) > 0)
			$this->renderPartial('action_items', array('actionItems' => $actionItems));
	}
	
	$bannersNew->getSmallMainBanners();

	foreach($groups as $entity=>$group)
	{
			if (count($group['items']) == 0)                        continue;
			$this->renderPartial('/entity/_entity_index',
				array('group' => $group['items'], 'entity' => $group['entity'])) ;
	}
?>