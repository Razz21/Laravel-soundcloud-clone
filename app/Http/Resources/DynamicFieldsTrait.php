<?php

namespace App\Http\Resources;

trait DynamicFieldsTrait
{

    /**
     * * The collection instance.
     *
     * @var string
     */
    protected static $collectionClass;

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
     * * Ensure collection class return current resource.
     *
     * @var mixed
     */
    public static function collection($resource)
    {
        if (self::$collectionClass) {
            return tap(new self::$collectionClass($resource), function ($collection) {
                $collection->collects = __CLASS__;
            });
        } else {
            return parent::collection($resource);
        }
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

    abstract public function toArrayFilter($request);

    /**
     * Remove the filtered keys.
     *
     * @param $array
     * @return array
     */
    protected function filterFields($array)
    {
        $collect = collect($array);
        if ($this->onlyFields) {
            $collect = $collect->only($this->onlyFields);
        }
        return $collect->forget($this->withoutFields)->toArray();
    }

    /**
     * Transform the resource into a filtered array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */

    public function toArray($request)
    {
        return $this->filterFields($this->toArrayFilter($request));
    }
}
