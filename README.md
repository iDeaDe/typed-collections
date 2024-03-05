# Typed collections

### Installation:

`composer require ideade/typed-collections:^0.1`

### Supported types
- Scalar: bool, int, float, string :white_check_mark:
- Any of your classes :white_check_mark:
- resource(including closed), array :x:

### TODO

Add tests. Right now the latest phpunit conflicts with psalm because of the nikic/php-parser versions they use

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
        
        ...
        
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
