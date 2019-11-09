<?php

use Ccbox\AdminWorkorder\Http\Controllers\WorkorderController;
use Ccbox\AdminWorkorder\Http\Controllers\TicketsController;

Route::get('admin-workorder', WorkorderController::class.'@index');
Route::resource('admin-workorder/tickets', TicketsController::class);