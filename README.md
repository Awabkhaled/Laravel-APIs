This is my first interaction with laravel or PHP, and here is a description of the project

# Database
- I am using local MySQL database I have configured in the .env file

# Tags
## Tag Model
- I started witrh creating the Tag Model using the 'php artisan make:model ModelName --migration'.
- Made the name unique by adding the '->unique()' to the migration file.
- The Model does not apply any authentication yet.
- I made the name fillable so it can be assigned values directly when creating or updating the model using Laravel's ORM.
- and disabled the timestamp (created_at, updated_at)
## TagRequest Validation file
- a little validation class based on the FormRequest class to validate the data before going through the endpoints.
- I Forced the name to be : required, string, unique(ignoring its own id in the comparison to handle updating), and its maximum length is 255.
- Made a couple of customized messages for error
- overrided the `failedValidation` method to be able to return a response in json, because the default behaviour involves redirecting the user back to the previous page with the error messages.
## TagController
- I sarted with generating an API resource controller using the command `php artisan make:controller TagController --api`
- Added two helper static methods to the class:
    - *JsonResponse*: to take the data and the status code, and returns a JSON response and formated using the JSON_PRETTY_PRINT option
    - *validateId*: to take an Id and make sure that the id exists in the tag model and that the id is a number
- Implementing the methods that correspond with an action
    - `index` (list)
    - `store` (create): Used the validation I created first to ensure the validation of the name field
    - `show` (Retrieve)
    - `update` (Update): Used the same validation for creation
    - `destroy` (Delete)

