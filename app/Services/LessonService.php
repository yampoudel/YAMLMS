<?php

namespace App\Services;

use App\Models\Lesson;
use Illuminate\Pagination\LengthAwarePaginator;

class LessonService
{
    /**
     * Get all the lessons
     */
    public function getLessonList($per_page = 15): LengthAwarePaginator
    {
        $user = auth()->user();

        if (! $user->isAdmin()) {
            return Lesson::with(['course', 'creator'])
                ->where('created_by', $user->id)
                ->orderBy('position')
                ->paginate($per_page);
        }

        return Lesson::with(['course', 'creator'])
            ->orderBy('position')
            ->paginate($per_page);
    }

    /**
     * Create lession
     */
    public function storeLesson(array $validated_lessons)
    {
        // Get id who creates lesson
        $validated_lessons['created_by'] = auth()->id();

        // cteate Lesson
        Lesson::create($validated_lessons);
    }
}
