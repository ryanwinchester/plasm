<?php

namespace Plasm;

use Plasm\Changeset\Errors;
use Plasm\Changeset\Validations;

abstract class Changeset
{
    use Validations;
    use Errors;

    private $schema;
    private $attrs;
    private $changes;

    /**
     * Create a new Changeset.
     *
     * @param Schema|string $schema
     * @param array|\ArrayAccess $attrs
     * @throws \TypeError
     */
    public function __construct($schema, $attrs)
    {
        $this->schema = is_string($schema) ? new $schema() : $schema;

        if (! $this->schema instanceof Schema) {
            throw new \TypeError("Invalid Schema type");
        }

        $this->attrs = $attrs;

        $this->messages = array_merge(
            $this->defaultMessages,
            $this->messages()
        );

        $this->change();
    }

    /**
     * Do the things.
     *
     * @return $this
     */
    abstract public function change();

    /**
     * Get the changes.
     *
     * @return array
     */
    public function changes()
    {
        return $this->changes;
    }

    /**
     * Get a change for a field of return null.
     *
     * @param string $field
     * @return mixed|null
     */
    public function getChange($field)
    {
        return isset($this->changes[$field]) ? $this->changes[$field] : null;
    }

    /**
     * @param string $field
     * @param mixed $value
     */
    protected function addChange($field, $value = null)
    {
        if (!is_null($value)) {
            $this->changes[$field] = $value;
        } elseif (isset($this->attrs[$field])) {
            $this->changes[$field] = $this->attrs[$field];
        }
    }

    /**
     * Cast the fields from attrs to the types defined in the Schema.
     *
     * @param array|\ArrayAccess $fields
     * @return $this
     */
    protected function cast($fields)
    {
        $fields = is_string($fields) ? func_get_args() : $fields;

        foreach ($fields as $field) {
            if ($this->shouldUseDefault($field)) {
                $this->addChange($field, $this->schema[$field]['default']);
            } else {
                $this->addChange($field);
                $this->castField($field);
            }
        }

        return $this;
    }

    /**
     * @param string $field
     */
    private function castField($field)
    {
        if (isset($this->changes[$field])) {
            if (!settype($this->changes[$field], $this->schema[$field]['type'])) {
                $this->addError($field, 'cast');
            }
        }
    }

    /**
     * @param string $field
     * @return bool
     */
    private function shouldUseDefault($field)
    {
        return !isset($this->attrs[$field])
            && isset($this->schema[$field]['default']);
    }
}
