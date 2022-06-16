<?php

namespace Bagoesz21\LaravelNotification\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseResourceCollection extends ResourceCollection
{
    protected $jsonResource;

    /**
     * @param mixed $resource
     * @param \Illuminate\Http\Resources\Json\JsonResource $jsonResource
     * @return void
     */
    public function __construct($resource, $jsonResource)
    {
        parent::__construct($resource);


        $this->jsonResource = $jsonResource;
        $this->resource = $this->collectResource($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return  $this->collection->mapInto($this->jsonResource)->all();

    }
}
