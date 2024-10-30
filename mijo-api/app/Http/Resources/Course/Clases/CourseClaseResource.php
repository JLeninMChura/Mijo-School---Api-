<?php

namespace App\Http\Resources\Course\Clases;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseClaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->resource->id,
            "course_section_id" => $this->resource->course_section_id,
            "name" => $this->resource->name,
            "description" => $this->resource->description,
            "vimeo_id" => $this->resource->vimeo_id ? "https://player.vimeo.com/video/" . $this->resource->vimeo_id : NULL,
            "time" => $this->resource->time,
            "state" => $this->resource->state,
            "files" => $this->resource->files->map(function ($file) {
                return [
                    "id" => $file->id,
                    "course_clase_id" => $file->course_clase_id,
                    "name_file" => $file->name_file,
                    "size" => $file->size,
                    "time" => $file->time,
                    "resolution" => $file->resolution,
                    "file" => env("APP_URL")."storage/". $file->file,
                    "type" => $file->type
                ];
            }),
        ];
    }
}
