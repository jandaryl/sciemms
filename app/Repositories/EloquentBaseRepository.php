<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\Contracts\BaseRepository;

/**
 * Class EloquentBaseRepository.
 */
class EloquentBaseRepository implements BaseRepository
{
    protected $model;

    /**
     * Construct the model instance.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Define a new query in the model instance.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return $this->model->newQuery();
    }

    /**
     * Define the search from the Scout Buider.
     *
     * @param      $query
     * @param null $callback
     *
     * @return \Laravel\Scout\Builder
     */
    public function search($query, $callback = null)
    {
        return $this->model->search($query, $callback);
    }

    /**
     * Define the select query for the model.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function select(array $columns = ['*'])
    {
        return $this->query()->select($columns);
    }

    /**
     * Define the make to new the model instance.
     *
     * @param array $attributes
     *
     * @return Model
     */
    public function make(array $attributes = [])
    {
        return $this->query()->make($attributes);
    }
}
