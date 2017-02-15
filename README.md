# LionDesk API client


[![Build Status](https://travis-ci.org/skosm/liondesk.svg?branch=master)](https://travis-ci.org/skosm/liondesk)
[![Coverage Status](https://coveralls.io/repos/github/skosm/liondesk/badge.svg)](https://coveralls.io/github/skosm/liondesk)

### Prerequisites
* php v7.0+

### Usage Example
```php
$lionDesk = new Skosm\LionDesk\LionDesk('API_KEY', 'USER_KEY');
$result = $lionDesk->echo('message');
```
Or using method chaining
```php
$lionDesk = new Skosm\LionDesk\LionDesk();
$result = $lionDesk
    ->setApiKey('API_KEY')
    ->setUserKey('USER_KEY')
    ->newSubmission([
        'firstname' => 'User',
        'lastname'  => 'Name',
        'email'     => 'user@name.email'
    ]);
```
