<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', 'kho-barcode');

Route::view('/kho-barcode', 'kho_barcode');
