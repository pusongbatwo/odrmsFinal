<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition()
    {
        return [
            'student_id' => $this->faker->unique()->numerify('2025####'),
            'first_name' => $this->faker->firstName,
            'middle_name' => $this->faker->optional()->firstName,
            'last_name' => $this->faker->lastName,
            'program' => $this->faker->randomElement([
                'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY',
                'BACHELOR OF SCIENCE IN ENTREPRENEURSHIP',
                'BACHELOR OF SCIENCE IN CRIMINOLOGY',
                'BACHELOR OF ELEMENTARY EDUCATION',
                'BACHELOR OF EARLY CHILDHOOD EDUCATION',
                'BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT',
                'BACHELOR OF PUBLIC ADMINISTRATION',
            ]),
            'year_level' => $this->faker->randomElement(['1st Year', '2nd Year', '3rd Year', '4th Year']),
            'school_year' => $this->faker->randomElement(['2023-2024', '2024-2025', '2025-2026']),
            'status' => $this->faker->randomElement(['active', 'on leave', 'graduated', 'dropped']),
        ];
    }
}
