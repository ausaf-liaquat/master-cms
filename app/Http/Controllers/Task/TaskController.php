<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use App\Models\Task;
class TaskController extends Controller
{
    /**
     * returns the index view
     */
    public function index(Request $request)
    {
        //getCurrentMenuId used from helpers.php
        $menu_id = getCurrentMenuId($request);

        //getFrontEndPermissionsSetup used from helpers.php
        $data = getFrontEndPermissionsSetup($menu_id);

        return view("main.task.index", $data);
    }

    /**
     * returns the datatable instance
     */
    public function datatable()
    {
        $model = Task::query();

        return DataTables::eloquent($model)->addIndexColumn()->make();
    }

    /**
     * returns the create view
     */
    public function create()
    {
        $data = [
            "isEdit" => false,
            'menu' => getCurrentMenuViaPermission(),
        ];

        return view("main.task.add", $data);
    }
    public function store(Request $request)
    {

    }

    public function edit($id)
    {
        $data = [
            "isEdit" => true,
            "" => Task::find($id),
            "menu" => getCurrentMenuViaPermission(),
        ];

        return view("main.task.add", $data);
    }

    public function update(Request $request, $id)
    {

    }

    /**
     * update the status
     */
    public function status(Request $request)
    {
        $response['status'] = false;
        $response['message'] = 'Oops! Something went wrong.';

        $id = $request->input('id');

        $status = $request->input('status');

        $item = Task::find($id);

        if ($item->update(['status' => $status])) {
            $response['status'] = true;
            $response['message'] = 'Task status updated successfully.';
            return response()->json($response, 200);
        }

        return response()->json($response, 409);
    }

    public function destroy($id)
    {
        $item = Task::where('id', $id)->first();
        $response['status'] = false;
        $response['message'] = 'Oops! Something went wrong.';

        if (false) {
            $response['error'] = 'This Task has Cases assgined to it';
            return response()->json($response, 409);
        } else {
            $response = $item->delete();
            $response['message'] = 'Task Destroyed successfully..';
            return response()->json($response, 200);
        }
    }
}
