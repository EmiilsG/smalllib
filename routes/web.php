<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\ReaderController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/books');

Route::resource('books', BookController::class);
Route::resource('readers', ReaderController::class);
Route::resource('borrowings', BorrowingController::class)->except(['edit', 'update']);
Route::patch('borrowings/{borrowing}/return', [BorrowingController::class, 'returnBook'])->name('borrowings.return');

Route::get('/zurnals', function () {
    $logs = DB::table('zurnals')
        ->leftJoin('books', 'zurnals.gramatas_id', '=', 'books.id')
        ->select('zurnals.*', 'books.title as gramatas_nosaukums')
        ->latest('zurnals.id')
        ->paginate(20);

    return view('zurnals.index', compact('logs'));
})->name('zurnals.index');

Route::get('/kavetie', function () {
    $kavetie = DB::table('kavetie_aiznemumi')->get();
    return view('kavetie.index', compact('kavetie'));
})->name('kavetie.index');

Route::get('/sodi', function () {
    $sodi = DB::table('lasitaja_sodi')->get();
    return view('sodi.index', compact('sodi'));
})->name('sodi.index');

Route::get('/sodi/{lasitajs}', function (\App\Models\Reader $lasitajs) {
    $sods = \App\Services\SodaProcedure::aprekinatSodu($lasitajs->id);
    return view('sodi.show', compact('sods'));
})->name('sodi.show');
