# Plasm

[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/ryanwinchester/plasm/master/LICENSE)
 [![Build Status](https://travis-ci.org/ryanwinchester/plasm.svg?branch=master)](https://travis-ci.org/ryanwinchester/plasm)
 [![codecov](https://img.shields.io/codecov/c/github/ryanwinchester/plasm.svg)](https://codecov.io/gh/ryanwinchester/plasm)
 [![Code Climate](https://codeclimate.com/github/ryanwinchester/plasm/badges/gpa.svg)](https://codeclimate.com/github/ryanwinchester/plasm)

Filter, cast, and validate incoming data from **forms**, **API**s, **CLI**, etc.

Schema and Changeset for PHP are inspired by `Ecto.Changeset`
from [Elixir's Ecto library](https://hexdocs.pm/ecto/Ecto.Changeset.html).

### In Development!


### Planned for V1.0:

- [ ] Default messages str replacements
- [ ] Finish default validators
- [ ] One or two provided Framework/ORM integrations
- [ ] Figure how to implement DB constraints from Integrations (unique, etc.)

## Install

Use [composer](https://getcomposer.org).

```sh
composer require plasm/plasm:dev-master@dev
```

## Usage

### 1) Define a Schema:

In the schema we specify all the fields we care about and specify what type
we want them to be cast to.

Options:

- `type`: required. the type the field should be cast to
- `default`: Will default to this value if not present in changeset `$attrs`
- `virtual`: This will be a future to mark fields as not for storing

```php
<?php

use Plasm\Schema;

class UserSchema extends Schema
{
    public function definition()
    {
        return [
            'name' => ['type' => 'string'],
            'email' => ['type' => 'string'],
            'is_admin' => ['type' => 'boolean', 'default' => false],
            'age' => ['type' => 'integer'],
            'money' => ['type' => 'float'],
            'password' => ['type' => 'string', 'virtual' => true],
            'password_confirmation' => ['type' => 'string', 'virtual' => true],
            'password_hash' => ['type' => 'string'],
            'nothing' => ['type' => 'string', 'default' => null]
        ];
    }
}
```

### 2) Define a Changeset:

You can define multiple changesets in the same class. You can create completely different ones or build on top of others.

For example, below we'll have a `createChangeset` for creating a user that just builds off our generic `changeset` and
making some of the fields required.

```php
<?php

use Plasm\Changeset;

class UserChangeset extends Changeset
{
    /**
     * Changeset for a User.
     */
    public function changeset($attrs)
    {
        return $this
            ->cast(['name', 'email', 'is_admin', 'age', 'money', 'password', 'nothing'])
            ->validateFormat('email', '/.+@.+\..+/')
            ->validateLength('password', ['min' => 8])
            ->validateConfirmation('password')
            ->validateNumber('age', ['greater_than' => 12], 'You need to be at least 13');
    }

    /**
     * Changeset for creating a User.
     */
    public function createChangeset($attrs)
    {
        return $this
            ->changeset($attrs)
            ->validateRequired(['name', 'email', 'age', 'password'])
            ->validateChange(
                'password',
                $this->validatePassStrength(),
                'Your password is too weak'
            );
    }

    /**
     * A custom validator for checking password strength.
     */
    private function validatePassStrength()
    {
        return function($password) {
            $zxcvbn = new \ZxcvbnPhp\Zxcvbn();
            $strength = $zxcvbn->passwordStrength($password);

            return $strength['score'] >= 3;
        };
    }
}
```

### 3) Use them somewhere:

Just for example's sake, the example below looks a lot like a typical Laravel controller's
`store` method.

We'll pass all the request data into the `createChangeset` changeset and
not worry since our `cast` method will filter out the fields we specify, cast them
to their specified types, and validate them.

If we used the `EloquentChangesets` trait we could call the `createModel` method after checking
if the changeset is valid. If it wasn't valid we can return to the view with the changeset
and display the changeset errors to the user.

```php
function store($request)
{
    $changeset = UserChangeset::using(UserSchema::class)
        ->createChangeset($request->all());

    if (! $changeset->valid()) {
        return back()->with('changeset', $changeset);
    }

    $user = $changeset->createModel();

    return redirect()->route('users/index')
        ->with('success', "User {$user->email} added");
}
```

## License

MIT

## Credits

- [Ecto.Changeset](https://hexdocs.pm/ecto/Ecto.Changeset.html): Most of the ideas come from here.
