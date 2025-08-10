<?php
namespace App\Http\Controllers;
use App\Models\CareTask;
use Illuminate\Http\Request;
class CareTaskController extends Controller {
    public function index() { return CareTask::all(); }
    public function store(Request $request) { $task = CareTask::create($request->all()); return response()->json($task, 201); }
    public function show(CareTask $task) { return $task; }
    public function update(Request $request, CareTask $task) { $task->update($request->all()); return response()->json($task); }
    public function destroy(CareTask $task) { $task->delete(); return response()->json(null, 204); }
}
