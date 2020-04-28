<?php

namespace App\Http\Resources;

trait DynamicFieldsCollectionTrait
{

    /**
     * * Store array keys to remove from resource.
     *
     * @var array
     */
    protected $withoutFields = [];

    /**
     * * Store array keys to return in resource.
     *
     * @var array
     */
    protected $onlyFields = [];

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return $this->processCollection($request);
    }
    /**
     * Set the keys that are supposed to be filtered out.
     *
     * @param array $fields
     * @return $this
     */
    public function hide(array $fields)
    {
        $this->withoutFields = $fields;

        return $this;
    }

    /**
     * Set the keys that are supposed to return.
     *
     * @param array $fields
     * @return $this
     */
    public function only(array $fields)
    {
        $this->onlyFields = $fields;

        return $this;
    }

    /**
     * Send fields to hide to Resource while processing the collection.
     *Processing collection of hidden fields through Resource
     *
     * @param $request
     * @return array
     */
    protected function processCollection($request)
    {
        return $this->collection->map(function ($resource) use ($request) {
            if ($this->onlyFields) {
                $resource = $resource->only($this->onlyFields);
            }
            return $resource->hide($this->withoutFields)->toArray($request);
        })->all();
    }
}
