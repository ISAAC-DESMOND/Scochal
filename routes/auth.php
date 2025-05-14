<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\DirectMsgController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\SportMatchController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('/dashboard/posts', [PostController::class, 'index'])->name('post.index');
    Route::post('/dashboard/post', [PostController::class, 'store'])->name('post.store');
    Route::post('/dashboard/like', [PostController::class, 'like'])->name('post.like');
    Route::post('/dashboard/dislike', [PostController::class, 'dislike'])->name('dislike');

    Route::get('/inbox', [DirectMsgController::class, 'show'])->name('inbox.show');
    Route::get('/inbox/all', [DirectMsgController::class, 'index'])->name('inbox.index');
    Route::get('/inbox/show_all/{recipient_id}', [DirectMsgController::class, 'show_all'])->name('inbox.show_all');
    Route::get('/inbox/create/{recipient_id}', [DirectMsgController::class, 'create'])->name('inbox.create');
    Route::post('/inbox/store', [DirectMsgController::class, 'store'])->name('inbox.store');


    Route::get('/team/create', [TeamController::class, 'create'])->name('team.create');
    Route::post('/team/create_team', [TeamController::class, 'create_team'])->name('team.create_team');
    Route::get('/teams', [TeamController::class, 'index'])->name('team.all');
    Route::get('/teams/all', [TeamController::class, 'team_index'])->name('team.team_index');
    Route::post('/teams/join', [TeamController::class, 'join_team'])->name('team.join');
    Route::post('/team/store', [TeamController::class, 'store'])->name('team.store');
    Route::get('/team/chats/{team_id}', [teamController::class, 'show'])->name('team.show');
    Route::get('/team/show_all/{team_id}', [TeamController::class, 'show_chats'])->name('team.show_all');

    Route::get('/team/edit/{team_id}', [TeamController::class, 'edit'])->name('team.edit');
    Route::get('/team/member/chat/{member_id}', [TeamController::class, 'chat'])->name('team.chat');
    Route::post('/team/member/accept', [TeamController::class, 'accept'])->name('team.accept');
    Route::post('/team/member/decline', [TeamController::class, 'decline'])->name('team.decline');
    Route::post('/team/member/remove', [TeamController::class, 'remove'])->name('team.remove');
    Route::post('/team/delete/{team_id}', [TeamController::class, 'destroy'])->name('team.delete');

    Route::get('/matches/create', [SportMatchController::class, 'create'])->name('match.create');
    Route::get('/matches/edit/{match_id}',[SportMatchController::class,'edit'])->name('match.edit');
    Route::get('/matches', [SportMatchController::class, 'index'])->name('match.all');
    Route::get('/matches/all', [SportMatchController::class, 'list_all'])->name('match.listed');
    Route::post('/matches/store', [SportMatchController::class, 'store'])->name('match.store');
    Route::post('matches/negotiate', [SportMatchController::class, 'negotiate'])->name('match.negotiate');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
