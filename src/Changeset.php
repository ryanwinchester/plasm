<?php

namespace Plasm;

use Plasm\Changeset\Errors;
use Plasm\Changeset\Validations;

abstract class Changeset
{
    use Validations;
    use Errors;

    /**
     * The Schema used for filtering, casting and default values.
     * @var null|Schema|string
     */
    private $schema;

    /**
     * The attributes to cast and validate.
     * @var array|\ArrayAccess
     */
    private $attrs;

    /**
     * The changes.
     * @var array
     */
    private $changes;

    /**
     * The errors produced when $attrs fails validation.
     * @var array
     */
    private $errors = [];

    /**
     * The name of the schema class to use (optional override).
     * @var string
     */
    protected $schemaClass;

    /**
     * Override or add to the default messages (optional override).
     * @var array
     */
    protected $messages = [];

    /**
     * @param Schema|string $schema Schema instance or class name
     * @param string $changeset The name of the changeset to call immediately
     * @param array|\ArrayAccess $attrs The attributes to use in the changeset
     * @throws \TypeError
     */
    public function __construct($schema = null, $changeset = null, $attrs = [])
    {
        $this->schema = $this->instantiateSchema($schema);

        if (!$this->schema instanceof Schema) {
            throw new \TypeError('Invalid Schema type');
        }

        if (method_exists(static::class, 'initIntegration')) {
            $this->initIntegration();
        }

        $this->messages = array_merge(
            $this->defaultMessages,
            $this->messages
        );

        if (!is_null($changeset)) {
            $this->{$changeset}($attrs);
        }
    }

    /**
     * @param Schema|string|null $schema
     * @return Schema
     * @throws \Exception
     */
    private function instantiateSchema($schema = null)
    {
        if (!is_null($schema)) {
            return is_string($schema) ? new $schema() : $schema;
        }

        if (is_null($this->schemaClass)) {
            throw new \InvalidArgumentException('Provide a schema in the constructor or set $schemaClass in your changeset');
        }

        return new $this->schemaClass();
    }

    /**
     * Create a new Changeset using given Schema.
     *
     * @param Schema|string $schema Schema instance or class name
     * @param string $changeset The name of the changeset to call immediately
     * @param array|\ArrayAccess $attrs The attributes to use in the changeset
     * @return static
     */
    public static function using($schema, $changeset = null, $attrs = [])
    {
        return new static($schema, $changeset, $attrs);
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
            settype($this->changes[$field], $this->schema[$field]['type']);
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
