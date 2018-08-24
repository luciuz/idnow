# luciuz/idnow

IDnow API for everyone!

### Examples
Test
```php
// init
$idNow = new IdNow(
    'companyid',
    'API-KEY-TOKEN',
    'https://gateway.test.idnow.de',
    'v1',
    'https://go.test.idnow.de'
);

// create the ident
$transactionId = 'rSRS6BcacTIm4hM94NleLM55x5jamuRI';
$params = [
    'birthday' => '1989-09-09',
    'firstname' => 'X-AUTOTEST-HAPPYPATH',
    'lastname' => 'Lastname',
    'gender' => 'MALE',
    'nationality' => 'RU',
    'mobilephone' => '+79123456789',
];
$result = $idNow->create($transactionId, $params);

// init test IdNowApi
$idNowTest = new IdNowApi(
    'companyid',
    'API-KEY-TOKEN',
    'https://api.test.idnow.de',
    'v1',
    ''
);

// start
$result = $idNowTest->do("{$idNowTest->companyId}/identifications/$transactionId/start", []);

// request video chat
$result = $idNowTest->do("{$idNowTest->companyId}/identifications/$transactionId/requestVideoChat", []);

// now we are ready to receive a webhook
```

Production
```php
// init
$idNow = new IdNow(
    'companyid',
    'API-KEY-TOKEN',
    'https://gateway.idnow.de',
    'v1',
    'https://go.idnow.de'
);

// create the ident
$transactionId = 'rSRS6BcacTIm4hM94NleLM55x5jamuRJ'
$params = [
    'birthday' => '1989-09-09',
    'firstname' => 'Firstname',
    'lastname' => 'Lastname',
    'gender' => 'MALE',
    'nationality' => 'RU',
    'mobilephone' => '+79123456789',
];
$result = $idNow->create($transactionId, $params);

// get estimated waiting time 
$estimatedWaitingTime = $idNow->estimatedWaitingTime();

// get ident url
$url = $idNow->getUrl($transactionId);

// open url in the iframe
// [identification processs]
// get result redirect
// now we are ready to receive a webhook
```

Bonus
```php
// download results as ZIP
$result = $idNow->download($href, $dir);

// retrieve results as JSON
$result = $idNow->retrieve($transactionId);
```