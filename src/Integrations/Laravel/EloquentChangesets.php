<?php

namespace Plasm\Integrations\Laravel;

trait EloquentChangesets
{
    /**
     * The Fully Qualified Class Name of the Eloquent Model
     * that this changeset represents.
     * @var string
     */
    protected $model;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    private $instance;

    /**
     * Initialize the integration.
     *
     * @throws \Exception
     */
    protected function initIntegration()
    {
        if (empty($this->model)) {
            throw new \Exception('You need to define $model class in your Changeset.');
        }
    }

    /**
     * @return bool
     */
    public function saveModel()
    {
        $this->instance = new $this->model($this->changes());

        return $this->instance->save();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createModel()
    {
        $this->instance = $this->model::create($this->changes());

        return $this->instance;
    }

    /**
     * @param mixed $id
     * @return bool
     */
    public function updateModel($id)
    {
        $this->instance = $this->model::find($id);

        return $this->instance->update($this->changes());
    }

    /**
     * Return the model instance.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getInstance()
    {
        return $this->instance ?? new $this->model();
    }

    protected function uniqueConstraint($field)
    {
        // TODO: implement uniqueConstraint
    }
}
