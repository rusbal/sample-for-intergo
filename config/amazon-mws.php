<?php

$testStore = [];
$testStore['merchantId'] = 'T_M_GOOD_83835495';
$testStore['marketplaceId'] = 'ATVPDKIKX0DER';
$testStore['keyId'] = 'key';
$testStore['secretKey'] = 'secret';

$invalidStore = [];
$invalidStore['merchantId'] = 'A124QSH1IWDCW2';
$invalidStore['marketplaceId'] = 'ATVPDKIKX0DER';
$invalidStore['keyId'] = 'AKIAIV6SV2IKZL4RFMKQ';
$invalidStore['secretKey'] = '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';

return [
	'store' => [
		'gregStore' => [
			'merchantId' => 'A124QSH1IWDCW2',
			'marketplaceId' => 'ATVPDKIKX0DER',
			'keyId' => 'AKIAITF5AZ2VRC4WXBGA',
			'secretKey' => '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',
        ],
        'testStore' => $testStore,
		'invalidStore' => $invalidStore,
	],

	// Default service URL
	'AMAZON_SERVICE_URL' => 'https://mws.amazonservices.com/',

	'muteLog' => false
];
