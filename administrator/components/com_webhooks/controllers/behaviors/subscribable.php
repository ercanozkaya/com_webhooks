<?php

class ComWebhooksControllerBehaviorSubscribable extends KControllerBehaviorAbstract
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

		$this->registerCallback(array('after.add', 'after.edit', 'after.delete'), array($this, 'notify'));
    }

	protected function _actionSubscribe(KCommandContext $context)
	{
		$controller = KFactory::tmp('admin::com.webhooks.controller.webhooks');
		return $controller->execute('add', $context);
	}

	public function notify(KCommandContext $context)
	{
		$controller = KFactory::tmp('admin::com.webhooks.controller.webhooks');

		$browse = clone $context;
		$items = $controller->execute('browse', $browse);

		if (!empty($items)) {
			$results = $context->result;
			$response = array();

			if ($results instanceof KDatabaseRowInterface) {
				$results = array($results);
			}

			foreach ($results as $row) {
				$response[] = $row->toArray();
			}
			$response = $response[0];
			$response = json_encode($response);

			foreach ($items as $item) {
				$this->_request($item->url, $response, $context->action);
			}
		}

		return true;
	}

	protected function _request($url, $result, $action)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $result);
		if ($action == 'delete') {
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: DELETE'));
		}

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_MAXREDIRS,		 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 		 20);

		$response = curl_exec($ch);

		if (curl_errno($ch)) {
			throw new KControllerException('Curl Error: '.curl_error($ch));
		}

		curl_close($ch);

		return $response;
	}
}