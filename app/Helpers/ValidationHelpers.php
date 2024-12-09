<?php
namespace App\Helpers\ValidationHelpers;
class ValidationHelpers {
    private static function check_id_in_model($id, $model){
        if (!$model::where('id', $id)->exists()) {
            return false;
        }
        return true;
    }

    /**
     * A helper function that validate an id by making sure
     * that the id is a number and that the id exist in the
     * model it belongs to
     * Returns:
     *   - if invalid: [false, message, status_code]
     *   - if valid: [true, null, null]
     */
    public static function Validate_id($id, $model){
        if(!is_numeric($id))
        {
            return [false, 'Invalid id Format', 400];
        }

        if(!ValidationHelpers::check_id_in_model($id, $model))
        {
            return [false, 'Id Does Not Exist', 404];
        }

        return [true, null, null];
    }
}