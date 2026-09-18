Route::get('/app', function () {
    return view('telegram.shell');
})->name('telegram.app');