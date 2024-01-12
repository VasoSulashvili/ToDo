<?php

namespace App\Http\Controllers;

use App\Enums\ResponseType;
use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();

        return ResponseType::SUCCESS->response(data: $projects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        try {

            Project::create(['name' => $request->input('name')]);

            return ResponseType::CREATED->response();

        } catch (\Throwable $throwable) {

            return ResponseType::ERROR->response();

        }
    }
}
