<?php

use App\Http\Controllers\Api\DashboardChartController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContactGroupController;
use App\Http\Controllers\CronJobController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SenderController;
use App\Http\Controllers\SmsLogController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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

Route::get('/', [LoginController::class, 'showLoginForm']);
Route::get('/home', [DashboardController::class, 'index'])->name('home');
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

//login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('profile/change-password', [ProfileController::class, 'change_password'])->name('profile.change-password');
Route::post('profile/change-password', [ProfileController::class, 'store_password'])->name('profile.change-password');


//sms logs
Route::any('sms-logs/lists', [SmsLogController::class, 'lists'])->name('sms-logs.lists');
//quick sms
Route::get('sms-logs/quick-sms', [SmsLogController::class, 'quick_sms'])->name('sms-logs.quick-sms');
Route::post('sms-logs/send-quick-sms', [SmsLogController::class, 'send_quick_sms'])->name('sms-logs.send-quick-sms');
//group sms
Route::get('sms-logs/group-sms', [SmsLogController::class, 'group_sms'])->name('sms-logs.group-sms');
Route::post('sms-logs/send-group-sms', [SmsLogController::class, 'send_group_sms'])->name('sms-logs.send-group-sms');
//file sms
Route::get('sms-logs/file-sms', [SmsLogController::class, 'file_sms'])->name('sms-logs.file-sms');
Route::post('sms-logs/send-file-sms', [SmsLogController::class, 'send_file_sms'])->name('sms-logs.send-file-sms');

//cron for sending sms
Route::get('cron-jobs/send-process', [CronJobController::class, 'send_process'])->name('cron-jobs.send-process');

//cron for delivery report
Route::get('cron-jobs/delivery-report', [CronJobController::class, 'delivery_report'])->name('cron-jobs.delivery-report');

//templates
Route::get('templates/{id}/delete', [TemplateController::class, 'destroy'])->name('templates.delete');
Route::get('templates/{id}/get-data', [TemplateController::class, 'get_data'])->name('templates.get-data');

//contact
Route::any('contacts/lists', [ContactController::class, 'lists'])->name('contacts.lists');
Route::get('contacts/import', [ContactController::class, 'import'])->name('contacts.import');
Route::post('contacts/store-import', [ContactController::class, 'store_import'])->name('contacts.store-import');
Route::get('contacts/{id}/delete', [ContactController::class, 'destroy'])->name('contacts.delete');

//contact groups
Route::get('contact-groups/{id}/contacts', [ContactGroupController::class, 'contacts'])->name('contact-groups.contacts');
Route::get('contact-groups/{id}/delete', [ContactGroupController::class, 'destroy'])->name('contact-groups.delete');
Route::post('contact-groups/{id}/assign-contacts', [ContactGroupController::class, 'assign_contacts'])->name('contact-groups.assign-contacts');

Route::get('contact-groups/assign-contacts-groups', [ContactGroupController::class, 'assign_contacts_groups'])->name('contacts-groups.assign-contacts-groups');
Route::post('contact-groups/store-contacts-groups', [ContactGroupController::class, 'store_contacts_groups'])->name('contacts-groups.store-contacts-groups');

//users
Route::get('users/{id}/delete', [UserController::class, 'destroy'])->name('users.delete');

//resources routes
Route::resources([
    'roles' => RoleController::class,
    'users' => UserController::class,
    'contacts' => ContactController::class,
    'contact-groups' => ContactGroupController::class,
    'groups' => GroupController::class,
    'senders' => SenderController::class,
    'templates' => TemplateController::class,
]);

//charts
Route::get('api/dashboard/monthly_status', [DashboardChartController::class, 'monthly_status']);
