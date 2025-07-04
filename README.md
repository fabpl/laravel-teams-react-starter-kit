# Schedule app

## Features

### Authentication
- registration
- email verification
- login
- logout
- password reset
- profile management

### Team Management
- creation
- settings
- roles and permissions
- invitations
- member management
- switching

## Development

### Defaults

Look at the `AppServiceProvider.php` file for the defaults features.

- Safe Console
- Immutable Dates
- Strict & Unguarded Models
- Strict Password rules
- Force HTTPS
- Asset Prefetching

### Code quality

To ensure code quality, we use the following tools:
- [ESLint](https://eslint.org/) for JavaScript linting
- [Prettier](https://prettier.io/) for code formatting
- [Pint](https://laravel.com/docs/12.x/pint) for PHP code formatting
- [Rector](https://getrector.com/) for automated refactoring of PHP code
- [PHPStan](https://phpstan.org/) for static analysis of PHP code
- [Pest](https://pestphp.com/) for testing PHP code and coverage

To run these tools, you can use the following commands:

```bash
# For JavaScript linting
npm run lint

# For JavaScript formatting
npm run format

# For PHP linting
composer lint

# For PHP automated refactoring
composer refactor

# For PHP static analysis
composer analyse

# For PHP testing
composer test

# For PHP code coverage
composer coverage
```
