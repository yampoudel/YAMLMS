<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => strip_tags($this->description), // Clean html for mobile
            'lesson_count' => $this->lesson_count ?? 0,
            'teacher' => $this->creator->name ?? 'unknown',

            // This stays hidden UNLESS you use ->with() or ->load() in the Controller
            'lessons' => LessonResource::collection($this->whenLoaded('lessons')),

            'my_progress' => $this->whenLoaded('courseProgress', function () {
                $record = $this->courseProgress->first();

                return [
                    'percentage' => $record->progress_percentage ?? 0,
                    'status' => $record->status ?? 'Not Started',
                ];
            }),
        ];
    }
}
