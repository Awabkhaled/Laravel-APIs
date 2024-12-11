<?php
namespace App\Helpers\ValidationHelpers;
class ValidationHelpers {
    private static function check_id_in_model($id, $model, $user_id=null){
        // prepare the query by checking  if the ID exists
        $query = $model::where('id', $id);

        // add checking user to the query
        if($user_id !== null){
            $query->where('user_id', $user_id);
        }

        return $query->exists();
    }

    /**
     * A helper function that validate an id by making sure
     * that the id is a number and that the id exist in the
     * model it belongs to
     * Returns:
     *   - if invalid: [false, message, status_code]
     *   - if valid: [true, null, null]
     */
    public static function Validate_id($id, $model, $user_id = null){
        // Check if the ID is a number
        if(!is_numeric($id)) {
            return [false, 'Invalid ID Format', 400];
        }

        // check if the id exist in the table witht he assigned user
        if(!self::check_id_in_model($id, $model, $user_id)) {

            return [false, 'Id Does Not Exist', 400];
        }
        return [true, null, null];
    }
}