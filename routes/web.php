<?php


use App\Models\Account;
use app\Http\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;

Route::get('/', [userController::class, 'showcorrecthomepage'])->name('login');

route::get('/home', [userController::class, 'loadHomepage'])->name('home');
route::get('/filter-home-page', [userController::class, 'loadHomepage'])->name('filtered-home')->middleware('auth');;
route::get('/switch-page', [userController::class, 'loadHomepage'])->middleware('auth');;


Route::get('/signup-login', function () {

    return view('signup_login');
})->name('signup-login');





Route::get('/create-post', function () {
    $user = Account::find(auth()->user()->id);

    if (!$user->artist_status) {
        return redirect('/');
    }
    return view('create-post');
})->middleware('auth');;
Route::get('/search-collab', [userController::class, 'searchCollaborators']);

Route::get('/modify-profile', function () {

    return view('modify-profile');
})->middleware('auth');

Route::get('/admin', function () {
    $user = Account::find(auth()->user()->id);

    if (!$user->type == 'admin') {
        return redirect('/');
    }
    return view('admin');
})->middleware('auth');

Route::post('/create-user', [userController::class, 'create_user']);
Route::get('/create-user', function () {

    return redirect()->route('signup-login');
});

Route::post('/application-form', [userController::class, 'artistApplication']);
Route::post('/login', [userController::class, 'login']);
Route::get('/logout', [userController::class, 'logout'])->middleware('auth');
Route::post('/create-post', [userController::class, 'storepost'])->middleware('auth');
Route::post('/add-collaborator', [userController::class, 'select_collaborator'])->middleware('auth');
route::get('/activity-profile', [userController::class, 'loadActivityProfile'])->middleware('auth');
route::get('/profile/{username}', [userController::class, 'loadProfile'])->middleware('auth')->name('profile');
route::post('/confirm-modify-profile', [userController::class, 'modifyProfile'])->middleware('auth')->name('modify-profile');
route::get('/post/{username}/{post_id}', [userController::class, 'showsinglepost'])->name('post-card')->middleware('auth');
Route::post('/follow/{userId}', [userController::class, 'addFollow'])->middleware('auth');
Route::post('/unfollow/{userId}', [userController::class, 'unFollow'])->middleware('auth');
Route::post('/remove-follower/{userId}', [userController::class, 'removeFollower'])->middleware('auth');
Route::get('/message/{userId}', [userController::class, 'loadMessagesPage'])->name('messages-page')->middleware('auth');
Route::post('/message-user/{receiverId}', [userController::class, 'loadOrStartConversation'])->middleware('auth');
Route::get('/message/{userId}/{corresponderId}', [userController::class, 'loadMessages'])->name('messages')->middleware('auth');
Route::post('/send-message', [userController::class, 'storeMessage'])->name('send-message')->middleware('auth');
Route::get('/search-messages', [userController::class, 'searchMessages'])->middleware('auth');
Route::post('/add-comment', [userController::class, 'storeComment'])->middleware('auth');
Route::post('/add-like', [userController::class, 'addLike'])->middleware('auth');
Route::post('/remove-like', [userController::class, 'removeLike'])->middleware('auth');
Route::post('/delete-conversation', [userController::class, 'deleteConversation'])->middleware('auth');
Route::post('/delete-post', [userController::class, 'deletePost'])->middleware('auth');
Route::post('/delete-comment', [userController::class, 'deleteComment'])->middleware('auth');
Route::post('/delete-own-account', [userController::class, 'deleteOwnAccount'])->middleware('auth');
Route::post('/delete-account', [userController::class, 'deleteAccount'])->middleware('auth');
Route::post('/update-application', [userController::class, 'updateArtistApplication'])->middleware('auth')->name('artists-update');
Route::post('/search', [userController::class, 'searchResults'])->middleware('auth');

Route::post('/remove-collab', [userController::class, 'removeCollab'])->middleware('auth');
