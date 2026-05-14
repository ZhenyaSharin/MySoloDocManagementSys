<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPdf\Facades\Pdf;

/*

| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::middleware('auth:api')->group(function () {

Route::post('/getuserslist', [
    'uses' => 'App\Http\Controllers\Api\UsersController@getUsersList',
]);

Route::post('/getuserbysmth', [
    'uses' => 'App\Http\Controllers\Api\UsersController@getUserBySmth',
]);

// йацс complete
Route::post('/getdocslist', [
    'uses' => 'App\Http\Controllers\AdminController@getDocumentsList',
]);
// Изменить фронт

Route::post('/updateuser', [
    'uses' => 'App\Http\Controllers\Api\AdminController@updateUser',
]);

Route::post('/adduser', [
    'uses' => 'App\Http\Controllers\Api\AdminController@addUser',
]);

Route::post('/getdeliverytypes', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@getDeliveryTypesList',
]);

Route::post('/getdocumenttypes', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@getDocumentTypesList',
]);

Route::post('/getdepartments', [
    'uses' => 'App\Http\Controllers\Api\UsersController@getDepartmentsList',
]);

// Route::post('/getdocuserslist', [
//     'uses' => 'App\Http\Controllers\Api\UsersController@getUsersList',
// ]);

Route::post('/newdocument', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@addNewDocument',
]);

Route::post('/getdocslist', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@getDocumentsList',
]);

Route::post('/getdocslistbyuser', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@getDocsListByUserId',
]);

Route::post('/getagreementslistbyuser', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@getAgreementListByUserId',
]);

Route::post('/getdocbyid', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@getDocumentById',
]);

Route::post('/updateagreement', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@updateAgreement',
]);

// йацс complete
Route::post('/getagrresponsesbyuserid', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@getAgreementResponsesByUserId',
]);

Route::post('/addagreementanduser', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@addNewAgreementAndUser',
]);

Route::post('/docintoarchive', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@updateDocumentIntoArchive',
]);

// йацс complete
Route::post('/getagreementsusersbydocid', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@getAgreementsByDocId',
]);

Route::post('/agreementsusershistory', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@getAgreementsAndUsersHistory',
]);

Route::post('/getcountnonviewed', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@getCountNonViewedAgreementsAndUsers',
]);

Route::post('/getallassignments', [
    'uses' => 'App\Http\Controllers\Api\AssignmentController@getAssignmentList',
]);

Route::post('/getassignmenttypes', [
    'uses' => 'App\Http\Controllers\Api\AssignmentController@getAssignmentTypes',
]);

Route::post('/getblogitems', [
    'uses' => 'App\Http\Controllers\Api\AdminController@getBlogItems',
]);

Route::post('/addblog', [
    'uses' => 'App\Http\Controllers\Api\AdminController@addBlogItem',
]);

Route::post('/docupdate', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@updateDocument',
]);

Route::post('/getdocslistbytype', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@getDocsListByTypeId',
]);

Route::post('/newassignment', [
    'uses' => 'App\Http\Controllers\Api\AssignmentController@addNewAssignment',
]);

Route::post('/assignmentsbyauthor', [
    'uses' => 'App\Http\Controllers\Api\AssignmentController@getAssignmentsByAuthorId',
]);

Route::post('/assignexecutors', [
    'uses' => 'App\Http\Controllers\Api\AssignmentController@getAssignmentsByExecutorId',
]);

Route::post('/updateassignment', [
    'uses' => 'App\Http\Controllers\Api\AssignmentController@updateAssignment',
]);

Route::post('/updateblogitem', [
    'uses' => 'App\Http\Controllers\Api\AdminController@updateBlogItem',
]);

Route::post('/getassignbyid', [
    'uses' => 'App\Http\Controllers\Api\AssignmentController@getAssignmentById',
]);

Route::post('/assignsnonviewed', [
    'uses' => 'App\Http\Controllers\Api\AssignmentController@getNonViewedAssignmentsByExecutorId',
]);

// author
Route::post('/getdocumentversions', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@getDocumentVersionsById',
]);

Route::post('/getassignmentversions', [
    'uses' => 'App\Http\Controllers\Api\AssignmentController@getAssignmentVersionsById',
]);

Route::get('/getpdf', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@getPdf',
]);

// getAgreerStamps
Route::get('/getagreerstamps', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@getAgreerStamp',
]);

// getAgreerList
Route::get('/getagreerlist', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@getAgreerList',
]);

Route::get('/getagreerstampswithout', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@getAgreersListWithoutUsers',
]);

Route::post('/addacquaintance', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@addNewAcquaintances',
]);

Route::post('/acquaintanceslist', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@getAcquaintancesList',
]);

Route::post('/updateacquaintance', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@updateAcquaintance',
]);

Route::post('/diruserslist', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@getDirusersList',
]);
// api/updateassignment
// Route::post('/updateassignment', [
//    'uses' => 'App\Http\Controllers\Api\AssignmentController@updateAssignment'
// ]);

Route::post('/newdeadlineassignment', [
    'uses' => 'App\Http\Controllers\Api\AssignmentController@newDeadlineAssignment',
]);
// api/updateassignexecutor
Route::post('/updateassignexecutor', [
    'uses' => 'App\Http\Controllers\Api\AssignmentController@updateAssignmentExecutor',
]);

Route::post('/assignlistbymain', [
    'uses' => 'App\Http\Controllers\Api\AssignmentController@getListByMainId',
]);

Route::post('/mail', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@mailTo',
]);

Route::post('/updateassignstatus', [
    'uses' => 'App\Http\Controllers\Api\AssignmentController@updateAssignmentStatus',
]);

Route::post('/assignstatuslog', [
    'uses' => 'App\Http\Controllers\Api\AssignmentController@getAssignmentStatusLog',
]);

Route::post('/addassigncontrol', [
    'uses' => 'App\Http\Controllers\Api\AssignmentController@addNewControl',
]);

Route::post('/updateassigncontrol', [
    'uses' => 'App\Http\Controllers\Api\AssignmentController@updateControl',
]);

Route::post('/getassignments', [
    'uses' => 'App\Http\Controllers\Api\AssignmentController@getAssignmentControls',
]);

Route::post('/updateassigndeadline', [
    'uses' => 'App\Http\Controllers\Api\AssignmentController@updateAssignmentDeadline',
]);

Route::post('/addassigndeadline', [
    'uses' => 'App\Http\Controllers\Api\AssignmentController@addAssignmentDeadline',
]);
// });

Route::post('/updatepassword', [
    'uses' => 'App\Http\Controllers\Api\AdminController@updateUserPassword',
]);

Route::post('/analyticsdocslist', [
    'uses' => 'App\Http\Controllers\Api\AnalyticsController@getDocumentsList',
]);

Route::post('/analyticsassignslist', [
    'uses' => 'App\Http\Controllers\Api\AnalyticsController@getAssignmentsList',
]);

Route::post('/departmentsuserslist', [
    'uses' => 'App\Http\Controllers\Api\UsersController@getDepartmentAnalyticsList',
]);

// getUsersAnalyticsList
Route::post('/usersanalyticslist', [
    'uses' => 'App\Http\Controllers\Api\UsersController@getUsersAnalyticsList',
]);

Route::post('/getstatuses', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@getStatuses',
]);

Route::post('/makesearch', [
    'uses' => 'App\Http\Controllers\Api\SearchController@makeSearch',
]);

Route::post('/addfileaddition', [
    'uses' => 'App\Http\Controllers\Api\FilesController@addFileAddition',
]);
// updatefileaddition
Route::post('/updatefileaddition', [
    'uses' => 'App\Http\Controllers\Api\FilesController@updateFileAddition',
]);

Route::post('/updatefilecomment', [
    'uses' => 'App\Http\Controllers\Api\FilesController@updateFileComment',
]);

Route::post('/getmailsettings', [
    'uses' => 'App\Http\Controllers\Api\UsersController@getMailSettingsList',
]);

Route::post('/addallmailsettings', [
    'uses' => 'App\Http\Controllers\Api\UsersController@addMailSettingsByUserId',
]);
//  Добавить фронтенд

Route::post('/updateagreelist', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@updateAgreementList',
]);

// removeAgreementUser
Route::post('/removeagreeuser', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@removeAgreementUser',
]);

Route::post('/updatedoc', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@updateDocumentInfo',
]);

Route::post('/checkmailsetting', [
    'uses' => 'App\Http\Controllers\Api\UsersController@checkMailSetting',
]);

Route::post('/updatemailsettingstatus', [
    'uses' => 'App\Http\Controllers\Api\UsersController@updateMailSettingStatus',
]);

Route::post('/addmailsettinguser', [
    'uses' => 'App\Http\Controllers\Api\UsersController@addMailSettingUser',
]);

Route::post('/updateassign', [
    'uses' => 'App\Http\Controllers\Api\AssignmentController@updateAssignmentInfo',
]);

Route::post('/docslistbystatus', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@getDocumentsListByStatus',
]);

Route::post('/updatedocfile', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@updateDocumentFile',
]);

Route::get('/watermark', [
    'uses' => 'App\Http\Controllers\Api\DocumentController@watermark',
]);

Route::post('/pdf', [App\Http\Controllers\Api\DocumentController::class, 'pdf'])->name('pdf');

// Route::post('/install', [
//     'uses' => 'App\Http\Controllers\Api\AdminController@install',
// ]);

Route::post('/addadmin', [
    'uses' => 'App\Http\Controllers\Api\AdminController@addAdmin',
]);

Route::post('/addrelation', [
    'uses' => 'App\Http\Controllers\Api\RelationController@addNewRelation',
]);

Route::post('/getrelations', [
    'uses' => 'App\Http\Controllers\Api\RelationController@getRelationByDocAssignId',
]);

Route::post('/updaterelation', [
    'uses' => 'App\Http\Controllers\Api\RelationController@updateRelation',
]);

Route::post('/getallrelations', [
    'uses' => 'App\Http\Controllers\Api\RelationController@getRelationsList',
]);

Route::post('/getrelationbyid', [
    'uses' => 'App\Http\Controllers\Api\RelationController@getRelationById',
]);
// getRolesList

Route::post('/getroleslist', [
    'uses' => 'App\Http\Controllers\Api\AdminController@getRolesList',
]);

Route::post('/updaterole', [
    'uses' => 'App\Http\Controllers\Api\AdminController@addRole',
]);

Route::post('/checkrole', [
    'uses' => 'App\Http\Controllers\Api\UsersController@checkUserRole',
]);