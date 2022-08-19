# arjbank Package

#### Package Payment Gateway With Alrajhi Bank with laravel

#

```composer
 composer require mfrouh/arjbank
```

## Package have 2 method payment

1- Bank Hosted

2- Merchant

1- Bank Hosted

```php

use MFrouh\ArjBank\Facades\ArjBank;

 ArjBank::bankHostedPayment($amount, 'response-url', 'error-url');

```

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
