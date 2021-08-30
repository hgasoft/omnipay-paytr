<?php

namespace Omnipay\Paytr;

use Omnipay\Common\AbstractGateway;
use Omnipay\Paytr\Traits\GettersSettersTrait;

/**
 * Paytr Gateway
 * (c) Tolga Can Günel
 * 2015, mobius.studio
 * http://www.github.com/tcgunel/omnipay-ipara
 * @method \Omnipay\Common\Message\NotificationInterface acceptNotification(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = [])
 */
class Gateway extends AbstractGateway
{
	use GettersSettersTrait;

	public function getName(): string
	{
		return 'Paytr';
	}

	public function getDefaultParameters()
	{
		return [
			"clientIp" => "127.0.0.1",
		];
	}
}
