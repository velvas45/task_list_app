<?php

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',function() {
    return redirect()->route('tasks.index');
});

Route::get('tasks', function () {
    return view('index', [
        // 'tasks' => Task::all()
        // 'tasks' => Task::latest()->get() // ini berguna untuk mencari data terbaru berdasarkan created at
        // 'tasks' => Task::latest()->where('completed', true)->get() // where dan latest adalah query builder, dan masih banyak lagi https://laravel.com/docs/10.x/queries#main-content
        'tasks' => Task::latest()->paginate(10)
    ]);
})->name('tasks.index');

Route::view('/tasks/create', 'create')
    ->name('tasks.create');

Route::put('tasks/{task}/toggle-complete', function (Task $task) {
    $task->toggleComplete();

    return redirect()->back()->with('success', 'Task updated successfully');
})->name('tasks.toggle-complete');

Route::get('tasks/{task}', function (Task $task) {
    return view('show', [
      'task' => $task
      ]
    );
})->name('tasks.show');

Route::get('tasks/{task}/edit', function (Task $task) {
    return view('edit', [
      'task' => $task
      ]
    );
})->name('tasks.edit');

Route::post('/tasks', function (TaskRequest $request) {
    // $data = $request->validated();
    // $task = new Task;
    // $task->title = $data['title'];
    // $task->description = $data['desc'];
    // $task->long_description = $data['long_desc'];
    // $task->save();
    $task = Task::create($request->validated());
    
    return redirect()->route('tasks.show', ['task' => $task->id])->with('success','Task created successfully!'); // with berguna untuk menset flash message jika data berhasil tercreate di db dan menyimpannya di session
})->name('tasks.store');

Route::put('/tasks/{task}', function (Task $task, TaskRequest $request) {
    // $data = $request->validated();
    // $task->title = $data['title'];
    // $task->description = $data['desc'];
    // $task->long_description = $data['long_desc'];
    // $task->save(); 
    $task->update($request->validated());
    
    return redirect()->route('tasks.show', ['task' => $task->id])->with('success','Task updated successfully!'); // with berguna untuk menset flash message jika data berhasil tercreate di db dan menyimpannya di session
})->name('tasks.update');

Route::delete('/tasks/{task}', function(Task $task){
    $task->delete();

    return redirect()->route('tasks.index')
        ->with('success', 'Task Deleted successfully!');
})->name('tasks.destroy');

// Route::get('/hello', function() {
//     return 'Hello';
// })->name('hello');

// Route::get('hallo', function () {
//     return redirect()->route('hello');
// });

// dynamic route
// Route::get('/greet/{name}', function ($name) {
//     return 'Hello ' . $name . '!'; 
// });

// Route::fallback(function() {
//     return 'Still got somewhere!';
// });


// ANOTHER ROUTE METHOD LIKE GET
// POST
// PUT
// DELETE