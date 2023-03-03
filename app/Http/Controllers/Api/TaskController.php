<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Get all tasks of the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $tasks = $user->tasks()->with('suggestedUsers')->get();
    
        return response()->json([
            'tasks' => $tasks,
        ]);
    }

    /**
     * Create a new task for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:65535',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = Auth::user();

        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->user_id = $user->id;
        $task->save();

        return response()->json([
            'message' => 'Task created successfully',
            'task' => $task,
        ]);
    }

    /**
     * Get the specified task of the authenticated user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();

        $task = $user->tasks()->find($id);

        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        return response()->json([
            'task' => $task,
        ]);
    }

    /**
     * Update the specified task of the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:65535',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = Auth::user();

        $task = $user->tasks()->find($id);

        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        $task->title = $request->input('title', $task->title);
        $task->description = $request->input('description', $task->description);
        $task->save();

        return response()->json([
            'message' => 'Task updated successfully',
            'task' => $task,
        ]);
    }

    /**
     * Delete the specified task of the authenticated user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
        {
            $user = Auth::user();
        
            $task = $user->tasks()->find($id);
        
            if (!$task) {
                return response()->json([
                    'message' => 'Task not found',
                ], 404);
            }
        
            $task->delete();
        
            return response()->json([
                'message' => 'Task deleted successfully',
            ]);
        }


        public function addSuggestedUsers(Request $request, Task $task)
            {
                $request->validate([
                    'user_ids' => 'required|array',
                    'user_ids.*' => 'exists:users,id'
                ]);

                $task->suggested_users()->sync($request->user_ids);

                return response()->json([
                    'message' => 'Suggested users added successfully'
                ]);
            }

}