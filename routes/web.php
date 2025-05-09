<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirebaseAuthController;
use App\Http\Controllers\Firebase\ContactController;
use App\Http\Controllers\Firebase\LoginController;
use App\Http\Controllers\Firebase\HomeController;
use App\Http\Controllers\Firebase\LoginFirebaseService;
use App\Http\Controllers\Firebase\ProfileController;
use App\Http\Controllers\Firebase\ChatController;
use App\Http\Controllers\Firebase\GroupChatController;
use App\Http\Controllers\Firebase\AboutController;
use App\Http\Controllers\Firebase\CustomResetPasswordController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::middleware(['guest.firebase'])->group(function () {

    Route::get('/', function () {
        // ถ้า login แล้ว ให้เด้งไปหน้า chat
        if (session()->has('firebase_uid')) {
            return redirect('/home');
        }
        // ถ้ายังไม่ได้ login ให้แสดงหน้า login
        return view('login');
    })->name('login');

    Route::post('/', [LoginController::class, 'login'])->name('login');

    Route::post('/password/email', [CustomResetPasswordController::class, 'sendResetLink'])->name('password.custom.send');
    Route::get('/password/forgot', [CustomResetPasswordController::class, 'showRequestForm'])->name('password.custom.request');
    Route::get('/password/reset/{token}', [CustomResetPasswordController::class, 'showResetForm'])->name('password.custom.reset.form');
    Route::post('/password/reset', [CustomResetPasswordController::class, 'resetPassword'])->name('password.custom.reset');
});





Route::middleware(['firebase.auth'])->group(function () {


    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/home/post', [HomeController::class, 'store'])->name('home.post');
    Route::post('/home/pin-post', [HomeController::class, 'togglePin'])->name('home.togglePin');
    Route::delete('/home/delete-post', [HomeController::class, 'deletePost'])->name('home.deletePost');



    Route::get('/chat', function () {return view('chat');});
    Route::get('/chat', [ChatController::class, 'chatList'])->name('chat.list');
    Route::get('/chat/{groupId}', [ChatController::class, 'chatConversation'])->name('chat.conversation');
    Route::get('/chat/private/{uid}', [ChatController::class, 'privateChat'])->name('chat.private');
    
    Route::post('/chat/private/{conversationId}/send', [ChatController::class, 'sendPrivateMessage'])->name('chat.private.send');
    Route::post('/chat/group/{groupId}/send', [ChatController::class, 'sendGroupMessage'])->name('chat.group.send');

    Route::get('/chat/messages/{id}', [ChatController::class, 'loadMessages'])->name('chat.loadMessages');
    Route::get('/chat/api/messages/{id}', [ChatController::class, 'getChatMessagesJson']);




    Route::get('/groupchat', function () {return view('groupchat');});
    Route::post('groupchat', [GroupChatController::class, 'store'])->name('groupchat');

    Route::get('/groupchat', [GroupChatController::class, 'create'])->name('groupchat.create');
    Route::post('/groupchat/store', [GroupChatController::class, 'store'])->name('groupchat.store');
    Route::post('/groupchat/leave/{groupId}', [GroupChatController::class, 'leaveGroup'])->name('groupchat.leave');
    Route::post('/groupchat/delete/{groupId}', [GroupChatController::class, 'deleteGroup'])->name('groupchat.delete');
    Route::post('/groupchat/{groupId}/update', [GroupChatController::class, 'update'])->name('groupchat.update');



    Route::get('/employee', [ContactController::class, '__EmployeeTable']);
    Route::get('add-employee', [ContactController::class, 'CreateEmployee']);
    Route::post('add-employee', [ContactController::class, 'store'])->name('add-employee');
    Route::delete('/employee/{id}', [ContactController::class, 'destroy'])->name('employee.delete');
    Route::post('/employee/{id}/update', [ContactController::class, 'update'])->name('employee.update');



    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/upload', [ProfileController::class, 'upload'])->name('profile.upload');


    Route::get('/about', [AboutController::class, 'show'])->name('about.show');
    Route::post('/about', [AboutController::class, 'update'])->name('about.update');
    
});



