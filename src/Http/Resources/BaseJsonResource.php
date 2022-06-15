<?php

namespace Bagoesz21\LaravelNotification\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseJsonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge($this->defaultData(), $this->custom());
    }

    /**
     * @return array
     */
    public function defaultData()
    {
        if (is_null($this->resource)) {
            return [];
        }

        return is_array($this->resource)
            ? $this->resource
            : $this->resource->toArray();
    }

    /**
     * @return array
     */
    public function custom()
    {
        return [];
    }
}
