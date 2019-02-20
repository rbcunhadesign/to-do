<?php

namespace App\Http\Controllers;

use App\ToDo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ToDoController extends Controller
{
    /**
     * Fetches all to dos
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => ToDo::when($request->has('complete'), function($query) {
                return $query->where('complete', '0');
            })
                ->orderBy('created_at', 'desc')
                ->get(),
        ]);
    }

    /**
     * Creates a new to do item
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $todo = ToDo::create([
            'user_id' => $request->user()->id,
            'title' => $request->get('title'),
        ]);

        return response()->json([
            'status' => 'success',
            'next_id' => $todo->id + 1,
        ]);
    }

    /**
     * Updates the title of a single to do item
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\ToDo                $todo
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTitle(Request $request, ToDo $todo)
    {
        if ((int) $request->user()->id !== (int) $todo->user_id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $todo->update($request->only('title'));

        return response()->json([
            'status' => 'success',
        ]);
    }

    /**
     * Marks to do as complete/incomplete
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\ToDo                $todo
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, ToDo $todo)
    {
        if ((int) $request->user()->id !== (int) $todo->user_id) {
            abort(403, 'Unauthorized');
        }

        $todo->update([
            'complete' => $todo->complete ? 0 : 1,
        ]);

        return response() ->json([
            'status' => 'success',
        ]);
    }

    /**
     * Deletes single to do item
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\ToDo                $todo
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroySingle(Request $request, ToDo $todo)
    {
        if ((int) $request->user()->id !== (int) $todo->user_id) {
            abort(403, 'Unauthorized');
        }

        $todo->delete();

        return response() ->json([
            'status' => 'success',
        ]);
    }

    /**
     * Deletes all complete to dos for a single user
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyComplete(Request $request)
    {
        DB::table('to_dos')
            ->where('user_id', $request->user()->id)
            ->where('complete', '1')
            ->delete();

        return response() ->json([
            'status' => 'success',
        ]);
    }
}
