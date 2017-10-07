<?php

namespace Plasm\Changeset;

trait Validations
{
    /**
     * Validates the given parameter was given as true.
     * Useful for things like accepting TOS.
     */
    public function validateAcceptance($field, $message = null)
    {
        if (isset($this->changes[$field]) && $this->changes[$field] !== true) {
            $this->addError($field, 'acceptance', $message);
        }

        return $this;
    }

    /**
     * Validates the given field change. It invokes the validator function to
     * perform the validation only if a change for the given field exists.
     * The validator should return an error message or a boolean.
     */
    public function validateChange($field, $validator, $message = null)
    {
        if (isset($this->changes[$field])) {
            $result = $validator($this->changes[$field]);
            if (is_string($result)) {
                $this->addError($field, 'change', $result);
            } elseif (!$result) {
                $this->addError($field, 'change', $message);
            }
        }

        return $this;
    }

    /**
     * Validates that the given field matches the confirmation parameter of that field.
     */
    public function validateConfirmation($field, $message = null)
    {
        if (isset($this->changes[$field]) && $this->changes[$field] != $this->changes["{$field}_confirmation"]) {
            $this->addError($field, 'confirmation', $message);
        }

        return $this;
    }

    /**
     * Validates a change is an array of the given count.
     *
     * @param string $field Filed name to validate
     * @param array $opts Validation options
     *  'is'  - (int) the count must be exactly this value
     *  'min' - (int) the count must be greater than or equal to this value
     *  'max' - (int) the count must be less than or equal to this value
     * @param string $message Error message
     *
     * @return $this
     */
    public function validateCount($field, $opts, $message = null)
    {
        if (isset($this->changes[$field]) && is_array($this->changes[$field])) {
            $count = count($this->changes[$field]);
            $this->validateCountable($field, $count, 'count', $opts, $message);
        }

        return $this;
    }

    /**
     * Validates a change is not included in the given array.
     */
    public function validateExclusion($field, $exclusions, $message = null)
    {
        if (isset($this->changes[$field]) && in_array($this->changes[$field], $exclusions)) {
            $this->addError($field, 'exclusion', $message);
        }

        return $this;
    }

    /**
     * Validate a change has the given format (using regex).
     *
     * @param string $field
     * @param string $format
     * @return $this
     */
    protected function validateFormat($field, $pattern, $message = null)
    {
        if (isset($this->changes[$field]) && preg_match($pattern, $this->changes[$field]) !== 1) {
            $this->addError($field, 'format', $message);
        }

        return $this;
    }

    /**
     * Validates a change is included in the given array.
     */
    public function validateInclusion($field, $inclusions, $message = null)
    {
        if (isset($this->changes[$field]) && !in_array($this->changes[$field], $inclusions)) {
            $this->addError($field, 'inclusion', $message);
        }

        return $this;
    }

    /**
     * Validates a change is a string of the given length.
     *
     * @param string $field Filed name to validate
     * @param array $opts Validation options
     *  'is'  - (int) the length must be exactly this value
     *  'min' - (int) the length must be greater than or equal to this value
     *  'max' - (int) the length must be less than or equal to this value
     * @param string $message Error message
     *
     * @return $this
     */
    public function validateLength($field, $opts, $message = null)
    {
        if (isset($this->changes[$field]) && is_string($this->changes[$field])) {
            $length = mb_strlen($this->changes[$field]);
            $this->validateCountable($field, $length, 'length', $opts, $message);
        }

        return $this;
    }

    /**
     * Validates the properties of a number.
     */
    // public function validateNumber($field, $opts, $message = null)
    // {
    //     // TODO: Implement validateNumber
    //
    //     // $opts
    //     // :less_than
    //     // :greater_than
    //     // :less_than_or_equal_to
    //     // :greater_than_or_equal_to
    //     // :equal_to
    //
    //     return $this;
    // }

    /**
     * Validate that one or more fields are present in the changeset.
     *
     * @param string|array|\ArrayAccess $fields
     * @return $this
     */
    protected function validateRequired($fields, $message = null)
    {
        $fields = is_string($fields) ? func_get_args() : $fields;

        foreach ($fields as $field) {
            if (! isset($this->changes[$field])) {
                $this->addError($field, 'required', $message);
            }
        }

        return $this;
    }

    /**
     * Validates a change, in an array, is a subset of the given array.
     * Like validateInclusion() for arrays.
     */
    // public function validateSubset($field, $set, $message = null)
    // {
    //     // TODO: Implement validateSubset
    //
    //     return $this;
    // }

    /**
     * Validates countable $field against $rules
     *
     * @param string $field Name of the field to validate
     * @param int $count Value of the countable field
     * @param string $action Action title
     *  'count'  - validating count of an array
     *  'length' - validating length of a string
     * @param array $rules List of validation rules
     *  'is'  - (int) the $count must be exactly this value
     *  'min' - (int) the $count must be greater than or equal to this value
     *  'max' - (int) the $count must be less than or equal to this value
     * @param string $message Error message
     *
     * @return void
     */
    private function validateCountable($field, $count, $action, $rules, $message)
    {
        if (isset($rules['is']) && $count !== $rules['is']) {
            $this->addError($field, $action . ':is', $message, $rules['is']);
        }

        if (isset($rules['min']) && $count < $rules['min']) {
            $this->addError($field, $action . ':min', $message, $rules['min']);
        }

        if (isset($rules['max']) && $count > $rules['max']) {
            $this->addError($field, $action . ':max', $message, $rules['max']);
        }
    }
}
