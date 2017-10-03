<?php

namespace Plasm\Integrations;

trait EloquentChangesets
{
    /**
     * The Fully Qualified Class Name of the Eloquent Model
     * that this changeset represents.
     * @var string
     */
    protected $model;

    public function save()
    {
        // TODO: eloquent save
    }

    public function update()
    {
        // TODO: eloquent update
    }

    protected function uniqueConstraint($field)
    {
        // TODO: implement uniqueConstraint
    }
}
