<div class="filament-hidden">

![Laravel ActiveCampaign](https://raw.githubusercontent.com/jeffersongoncalves/laravel-activecampaign/main/art/jeffersongoncalves-laravel-activecampaign.png)

</div>

# Laravel ActiveCampaign

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-activecampaign.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-activecampaign)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-activecampaign/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/laravel-activecampaign/actions?query=workflow%3ATests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-activecampaign/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/laravel-activecampaign/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-activecampaign.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-activecampaign)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/laravel-activecampaign.svg?style=flat-square)](LICENSE.md)

A PHP/Laravel client for the [ActiveCampaign](https://www.activecampaign.com/) REST API v3. Covers contacts, lists, campaigns, deals (CRM), automations, tags, pipelines, webhooks and users through a simple, typed API built on Laravel's `Http` client.

## Features

- Contacts: list, get, create, update, delete, sync (upsert by email)
- Lists: list, get, create, delete, subscribe/unsubscribe a contact
- Campaigns: list, get
- Deals (CRM): list, get, create, update, delete
- Automations: list, get, add a contact to an automation
- Tags: list, get, create, delete, add/remove on a contact
- Pipelines: list, get
- Webhooks: list, get, create, delete
- Users: current user (`me`), list
- Throws `ActiveCampaignException` (with the original API error body) on any non-2xx response
- Throws `InvalidArgumentException` before hitting the API when a required field is missing

## Installation

You can install the package via composer:

```bash
composer require jeffersongoncalves/laravel-activecampaign
```

Publish the config file:

```bash
php artisan vendor:publish --tag=activecampaign-config
```

Set your ActiveCampaign credentials in `.env`:

```env
ACTIVECAMPAIGN_API_URL=https://youraccountname.api-us1.com
ACTIVECAMPAIGN_API_KEY=your-api-key
```

Both values are found under **Settings > Developer** in your ActiveCampaign account.

## Configuration

```php
// config/activecampaign.php
return [
    'api_url' => env('ACTIVECAMPAIGN_API_URL', ''),
    'api_key' => env('ACTIVECAMPAIGN_API_KEY', ''),
    'default_limit' => env('ACTIVECAMPAIGN_DEFAULT_LIMIT', 20),
];
```

## Usage

The package is resolved via the `ActiveCampaign` facade or by injecting `JeffersonGoncalves\ActiveCampaign\ActiveCampaign`. Each resource is exposed as a method returning a dedicated resource class.

### Contacts

```php
use JeffersonGoncalves\ActiveCampaign\Facades\ActiveCampaign;

// List (supports email, search, listid, status, limit, offset filters)
$contacts = ActiveCampaign::contacts()->list(['email' => 'jane@example.com']);

$contact = ActiveCampaign::contacts()->get(1);

$contact = ActiveCampaign::contacts()->create([
    'email' => 'jane@example.com',
    'firstName' => 'Jane',
    'lastName' => 'Doe',
]);

ActiveCampaign::contacts()->update(1, ['firstName' => 'Janet']);

ActiveCampaign::contacts()->delete(1);

// Upsert by email
ActiveCampaign::contacts()->sync(['email' => 'jane@example.com', 'firstName' => 'Jane']);
```

### Lists

```php
$lists = ActiveCampaign::lists()->list();

$list = ActiveCampaign::lists()->create(['name' => 'Newsletter']);

ActiveCampaign::lists()->subscribe(listId: 1, contactId: 42);
ActiveCampaign::lists()->unsubscribe(listId: 1, contactId: 42);

ActiveCampaign::lists()->delete(1);
```

### Deals (CRM)

```php
$deals = ActiveCampaign::deals()->list(['search' => 'Big Sale', 'stage' => 3]);

$deal = ActiveCampaign::deals()->create([
    'title' => 'Big Sale',
    'value' => 10000, // in cents
    'currency' => 'usd',
    'group' => 1, // pipeline id
    'stage' => 3,
    'contact' => 42,
]);

ActiveCampaign::deals()->update($deal['deal']['id'], ['stage' => 5]);

ActiveCampaign::deals()->delete($deal['deal']['id']);
```

### Tags

```php
$tags = ActiveCampaign::tags()->list(['search' => 'vip']);

$tag = ActiveCampaign::tags()->create('vip');

ActiveCampaign::tags()->addToContact(tagId: $tag['tag']['id'], contactId: 42);
ActiveCampaign::tags()->removeFromContact($contactTagId);

ActiveCampaign::tags()->delete($tag['tag']['id']);
```

### Campaigns, Automations, Pipelines, Webhooks and Users

```php
ActiveCampaign::campaigns()->list();
ActiveCampaign::campaigns()->get(1);

ActiveCampaign::automations()->list();
ActiveCampaign::automations()->addContact(automationId: 1, contactId: 42);

ActiveCampaign::pipelines()->list();

ActiveCampaign::webhooks()->create(
    name: 'CRM Sync',
    url: 'https://example.com/hooks/activecampaign',
    events: ['subscribe', 'unsubscribe'],
);

ActiveCampaign::users()->me();
ActiveCampaign::users()->list();
```

### Error handling

Any non-2xx API response throws `JeffersonGoncalves\ActiveCampaign\Exceptions\ActiveCampaignException`, which exposes the decoded error body:

```php
use JeffersonGoncalves\ActiveCampaign\Exceptions\ActiveCampaignException;

try {
    ActiveCampaign::contacts()->get(999999);
} catch (ActiveCampaignException $e) {
    logger()->error($e->getMessage(), $e->errorBody());
}
```

Missing required fields (e.g. `email` on `contacts()->create()`, `name` on `lists()->create()`) throw `InvalidArgumentException` before any HTTP call is made.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jefferson Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
