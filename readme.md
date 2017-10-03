# Plasm

[![Build Status](https://travis-ci.org/ryanwinchester/plasm.svg?branch=master)](https://travis-ci.org/ryanwinchester/plasm)

Filter, cast, and validate incoming data from **forms**, **API**s, **CLI**, etc.

Schema and Changeset for PHP inspired and modeled after `Ecto.Changeset` from [Elixir's Ecto library](https://hexdocs.pm/ecto/Ecto.Changeset.html).

### In Development!


### TODO

- [ ] Default messages str replacements
- [ ] Finish implementing validators
- [ ] figure out how to do db constraints (unique etc)
- [ ] framework/orm integrations


### Example

#### 1) Define a Schema:

```php
<?php

use Plasm\Integrations\EloquentSchemas;
use Plasm\Schema;

class UserSchema extends Schema
{
    use EloquentSchemas;

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

#### 2) Define a Changeset:

```php
<?php

use Plasm\Changeset;
use Plasm\Integrations\EloquentChangesets;

class UserCreateChangeset extends Changeset
{
    use EloquentChangesets;

    public function change()
    {
        return $this
            ->cast(['name', 'email', 'is_admin', 'age', 'money', 'password', 'nothing'])
            ->validateFormat('email', '/.+@.+\..+/')
            ->validateLength('password', ['min' => 8])
            ->validateConfirmation('password')
            ->validateNumber('age', ['greater_than' => 13])
            ->validateRequired(['name', 'email', 'age', 'password']);
    }
}
```

#### 3) Use them somewhere:

```php
function store($request)
{
    $changeset = new UserCreateChangeset(UserSchema::class, $request->all());

    if (! $changeset->valid()) {
        return back()->with('changeset', $changeset);
    }

    $user = $changeset->save();

    return redirect()->route('users/index')
        ->with('success', "User {$user->email} added");
}
```
