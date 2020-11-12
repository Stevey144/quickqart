<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductInventoryController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\SpecialOfferController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use App\Notifications\StoreSetupCompletedNotification;
use App\Permission;
use App\Role;
use App\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$parentDomain = env("APP_BASE_URL", 'localhost:8000');

Route::group(['domain' => $parentDomain], function () {
    Route::group(['prefix' => 'password'], function () {
        Route::post('forget', [ForgotPasswordController::class, 'sendResetLinkEmail']);
        Route::group(['middleware' => 'jwt.auth'], function () {
            Route::post('change', 'PasswordResetController@doChange');
        });
    });

    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [LoginController::class, 'login'])->name('auth.login');
    });
});

Route::group(['prefix' => 'admin', 'middleware' => ['jwt.auth', 'suspension.gate']], function () {
    Route::group(['prefix' => 'users'], function () {
        Route::get('', [UserController::class, 'index']);
        Route::delete('{username}', [UserController::class, 'destroy']);
        Route::post('{username}/suspend', [UserController::class, 'suspendAccount']);
        Route::post('{username}/reactivate', [UserController::class, 'reactivateAccount']);
    });

    Route::group(['prefix' => 'faqs'], function () {
        Route::get('', [FaqController::class, 'index']);
        Route::delete('{id}', [FaqController::class, 'destroy']);
        Route::post('', [FaqController::class, 'store']);
        Route::put('{id}', [FaqController::class, 'update']);
    });


    Route::group(['prefix' => 'stores'], function () {
        Route::get('', [StoreController::class, 'index']);
        Route::get('{slug}/orders', [StoreController::class, 'storeOrders']);
        Route::get('{slug}/products', [StoreController::class, 'storeProducts']);
        Route::get('{slug}/categories', [StoreController::class, 'storeCategories']);

        Route::delete('{slug}', [StoreController::class, 'destroy']);
    });

});

Route::group(['domain' => '{subdomain}.' . $parentDomain], function () {
    Route::group(['prefix' => 'public'], function () {
        Route::post('contact-us', [ContactUsController::class, 'contact']);
        Route::get('categories', [CategoryController::class, 'publicIndex']);
        Route::get('categories/{slug}', [CategoryController::class, 'publicShow']);
        Route::get('products', [ProductController::class, 'publicIndex']);
        Route::get('products/{slug}', [ProductController::class, 'publicShow']);
        Route::get('home-data', [HomeController::class, 'getHomeData']);
        Route::get('latest/products/{count}', [ProductController::class, 'getLatestProducts']);
        Route::get('featured/products', [ProductController::class, 'getFeaturedProducts']);
        Route::get('popular/products', [ProductController::class, 'getPopularProducts']);
        Route::get('product-reviews/{productId}', [ProductReviewController::class, 'index']);
        Route::post('product-reviews/{productId}', [ProductReviewController::class, 'store']);

        Route::get('orders/{slug}', [OrderController::class, 'show']);
        Route::post('checkout', [OrderController::class, 'checkout']);
        Route::post('subscribe', [HomeController::class, 'subscribe']);
    });

    Route::get('verify-token', [AuthController::class, 'verifyToken']);

    Route::group(['middleware' => ['jwt.auth', 'suspension.gate']], function () {
        Route::post('setup/store-id', [SetupController::class, 'verifyStoreId']);
        Route::post('stores/setup', [SetupController::class, 'store']);
    });

    Route::group(['middleware' => ['jwt.auth', 'suspension.gate']], function () {
        Route::post('setup/store-id', [SetupController::class, 'verifyStoreId']);
        Route::get('', [ProductController::class, 'index']);
        Route::get('orders', [OrderController::class, 'index']);
        Route::get('orders/{slug}', [OrderController::class, 'showForAdmin']);
        Route::put('orders/{slug}/status', [OrderController::class, 'updateStatus']);
        Route::put('orders/{slug}/order-items/{item_slug}', [OrderController::class, 'updateQuantity']);
        Route::delete('orders/{slug}/order-items/{item_slug}', [OrderController::class, 'removeItem']);
        Route::delete('orders/{slug}', [OrderController::class, 'destroy']);

        Route::get('roles', function () {
            $roles = Role::query();
            if (!auth()->user()->hasRole(Role::SYSADMIN)) {
                $roles->where('name', '!=', Role::SYSADMIN);
            }

            $roles = $roles->get();
            return response()->json(['data' => $roles]);
        });
        Route::group(['prefix' => 'profile'], function () {
            Route::get('', [UserController::class, 'profile']);
            Route::get('{username}', [UserController::class, 'profile']);
            Route::put('{username}', [UserController::class, 'update']);
            Route::put('', [UserController::class, 'update']);
            Route::post('', [UserController::class, 'store']);
        });

        Route::group(['prefix' => 'categories'], function () {
            Route::get('', [CategoryController::class, 'index']);
            Route::post('', [CategoryController::class, 'store']);
            Route::put('{slugId}', "CategoryController@update");
            Route::delete('{slugId}', [CategoryController::class, 'destroy']);
        });

         Route::group(['prefix' => 'stores'], function () {
            Route::get('{slug}', [StoreController::class, 'show']);
            Route::put('{slug}', [StoreController::class, 'ownerUpdate']);
            Route::delete('{slug}', [StoreController::class, 'destroy']);

             Route::get('', [StoreController::class, 'getOwnerStores']);
        });

        Route::group(['prefix' => 'special-offers'], function () {
            Route::get('', [SpecialOfferController::class, 'index']);
            Route::post('', [SpecialOfferController::class, 'store']);
            Route::get('{slugId}', [SpecialOfferController::class, "show"]);
            Route::put('{slugId}', "SpecialOfferController@update");
            Route::delete('{slugId}', [SpecialOfferController::class, 'destroy']);
        });

        Route::group(['prefix' => 'products'], function () {
            Route::get('', [ProductController::class, 'index']);
            Route::get('', [ProductController::class, 'index']);
            Route::get('{slugId}', [ProductController::class, 'show']);
            Route::post('', [ProductController::class, 'store']);
            Route::put('{slugId}', [ProductController::class, 'update']);
            Route::put('{slugId}/featured', [ProductController::class, 'updateFeaturedStatus']);
            Route::put('{slugId}/activeness', [ProductController::class, 'updateActiveStatus']);
            Route::delete('{slugId}', [ProductController::class, 'destroy']);

            Route::get('{slugId}/inventory', [ProductInventoryController::class, 'index']);
            Route::post('{slugId}/inventory', [ProductInventoryController::class, 'store']);
        });

        Route::get('dashboard', [HomeController::class, 'getDashboardData']);
    });

    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [LoginController::class, 'login'])->name('auth.login');
    });

    Route::middleware('optimizeImages')->group(function () {
        Route::post('upload', 'UploadController@store')->name('upload.create');
    });
});
