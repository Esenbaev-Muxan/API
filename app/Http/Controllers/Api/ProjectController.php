<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Get all projects of the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $projects = $user->projects()->get();

        return response()->json([
            'projects' => $projects,
        ]);
    }

    /**
     * Create a new project for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:65535',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = Auth::user();

        $project = new Project();
        $project->name = $request->name;
        $project->description = $request->description;
        $project->user_id = $user->id;
        $project->save();

        return response()->json([
            'message' => 'Project created successfully',
            'project' => $project,
        ]);
    }

    /**
     * Get the specified project of the authenticated user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();

        $project = $user->projects()->find($id);

        if (!$project) {
            return response()->json([
                'message' => 'Project not found',
            ], 404);
        }

        return response()->json([
            'project' => $project,
        ]);
    }

    /**
     * Update the specified project of the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:65535',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = Auth::user();

        $project = $user->projects()->find($id);

        if (!$project) {
            return response()->json([
                'message' => 'Project not found',
            ], 404);
        }

        $project->name = $request->input('name', $project->name);
        $project->description = $request->input('description', $project->description);
        $project->save();

        return response()->json([
            'message' => 'Project updated successfully',
            'project' => $project,
        ]);
    }

    /**
     * Delete the specified project of the authenticated user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      
    }


    public function search($name){
        return Project::where('name','like','%'.$name.'%')->get();
    }
}