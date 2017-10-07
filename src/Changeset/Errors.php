<?php

namespace Plasm\Changeset;

trait Errors
{
    protected $defaultMessages = [
        'acceptance' => 'You must accept {field}',
        'change' => '{field} failed validation',
        'confirmation' => '{field} and {field} confirmation do not match',
        'count:is' => 'you do not have {count} {field}s',
        'count:min' => 'you need at least {count} {field}s',
        'count:max' => 'you can have, at most {count}, {field}s',
        'exclusion' => 'that is not a valid {field}',
        'format' => '{field} is not the desired format',
        'inclusion' => '{field} is not among the desired values',
        'length:is' => 'you do not have {length} {field}s',
        'length:min' => 'you need at least {length} {field}s',
        'length:max' => 'you can have, at most {length}, {field}s',
        'number:less_than' => '{field} should be less than {number}',
        'number:greater_than' => '{field} should be greater than {number}',
        'number:less_than_or_equal_to' => '{field} should be less than or equal to {number}',
        'number:greater_than_or_equal_to' => '{field} should be greater than or equal to {number}',
        'number:equal_to' => '{field} should be equal to {number}',
        'required' => '{field} is required',
        'subset' => '{field} is not a valid subset',
    ];

    /**
     * Determine if the Changeset is valid or not.
     *
     * @return bool
     */
    public function valid()
    {
        return count($this->errors) === 0;
    }

    /**
     * Get all the errors.
     *
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Get the error message for a field.
     * @param string $field
     * @return mixed
     */
    public function getErrors($field)
    {
        return $this->errors[$field];
    }

    /**
     * Add an error message to the errors, by field.
     *
     * @param string $field
     * @param string $action
     * @param string $message
     * @param mixed $constraint
     */
    protected function addError($field, $action, $message = null, $constraint = null)
    {
        $message = is_null($message)
            ? str_replace('{field}', $this->humanize($field), $this->messages[$action])
            : $message;

        $message = !is_null($constraint)
            ? str_replace(['{count}', '{length}', '{number}'], $constraint, $message)
            : $message;

        $this->errors[$field][] = $message;
    }

    /**
     * Turn a field name like 'first_name' into 'First name'
     *
     * @param string $field
     * @return string
     */
    protected function humanize($field)
    {
        return str_replace('_', ' ', ucfirst($field));
    }
}
