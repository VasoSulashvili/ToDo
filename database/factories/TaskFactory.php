<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $priority = Task::where('project_id', 9)
            ->orderBy('priority', 'DESC')
            ->first();
        $priority = $priority?->priority ? $priority->priority + 1 : 1;
        return [
            'project_id' => Project::factory()->create()->id,
            'name' => fake()->sentence(),
            'completed' => 0,
            'priority' => $priority
        ];
    }
}
