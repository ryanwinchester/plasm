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
        if ($this->changes[$field] !== true) {
            $this->addError($field, 'acceptance', $message);
        }

        return $this;
    }

    /**
     * Validates the given field change. It invokes the validator function to
     * perform the validation only if a change for the given field exists.
     * The validator should return a boolean.
     */
    public function validateChange($field, $validator, $message = null)
    {
        if (isset($this->changes[$field]) && !$validator($this->changes[$field])) {
            $this->addError($field, 'change', $message);
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
     */
    public function validateCount($field, $opts, $message = null)
    {
        // TODO: Implement validateCount

        // $opts
        // 'is' - the count must be exactly this value
        // 'min' - the count must be greater than or equal to this value
        // 'max' - the count must be less than or equal to this value

        if (isset($this->changes[$field])) {
            $this->addError($field, 'count', $message);
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
     */
    public function validateLength($field, $opts, $message = null)
    {
        // TODO: Implement validateLength

        // $opts
        // 'is' - the length must be exactly this value
        // 'min' - the length must be greater than or equal to this value
        // 'max' - the length must be less than or equal to this value

        if (isset($this->changes[$field]) && is_string($this->changes[$field])) {
            $this->addError($field, 'length', $message);
        }

        return $this;
    }

    /**
     * Validates the properties of a number.
     */
    public function validateNumber($field, $opts, $message = null)
    {
        // TODO: Implement validateNumber

        // $opts
        // :less_than
        // :greater_than
        // :less_than_or_equal_to
        // :greater_than_or_equal_to
        // :equal_to

        return $this;
    }

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
    public function validateSubset($field, $set, $message = null)
    {
        // TODO: Implement validateSubset

        return $this;
    }
}
