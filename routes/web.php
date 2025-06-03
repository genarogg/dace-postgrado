<?php

use App\Http\Controllers\CmsController;
use Illuminate\Support\Facades\Route;

/* Route::get('/', function () {
    //return view('welcome');
    return redirect(route("filament.admin.auth.login"));
}); */

Route::get('/', [CmsController::class, 'index'])->name('cms.index');
Route::get('/blog/post/{slug}', [CmsController::class, 'showPost'])->name('cms.post');
Route::get('/blog/categoria/{slug}', [CmsController::class, 'showCategory'])->name('cms.category');
Route::get('/blog/etiqueta/{slug}', [CmsController::class, 'showTag'])->name('cms.tag');
Route::get('/{slug}', [CmsController::class, 'showPage'])->name('cms.page')->where('slug', '^(?!blog).*$');
