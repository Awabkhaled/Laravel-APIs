This is my first interaction with laravel or PHP, and here is a description of the project

# Database
- I am using local MySQL database I have configured in the .env file.

# Authentication
- Used sanctum to apply Token Authentication.
- Applied the expiration date in the register and in the login endpoints (explained bellow).
## AuthController
- `register`
    - Validation:
        - Making sure that the name is required and a string.
        - Making sure that the phone number is a string representing number and set boundries to its size, and making sure that it is unique.
        - Making sure that the password is required with boundries.
    - Creating the user in the database.
    - Creating the Token:
        - Set its expiration date after three days.
        - did not implemenet the schedule that will run and delete the expiration tokens.
- `login`
    - Validation: *almost the same as registration*.
    - Checking If the user exist and checking the password.
    - Creating the Token: *the same as registration*.
## User Model
- I removed all the unnecessary fields and added the phone_number.
- I made the phone number unique so I can use it in the login (seemed reasonable).
- User uses the `HasApiTokens` from Sanctum.
## Routes
### Tags
- You can check the authentication heading in the Tags part to see How I handled it using Routes.
## Testing Using Postman Note
- To Test it in postman you have to add the `Accept` header and with the value `application/json`, If the Accept header is not set correctly or not passed in, Laravel might try to treat the request as a web request and try to reach a route for the login page, leading to the `Route [login] not defined` error.

# Tags
## Tag Model
- I started witrh creating the Tag Model using the 'php artisan make:model ModelName --migration'.
- Made the name unique by adding the '->unique()' to the migration file.
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
## Authentication
- Used the `sanctum` authentication system and applied it in the `Routes` using the middleware `Route::middleware('auth:sanctum')->group(....);`
