<?php

use Illuminate\Support\Facades\Route;

// Purpose: Public root page that introduces the API and links to /api/health


Route::get('/', fn () => view('welcome'));
