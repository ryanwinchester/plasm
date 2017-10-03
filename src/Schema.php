<?php

namespace Plasm;

use ArrayAccess;

abstract class Schema implements ArrayAccess
{
    private $fields;

    /**
     * Make-a the Schema.
     */
    public function __construct()
    {
        $this->fields = $this->definition();
    }

    /**
     * Define the fields and their types.
     *
     * @return array
     */
    abstract protected function definition();

    /**
     * Get the defined fields.
     *
     * @return array
     */
    public function fields()
    {
        return $this->fields;
    }

    /**
     * @param mixed $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->fields[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->fields[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        throw new \Exception("Schema is read-only");
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        throw new \Exception("Schema is read-only");
    }

}
