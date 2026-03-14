<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index()
    {
        return CourseResource::collection(Course::withCount('students')->orderBy('name')->paginate(25));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('courses', 'code')],
            'name' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'credits' => ['required', 'integer', 'between:1,10'],
            'description' => ['nullable', 'string'],
        ]);

        return new CourseResource(Course::create($validated));
    }

    public function show(Course $course)
    {
        $course->loadCount('students');

        return new CourseResource($course);
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'code' => ['sometimes', 'required', 'string', 'max:20', Rule::unique('courses', 'code')->ignore($course->id)],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'department' => ['sometimes', 'required', 'string', 'max:255'],
            'credits' => ['sometimes', 'required', 'integer', 'between:1,10'],
            'description' => ['nullable', 'string'],
        ]);

        $course->update($validated);
        $course->loadCount('students');

        return new CourseResource($course);
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return response()->json(['message' => 'Course deleted.']);
    }
}
