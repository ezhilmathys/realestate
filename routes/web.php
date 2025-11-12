<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\FrontpageController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\Agent\DashboardController as AgentController;
use App\Http\Controllers\Admin\DashboardController as AdminAgentController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Agent\MessageController;
use App\Http\Controllers\Agent\PropertyController;

// ==============================
// FRONT-END ROUTES
// ==============================


Route::get('/', [FrontpageController::class, 'index'])->name('home');
Route::get('/slider', [FrontpageController::class, 'slider'])->name('slider.index');
Route::get('/search', [FrontpageController::class, 'search'])->name('search');

Route::get('/property', [PagesController::class, 'properties'])->name('property');
Route::get('/property/{id}', [PagesController::class, 'propertieshow'])->name('property.show');
Route::post('/property/message', [PagesController::class, 'messageAgent'])->name('property.message');
Route::post('/property/comment/{id}', [PagesController::class, 'propertyComments'])->name('property.comment');
Route::post('/property/rating', [PagesController::class, 'propertyRating'])->name('property.rating');
Route::get('/property/city/{cityslug}', [PagesController::class, 'propertyCities'])->name('property.city');

Route::get('/agents', [PagesController::class, 'agents'])->name('agents');
Route::get('/agents/{id}', [PagesController::class, 'agentshow'])->name('agents.show');

Route::get('/gallery', [PagesController::class, 'gallery'])->name('gallery');

Route::get('/blog', [PagesController::class, 'blog'])->name('blog');
Route::get('/blog/{id}', [PagesController::class, 'blogshow'])->name('blog.show');
Route::post('/blog/comment/{id}', [PagesController::class, 'blogComments'])->name('blog.comment');
Route::get('/blog/categories/{slug}', [PagesController::class, 'blogCategories'])->name('blog.categories');
Route::get('/blog/tags/{slug}', [PagesController::class, 'blogTags'])->name('blog.tags');
Route::get('/blog/author/{username}', [PagesController::class, 'blogAuthor'])->name('blog.author');

Route::get('/contact', [PagesController::class, 'contact'])->name('contact');
Route::post('/contact', [PagesController::class, 'messageContact'])->name('contact.message');

Auth::routes();

// ==============================
// ADMIN ROUTES (Laravel 9 syntax)
// ==============================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard', [AdminAgentController::class, 'index'])->name('dashboard');

    Route::resource('tags', \App\Http\Controllers\Admin\TagController::class);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);
    Route::resource('features', FeatureController::class);
    Route::resource('properties', AdminPropertyController::class);
    Route::resource('services', ServiceController::class);


    Route::post('properties/gallery/delete', [AdminPropertyController::class, 'galleryImageDelete'])->name('gallery-delete');

    Route::resource('sliders', \App\Http\Controllers\Admin\SliderController::class);
    Route::resource('testimonials', \App\Http\Controllers\Admin\TestimonialController::class);

    Route::get('galleries/album', [\App\Http\Controllers\Admin\GalleryController::class, 'album'])->name('album');
    Route::post('galleries/album/store', [\App\Http\Controllers\Admin\GalleryController::class, 'albumStore'])->name('album.store');
    Route::get('galleries/{id}/gallery', [\App\Http\Controllers\Admin\GalleryController::class, 'albumGallery'])->name('album.gallery');
    Route::post('galleries', [\App\Http\Controllers\Admin\GalleryController::class, 'Gallerystore'])->name('galleries.store');

    Route::get('settings', [AdminAgentController::class, 'settings'])->name('settings');
    Route::post('settings', [AdminAgentController::class, 'settingStore'])->name('settings.store');

    Route::get('profile', [AdminAgentController::class, 'profile'])->name('profile');
    Route::post('profile', [AdminAgentController::class, 'profileUpdate'])->name('profile.update');

    Route::get('message', [AdminAgentController::class, 'message'])->name('message');
    Route::get('message/read/{id}', [AdminAgentController::class, 'messageRead'])->name('message.read');
    Route::get('message/replay/{id}', [AdminAgentController::class, 'messageReplay'])->name('message.replay');
    Route::post('message/replay', [AdminAgentController::class, 'messageSend'])->name('message.send');
    Route::post('message/readunread', [AdminAgentController::class, 'messageReadUnread'])->name('message.readunread');
    Route::delete('message/delete/{id}', [AdminAgentController::class, 'messageDelete'])->name('messages.destroy');
    Route::post('message/mail', [AdminAgentController::class, 'contactMail'])->name('message.mail');

    Route::get('changepassword', [AdminAgentController::class, 'changePassword'])->name('changepassword');
    Route::post('changepassword', [AdminAgentController::class, 'changePasswordUpdate'])->name('changepassword.update');
});

// ==============================
// AGENT ROUTES
// ==============================
Route::prefix('agent')->name('agent.')->middleware(['auth', 'agent'])->group(function () {
    Route::get('dashboard', [AgentController::class, 'index'])->name('dashboard');
    Route::get('profile', [AgentController::class, 'profile'])->name('profile');
    Route::post('profile', [AgentController::class, 'profileUpdate'])->name('profile.update');
    Route::get('changepassword', [AgentController::class, 'changePassword'])->name('changepassword');
    Route::post('changepassword', [AgentController::class, 'changePasswordUpdate'])->name('changepassword.update');
    Route::resource('properties', PropertyController::class);
    Route::post('properties/gallery/delete', [PropertyController::class, 'galleryImageDelete'])->name('gallery-delete');

    // Agent Messages
    Route::get('message', [AgentController::class, 'message'])->name('message');
    Route::get('message/read/{id}', [AgentController::class, 'messageRead'])->name('message.read');
    Route::get('message/replay/{id}', [AgentController::class, 'messageReplay'])->name('message.replay');
    Route::post('message/replay', [AgentController::class, 'messageSend'])->name('message.send');
    Route::post('message/readunread', [AgentController::class, 'messageReadUnread'])->name('message.readunread');
    Route::delete('message/delete/{id}', [AgentController::class, 'messageDelete'])->name('messages.destroy');
    Route::post('message/mail', [AgentController::class, 'contactMail'])->name('message.mail');
});

// ==============================
// USER ROUTES
// ==============================
Route::prefix('user')->name('user.')->middleware(['auth', 'user'])->group(function () {
    Route::get('dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', [UserDashboardController::class, 'profile'])->name('profile');
    Route::post('profile', [UserDashboardController::class, 'profileUpdate'])->name('profile.update');
    Route::get('changepassword', [UserDashboardController::class, 'changePassword'])->name('changepassword');
    Route::post('changepassword', [UserDashboardController::class, 'changePasswordUpdate'])->name('changepassword.update');

    // User Messages
    Route::get('message', [UserDashboardController::class, 'message'])->name('message');
    Route::get('message/read/{id}', [UserDashboardController::class, 'messageRead'])->name('message.read');
    Route::get('message/replay/{id}', [UserDashboardController::class, 'messageReplay'])->name('message.replay');
    Route::post('message/replay', [UserDashboardController::class, 'messageSend'])->name('message.send');
    Route::post('message/readunread', [UserDashboardController::class, 'messageReadUnread'])->name('message.readunread');
    Route::delete('message/delete/{id}', [UserDashboardController::class, 'messageDelete'])->name('messages.destroy');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
