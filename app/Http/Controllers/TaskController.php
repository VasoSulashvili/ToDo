<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use Illuminate\Http\Request;
use App\Enums\ResponseType;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $tasks = Task::where('project_id', '=', $id)
            ->orderBy('priority', 'ASC')
            ->get();

        return ResponseType::SUCCESS->response(data: $tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        try {

            Task::create([
                'project_id' => $request->input('project_id'),
                'name' => $request->input('name'),
                'priority' => 1
            ]);

            return ResponseType::CREATED->response();

        } catch (\Throwable $throwable) {

            return ResponseType::ERROR->response();

        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        try {
            $task->update([
                'name' => $request->input('name')
            ]);

            return ResponseType::SUCCESS->response(data: $task);

        } catch (\Throwable $throwable) {

            return ResponseType::ERROR->response();

        }
    }

    public function toggle(Task $task)
    {
        try {
            $task->update([
                'completed' => $task->completed ? 0 : 1
            ]);

            return ResponseType::SUCCESS->response(data: $task);

        } catch (\Throwable $throwable) {


            return ResponseType::ERROR->response();

        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        try {
            $task->delete();

            return ResponseType::SUCCESS->response();

        } catch (\Throwable $throwable) {

            return ResponseType::ERROR->response();

        }
    }

    public function upsert(Request $request)
    {
        $data = json_decode($request->input('data'));
        DB::beginTransaction();
        try {

            foreach ($data as $key => $task)
            {
                $model = Task::where('id', '=', $task->id)->first();

                $model->update(['priority' => $key + 1]);
            }

            DB::commit();
            return ResponseType::SUCCESS->response();

        } catch (\Throwable $throwable) {

            DB::rollBack();
            return ResponseType::ERROR->response();
        }
    }


}
