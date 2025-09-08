# Laravel Subscribe

A simple and flexible package for Laravel to manage email subscriptions. It can easily integrate into any project to
collect and manage a list of subscribers.

## Features

- **API endpoint** for creating/updating subscribers.
- **Flexible configuration** through published configuration file.
- **Event system** (`SubscriberCreated`) for integration with other parts of your application (for example, to send
  welcome emails).
- **Published templates** for emails that can be easily customized.
- **Validation** for incoming requests.
- Uses **Soft Deletes** to save data about subscribers.

## Installation

Instructions to install the klunker/laravel-subscribe package from Git
This instruction will help you install and configure the package for managing email subscriptions
in a new or existing Laravel project.

### Step 1: Set up `composer.json` for the project

Since the package is not yet published on Packagist (or is a private one), Composer needs to know where to look for
it.

1. Open the `composer.json` file in the root of your Laravel project.
2. Add the `repositories` section. This section tells Composer to look for packages not only on Packagist, but also in
   the specified Git repository.
   Add the following block to your `composer.json` file (for example, after the `config` section):

```json
-----
"repositories": [
{
"type": "vcs",
"url": "git@github.com:klunker/laravel-subscribe.git"
}
]
-----
```

#### • The `"type"` field indicates "Version Control System" (VCS).

#### • The `"url"` field is the URL of your repository. You can use SSH (as in the example), or HTTPS.

### Step 2: Package Installation

Now that Composer knows where to look, run the standard require command in the terminal.
Specify version 0.1.0 or newer.

```Shell Script
composer require klunker/laravel-subscribe:"^0.1.0"
```

Composer will connect to your GitHub repository, find the 1.0.0 tag, download the package code,
and install it in the vendor directory.

### Step 3: Running the migration

The package comes with a migration for creating the subscribers table. To create this table in your database,
run the following command:

```Shell Script
php artisan migrate
```

After this, a subscribers table will be created in your database.

## (Optionally) Package customization

If you want to change the default settings or the appearance of the template, you can publish the package resources.

#### 1.Publishing the configuration file: Allows you to change, for example, the table name.

```Shell Script
php artisan vendor:publish --provider="Klunker\LaravelSubscribe\SubscribeServiceProvider" --tag="config"
```

The configuration file will be created in config/subscribe.php.

#### 2. Publishing email templates:

Allows you to change, for example, the HTML/text of the welcome email.

```Shell Script
php artisan vendor:publish --provider="Klunker\LaravelSubscribe\SubscribeServiceProvider" --tag="views"
```

The templates will appear in `resources/views/vendor/subscribe`.