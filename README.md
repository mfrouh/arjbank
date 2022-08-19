# arjbank Package

#### Package Payment Gateway With Alrajhi Bank with laravel

#

```bash
 composer require mfrouh/arjbank
```

## Package have 2 method payment

1- Bank Hosted

2- Merchant

#

1- Bank Hosted

```php

use MFrouh\ArjBank\Facades\ArjBank;

 ArjBank::bankHostedPayment($amount, 'response-url', 'error-url');

```

## In Bank Hosted Response Will be

>  Success : ["status" => '1', "url" => $url];
#
### Using Url In

```html
<iframe src="{{$url}}" style="width: 100%; height: 100%"></iframe>
```
#

>  Fail    : ["status" => '2', "message" => $errorMessage];
#

2- Merchant

```php

use MFrouh\ArjBank\Facades\ArjBank;

 $card_details = [
     "expYear" => (string) request('expiry_year'),
     "expMonth" => (string) request('expiry_month'),
     "card_holder" => (string) request('card_holder'),
     "cvv2" => (string) request('cvv'),
     "cardNo" => (string) request('card_number'),
     "cardType" => "C",
 ];

 ArjBank::merchantPayment($card_details , $amount, 'response-url', 'error-url');

```
#

## In Merchant Response Will be

#
>  Success : ["status" => '1', "url" => $url]; 


### Using Url In Redirect To Alrajhi Bank Page For Otp
#


>  Fail    : ["status" => '2', "message" => $errorMessage];
#
## Get Result From trandata from Response Url

```php

use MFrouh\ArjBank\Facades\ArjBank;

 ArjBank::result($trandata);

```

## .env File

```env

ARJ_MODE="live" // or "test"
ARJ_TRANPORTAL_ID=""
ARJ_TRANPORTAL_PASSWORD=""
ARJ_RESOURCE_KEY=""

```