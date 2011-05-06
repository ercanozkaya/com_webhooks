<?php

class ComWebhooksDatabaseTableWebhooks extends KDatabaseTableAbstract
{
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'behaviors' => array('creatable', 'modifiable', 'identifiable')
		));

		parent::_initialize($config);
	}
}