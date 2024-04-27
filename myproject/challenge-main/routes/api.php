<?php

use App\Http\Controllers\DocumentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\SuperAdminController;

Route::middleware('guest')->group(function () {
    Route::post('/register',[ AuthController::class,'register']);
    Route::post('/login', [AuthController::class, 'Login']);
    
});


Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user/show/{user_id}', [AuthController::class, 'ShowUser']);

    Route::group(['middleware' => ['role:SuperAdmin']], function () {
        Route::post('/user/change_role/{user_id}', [SuperAdminController::class, 'ChangeRole']);

        // upload a document for a user 
        Route::post('/user/document/upload/{user_id}', [DocumentController::class, 'uploadForUser']);

        // show user documents
        Route::get('/user/document/show/{user_id}', [DocumentController::class, 'ShowUserDocs']);

    });

    // files apis
    Route::prefix('/user/document')->group(function () {
        // upload file for the authenticated user
        Route::post('/upload', [DocumentController::class, 'upload']);

        // download  documant (super admin can download every document and the others can only from download their documents)
        Route::get('/download/{document_id}', [DocumentController::class, 'download']);


        // show all files for the authenticated user
        Route::get('/show', [DocumentController::class, 'Show']);

        // show a specific document (super admin can see every document and the others can only show their documents)
        Route::get('/show/{document_id}', [DocumentController::class, 'ShowFile']);

        // delete a file
        Route::delete('/delete/{file_id}', [DocumentController::class, 'delete']);
    });

});