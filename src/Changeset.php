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
     * @param Schema|string $schema
     * @throws \TypeError
     */
    public function __construct($schema)
    {
        $this->schema = is_string($schema) ? new $schema() : $schema;

        if (! $this->schema instanceof Schema) {
            throw new \TypeError("Invalid Schema type");
        }

        $this->messages = array_merge(
            $this->defaultMessages,
            $this->messages()
        );
    }

    /**
     * Create a new Changeset using given Schema.
     *
     * @param Schema|string $schema
     * @return static
     */
    public static function using($schema)
    {
        return new static($schema);
    }

    /**
     * Cast the fields from attrs to the types defined in the Schema,
     * ignores any fields not specified or uses the default value if one is
     * defined in the Schema.
     *
     * @param array|\ArrayAccess $attrs
     * @param array|\ArrayAccess $fields
     * @return $this
     */
    protected function cast($attrs, $fields)
    {
        $this->attrs = $attrs;

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
     * Cast an existing field.
     *
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
     * Whether or not to use the default value.
     *
     * @param string $field
     * @return bool
     */
    private function shouldUseDefault($field)
    {
        return !isset($this->attrs[$field])
            && isset($this->schema[$field]['default']);
    }

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
     * Add a change to the Changeset.
     *
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
}
