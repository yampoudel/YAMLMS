<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Pagination\LengthAwarePaginator;

class LessonService
{
    /**
     * Get all the lessons
     *
     * @param  int  $per_page
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
     * Create lesson
     */
    public function storeLesson(array $validated_lesson): Lesson
    {
        // Transform the 'content' string into your JSON block format
        $prepared_data = $this->handleLessonContent($validated_lesson);

        // Find the highest position in this course and add
        $course = Course::findOrFail($prepared_data['course_id']);
        $prepared_data['position'] = $course->lessons->max('position') + 1;

        // Get id who creates lesson
        $prepared_data['created_by'] = auth()->id();

        // cteate Lesson
        return Lesson::create($prepared_data);
    }

    /**
     * Update Lesson
     */
    public function updateLesson(Lesson $lesson, array $validated_lesson): Lesson
    {
        // Transform the 'content' string into your JSON block format
        $prepared_data = $this->handleLessonContent($validated_lesson);

        // Update lesson
        $lesson->update($prepared_data);

        return $lesson;
    }

    /**
     * Delete lesson
     */
    public function delete(Lesson $lesson): bool
    {
        // Delete lesson
        return $lesson->delete();
    }

    /**
     * Return array of data for content
     */
    public function handleLessonContent(array $data): array
    {
        // Wrap the raw string from form to json block format
        $data['content'] = [
            [
                'type' => $data['type'] === 'Default' ? 'text' : strtolower($data['type']),
                'value' => $data['content'], // String from ck editor
            ],
        ];

        return $data;
    }
}
