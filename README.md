[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Ftyped-collections)](https://dashboard.stryker-mutator.io/reports/typed-collections)

# Typed collections

PHP collections with runtime type checking

### Installation:

`composer require ideade/typed-collections:~1.0`

### Supported types
- Scalar: bool, int, float, string :white_check_mark:
- Any of your classes :white_check_mark:
- resource(including closed), array :x:

### Example:
```php

// User.php

...

final readonly class User
{   
    public function __construct(
        public string $id,
        public string $login,
        public string $email
    ) {}
}

// UserCollection.php

...

use Ideade\TypedCollections\TypedCollection;

class UserCollection extends TypedCollection
{
    protected function valueType() {
        return User::class;
    }
}

// SomeRepository.php

...

class UserRepository
{
    ...

    public function findAllUsers(): UserCollection
    {
        $users = new UserCollection();
        
        // Getting data from some source
        $sourceUsers = [];
        
        foreach ($sourceUsers as $sourceUser) {
            $users
                ->add(
                    new User(
                        $sourceUser['id'],
                        $sourceUser['login'],
                        $sourceUser['email']
                    )
                )
        }

        return $users;
    }

    ...
}

```
