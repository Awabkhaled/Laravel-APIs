# Project Description

### Summary
This project is a RESTful API backend system built with Laravel. It implements user authentication using **Laravel Sanctum**, allowing users to securely register, log in, and manage their posts and tags. The API is designed to handle basic CRUD operations while ensuring that data is properly validated and managed.
### Main Features
#### Posts
- **CRUD Operations**: Users can create, read, update, and delete posts.
- **Image Support**: Posts can have images attached to them.
- **Soft Delete**: Posts can be softly deleted, meaning they are marked as deleted but not completely removed from the database.
- **Restore Soft Deleted Posts**: Users can restore soft-deleted posts, bringing them back.
#### Tags
- **CRUD Operations**: Tags can be created, updated, and deleted, providing an efficient way to categorize and organize posts.

---

# Database
- Using a local MySQL database configured in the `.env` file.

---


# Authentication
- Applied **Sanctum** middleware for user authentication, ensuring that routes requiring authentication are protected.
- Used the `Route::middleware('auth:sanctum')->group(...)` to wrap the post and tag related routes, ensuring only authenticated users can create, update, delete, or view posts.
- The authentication system includes registration and login endpoints with token expiration set for three days.
### AuthController
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
### User Model
- I removed all the unnecessary fields and added the phone_number.
- I made the phone number unique so I can use it in the login (seemed reasonable).
- User uses the `HasApiTokens` from Sanctum.
### Testing Using Postman Note
- To Test it in postman you have to add the `Accept` header and with the value `application/json`, If the Accept header is not set correctly or not passed in, Laravel might try to treat the request as a web request and try to reach a route for the login page, leading to the `Route [login] not defined` error.

---

# Tags
### Tag Model
- I started witrh creating the Tag Model using the `php artisan make:model ModelName --migration`.
- Made the name unique by adding the '->unique()' to the migration file.
- I made the name fillable so it can be assigned values directly when creating or updating the model using Laravel's ORM.
- and disabled the timestamp (created_at, updated_at)
### TagRequest Validation file
- a little validation class based on the FormRequest class to validate the data before going through the endpoints.
- I Forced the name to be : required, string, unique(ignoring its own id in the comparison to handle updating), and its maximum length is 255.
- Made a couple of customized messages for error
- overrided the `failedValidation` method to be able to return a response in json, because the default behaviour involves redirecting the user back to the previous page with the error messages.
### TagController
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
### Authentication
- Used the `sanctum` authentication system and applied it in the `Routes` using the middleware `Route::middleware('auth:sanctum')->group(....);`

---

# Post
### Post Model
- Created the `Post` model using the `php artisan make:model Post --migration` command.
- Defined relationships to the `User` model (each post belongs to a user) and did the inverse relationship because if needed in the future.
- Defined relationships to `Tag` model by creating a pivot table to be as a bridge between the two tables (many-to-many relationship), and defined it in the two ways in each Model.
- Added the `SoftDeletes` trait to the model to enable soft deletion of posts. This allows posts to be marked as deleted without being permanently removed from the database.
- Defined fillable fields for attributes like `title`, `body`, `pinned`, and `cover_image`, allowing direct mass assignment via Laravel's ORM.
### PostRequest Validation Files
#### BasePostRequest
- The `BasePostRequest` class extends Laravel's `FormRequest` class and is designed to handle common validation logic for post-related requests.
- It includes an array `$returnedRules` that can be customized by child classes to define the specific validation rules.
- The `authorize()` method is overridden to always return `true`, meaning all users are authorized to make the request.
- The `rules()` method returns the `$returnedRules`, allowing specific validation rules to be set in the child request classes.
- The `failedValidation()` method is customized to return validation errors in a JSON response with a 400 status code, formatted for better readability using `JSON_PRETTY_PRINT`.
- The `messages()` method provides custom error messages for the `tags` field, specifically for invalid tag IDs and non-existent tags.
#### StorePostRequest
- The `StorePostRequest` class extends `BasePostRequest` and is used for validating post creation requests.
- It sets the `$returnedRules` to validate:
    - `title`: Required, string, and up to 255 characters.
    - `body`: Required string for the content of the post.
    - `cover_image`: Required and must be an image.
    - `pinned`: Required and must be a boolean value.
    - `tags`: Required and must be an array of tag IDs.
    - `tags.*`: Ensures each tag ID is an integer and exists in the `tags` table.
#### UpdatePostRequest
- The `UpdatePostRequest` class extends `BasePostRequest` and is used for validating post update requests.
- It sets the `$returnedRules` to validate:
    - `title`: Conditionally required (only if provided), string, and up to 255 characters.
    - `body`: Conditionally required (only if provided), string.
    - `cover_image`: Conditionally required (only if provided), must be an image.
    - `pinned`: Conditionally required (only if provided), must be a boolean.
    - `tags`: Conditionally required (only if provided), must be an array of tag IDs.
    - `tags.*`: Ensures each tag ID is an integer and exists in the `tags` table.
    - `replace_whole_tags`: Required when `tags` is provided, must be a boolean. This is used to decide if the tags should be replaced entirely during an update.
### PostController
- Used the `php artisan make:controller PostController --api` command to generate the controller for managing posts.
- Implemented actions for handling CRUD operations on posts:
    - `index` (list posts): Retrieves all posts associated with the logged-in user.
    - `store` (create post): Validates the request data before creating a new post using the `PostRequest` validation class.
    - `show` (retrieve post): Fetches a single post based on its ID and ensures the user is authorized to view it.
    - `update` (update post): Allows users to update their posts, validating data before applying changes.
    - `destroy` (soft delete): Softly deletes a post, making it recoverable later.
    - `trashed` (retrieve deleted post): Retrieves all deleted posts associated with the logged-in user.
    - `restore` (restore post): Restores a soft-deleted post, bringing it back to the user's post list.
- Ensured that users can only manage their own posts by verifying ownership of the post before performing updates or deletions by using the `ValidationHelpers` Class .
### Post Resource
- Defined a custom API resource class to format the post data in the response.
- Used the `PostResource` to structure the post data to ensure consistency in the API responses, including attributes like `id`, `title`, `body`, `cover_image`, `pinned`, `user`, and `tags`.

---

# Validation Helper
- Created a helper class for validating IDs used in the request, ensuring that provided post IDs or tag IDs exist in the database before processing the request.
- Added a method to check if a user is authorized to modify their own posts, preventing unauthorized access to other users' posts or tags.