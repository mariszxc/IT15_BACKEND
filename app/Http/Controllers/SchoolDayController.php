<?php

namespace App\Http\Controllers;

use App\Http\Resources\SchoolDayResource;
use App\Models\SchoolDay;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SchoolDayController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 30);
        $perPage = max(1, min($perPage, 366));

        return SchoolDayResource::collection(
            SchoolDay::orderBy('date', 'desc')->paginate($perPage)
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date', Rule::unique('school_days', 'date')],
            'is_school_day' => ['required', 'boolean'],
            'is_holiday' => ['required', 'boolean'],
            'event' => ['nullable', 'string', 'max:255'],
            'attendance_count' => ['required', 'integer', 'min:0'],
            'attendance_rate' => ['required', 'numeric', 'between:0,100'],
        ]);

        return new SchoolDayResource(SchoolDay::create($validated));
    }

    public function show(SchoolDay $schoolDay)
    {
        return new SchoolDayResource($schoolDay);
    }

    public function update(Request $request, SchoolDay $schoolDay)
    {
        $validated = $request->validate([
            'date' => ['sometimes', 'required', 'date', Rule::unique('school_days', 'date')->ignore($schoolDay->id)],
            'is_school_day' => ['sometimes', 'required', 'boolean'],
            'is_holiday' => ['sometimes', 'required', 'boolean'],
            'event' => ['nullable', 'string', 'max:255'],
            'attendance_count' => ['sometimes', 'required', 'integer', 'min:0'],
            'attendance_rate' => ['sometimes', 'required', 'numeric', 'between:0,100'],
        ]);

        $schoolDay->update($validated);

        return new SchoolDayResource($schoolDay);
    }

    public function destroy(SchoolDay $schoolDay)
    {
        $schoolDay->delete();

        return response()->json(['message' => 'School day deleted.']);
    }
}
