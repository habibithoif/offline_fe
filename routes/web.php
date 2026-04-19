<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\GlobalMiddleware;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Setting\MenuController;
use App\Http\Controllers\Setting\RoleController;
use App\Http\Controllers\Setting\UsersController;
use App\Http\Controllers\Setting\AppSettingController;
use App\Http\Controllers\Setting\AppHisKalkulasiController;
use App\Http\Controllers\MasterData\PointTypeController;
use App\Http\Controllers\MasterData\PointTypeStateController;
use App\Http\Controllers\MasterData\CPointController;
use App\Http\Controllers\MasterData\RefEpCuacaController;
use App\Http\Controllers\MasterData\RefEpIndikasiController;
use App\Http\Controllers\MasterData\RefEpKategoriGgnController;
use App\Http\Controllers\MasterData\RefEpPenyebabGgnController;
use App\Http\Controllers\MasterData\RefEpStatusProteksiController;
use App\Http\Controllers\MasterData\ScdRefSistemScadaController;
use App\Http\Controllers\MasterData\RefTargetKinerjaController;

use App\Http\Controllers\Fasop\StatusRealtime\TelemeteringController;
use App\Http\Controllers\Fasop\StatusRealtime\MeterStationController;
use App\Http\Controllers\Fasop\StatusRealtime\SoeAlarmProteksiController;
use App\Http\Controllers\Fasop\StatusRealtime\RtuController;
use App\Http\Controllers\Fasop\StatusRealtime\TelesignalController;

use App\Http\Controllers\Fasop\History\TelemeteringController as HistoryTelemeteringController;
use App\Http\Controllers\Fasop\History\TelesignalController as HistoryTelesignalController;
use App\Http\Controllers\Fasop\History\MeterStationController as HistoryMeterStationController;
use App\Http\Controllers\Fasop\History\RtuController as HistoryRtuController;
use App\Http\Controllers\Fasop\History\RemoteControlController as HistoryRemoteControlController;
use App\Http\Controllers\Fasop\History\TripController as HistoryTripController;

use App\Http\Controllers\Fasop\Kinerja\TelemeteringController as KinerjaTelemeteringController;
use App\Http\Controllers\Fasop\Kinerja\TelesignalController as KinerjaTelesignalController;
use App\Http\Controllers\Fasop\Kinerja\MeterStationController as KinerjaMeterStationController;
use App\Http\Controllers\Fasop\Kinerja\RtuController as KinerjaRtuController;
use App\Http\Controllers\Fasop\Kinerja\RemoteControlController as KinerjaRemoteControlController;
use App\Http\Controllers\Fasop\Kinerja\TripController as KinerjaTripController;


use App\Http\Controllers\Dashboard\MonitoringrtuController;

// MASTER DATA - FASOP //
use App\Http\Controllers\MasterData\Fasop\MasterDataTelemeteringController;
use App\Http\Controllers\MasterData\Fasop\MasterDataTelesignalController;
use App\Http\Controllers\MasterData\Fasop\MasterDataMasterStationController;
use App\Http\Controllers\MasterData\Fasop\MasterDataRTUController;
use App\Http\Controllers\MasterData\Fasop\RefRegionController;
use App\Http\Controllers\MasterData\Fasop\MasterDataRemoteControlController;
use App\Http\Controllers\MasterData\Fasop\MasterDataTargetBulananController;

// FASOP - DASHBOARD //
use App\Http\Controllers\Fasop\Dashboard\KinerjaScadaHarianController;
use App\Http\Controllers\Fasop\Dashboard\KinerjaScadaBulananController;
use App\Http\Controllers\Fasop\Dashboard\TargetBulananController;

// OPSIS //
use App\Http\Controllers\Opsis\Tm30ColController;
use App\Http\Controllers\Opsis\Tm30RowController;

use App\Http\Controllers\Auth\LoginController;
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.show');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

use App\Http\Controllers\CaptchaController;
Route::get('/captcha-image', [CaptchaController::class, 'image']);

Route::middleware([GlobalMiddleware::class])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home.index');
    Route::get('/dashboard/monitoringrtu', [MonitoringrtuController::class, 'index'])->name('dash-monitoringrtu.index');

    // export excel
    Route::post('/export/save-file', [ExportController::class, 'saveFile']);

    Route::prefix('setting')->group(function () {
        Route::get('/menu', [MenuController::class, 'index'])->name('setting-menu.index');
        Route::post('/menu/read', [MenuController::class, 'read'])->name('setting-menu.read');
        Route::post('/menu/store', [MenuController::class, 'store'])->name('setting-menu.store');
        Route::get('/menu/detail/{id}', [MenuController::class, 'detail'])->name('setting-menu.detail');
        Route::post('/menu/update', [MenuController::class, 'update'])->name('setting-menu.update');
        Route::delete('/menu/{id}', [MenuController::class, 'destroy'])->name('setting-menu.destroy');
        Route::get('/menu/read_all', [MenuController::class, 'read_all'])->name('setting-menu.read_all');
        
        // Route::get('/roles', [RolesController::class, 'index'])->name('setting-roles.index');
        // Route::post('/roles/read', [RolesController::class, 'read'])->name('setting-roles.read');
        // Route::post('/roles/store', [RolesController::class, 'store'])->name('setting-roles.store');
        // Route::get('/roles/detail/{id}', [RolesController::class, 'detail'])->name('setting-roles.detail');
        // Route::get('/roles/read_access/{id}', [RolesController::class, 'read_access'])->name('setting-roles.read_access');
        // Route::post('/roles/update', [RolesController::class, 'update'])->name('setting-roles.update');
        // Route::delete('/roles/{id}', [RolesController::class, 'destroy'])->name('setting-roles.destroy');
        // Route::post('/roles/update_access', [RolesController::class, 'update_access'])->name('setting-roles.update_access');

        Route::prefix('roles')->group(function () {
            Route::get('/read', [RoleController::class, 'read'])->name('roles.read');
            Route::resource('/', RoleController::class)->parameters(['' => 'role']);
            Route::get('/detail/{id}', [RoleController::class, 'detail'])->name('roles.detail');
            Route::post('/update_access', [RoleController::class, 'update_access'])->name('roles.update_access');
            Route::get('/read_access/{id}', [RoleController::class, 'read_access'])->name('setting-roles.read_access');
            Route::post('/{id}', [RoleController::class, 'update'])->name('setting-roles.update');
        });        
        
        Route::get('/users', [UsersController::class, 'index'])->name('setting-users.index');
        Route::get('/users/read', [UsersController::class, 'read'])->name('setting-users.read');
        Route::post('/users/store', [UsersController::class, 'store'])->name('setting-users.store');
        Route::get('/users/detail/{id}', [UsersController::class, 'detail'])->name('setting-users.detail');
        Route::get('/users/read_role/{id}', [UsersController::class, 'read_role'])->name('setting-users.read_role');
        Route::post('/users/update', [UsersController::class, 'update'])->name('setting-users.update');
        Route::delete('/users/{id}', [UsersController::class, 'destroy'])->name('setting-users.destroy');

        Route::get('/app-settings/read', [AppSettingController::class, 'read'])->name('app-settings.read');
        Route::resource('app-settings', AppSettingController::class);
        Route::post('/app-settings/update/{id}', [AppSettingController::class, 'update'])->name('app-settings.update');
    });

    // Route::prefix('masterdata')->group(function () {
    //     Route::get('/pointtype/findValueBy', [PointTypeController::class, 'findValueBy'])->name('pointtype.findValueBy');
    //     Route::get('/pointtype', [PointTypeController::class, 'index'])->name('masterdata-pointtype.index');
    //     Route::get('/pointtype/read', [PointTypeController::class, 'read'])->name('masterdata-pointtype.read');
    //     Route::post('/pointtype/store', [PointTypeController::class, 'store'])->name('masterdata-pointtype.store');
    //     Route::get('/pointtype/detail/{id}', [PointTypeController::class, 'detail'])->name('masterdata-pointtype.detail');
    //     Route::post('/pointtype/update', [PointTypeController::class, 'update'])->name('masterdata-pointtype.update');
    //     Route::delete('/pointtype/{id}', [PointTypeController::class, 'destroy'])->name('masterdata-pointtype.destroy');
    //     Route::get('/pointtypestate/read', [PointTypeStateController::class, 'read'])->name('masterdata-pointtypestate.read');
    //     Route::get('/pointtypestate', [PointTypeStateController::class, 'index'])->name('masterdata-pointtypestate.index');
    //     Route::post('/pointtypestate/store', [PointTypeStateController::class, 'store'])->name('masterdata-pointtypestate.store');
    //     Route::get('/pointtypestate/detail/{id}', [PointTypeStateController::class, 'detail'])->name('masterdata-pointtypestate.detail');
    //     Route::post('/pointtypestate/update', [PointTypeStateController::class, 'update'])->name('masterdata-pointtypestate.update');
    //     Route::delete('/pointtypestate/{id}', [PointTypeStateController::class, 'destroy'])->name('masterdata-pointtypestate.destroy');
    //     Route::post('/pointtypestate/copy', [PointTypeStateController::class, 'copy'])->name('masterdata-pointtypestate.copy');
    // });

    Route::prefix('master-data')->group(function () {
        Route::prefix('fasop')->group(function () {
            Route::get('/telemetering', [MasterDataTelemeteringController::class, 'index'])->name('masterdata-fasop-telemetering.index');
            Route::get('/telemetering/read', [MasterDataTelemeteringController::class, 'read'])->name('masterdata-fasop-telemetering.read');
            Route::post('/telemetering/store', [MasterDataTelemeteringController::class, 'store'])->name('masterdata-fasop-telemetering.store');
            Route::post('/telemetering/update', [MasterDataTelemeteringController::class, 'update'])->name('masterdata-fasop-telemetering.update');
            Route::delete('/telemetering/{id}', [MasterDataTelemeteringController::class, 'destroy'])->name('masterdata-fasop-telemetering.destroy');

            Route::get('/telesignal', [MasterDataTelesignalController::class, 'index'])->name('masterdata-fasop-telesignal.index');
            Route::get('/telesignal/read', [MasterDataTelesignalController::class, 'read'])->name('masterdata-fasop-telesignal.read');
            Route::post('/telesignal/store', [MasterDataTelesignalController::class, 'store'])->name('masterdata-fasop-telesignal.store');
            Route::post('/telesignal/update', [MasterDataTelesignalController::class, 'update'])->name('masterdata-fasop-telesignal.update');
            Route::delete('/telesignal/{id}', [MasterDataTelesignalController::class, 'destroy'])->name('masterdata-fasop-telesignal.destroy');

            Route::get('/master-station', [MasterDataMasterStationController::class, 'index'])->name('masterdata-fasop-master-station.index');
            Route::get('/master-station/read', [MasterDataMasterStationController::class, 'read'])->name('masterdata-fasop-master-station.read');
            Route::post('/master-station/store', [MasterDataMasterStationController::class, 'store'])->name('masterdata-fasop-master-station.store');
            Route::post('/master-station/update', [MasterDataMasterStationController::class, 'update'])->name('masterdata-fasop-master-station.update');
            Route::delete('/master-station/{id}', [MasterDataMasterStationController::class, 'destroy'])->name('masterdata-fasop-master-station.destroy');

            Route::get('/rtu', [MasterDataRTUController::class, 'index'])->name('masterdata-fasop-rtu.index');
            Route::get('/rtu/read', [MasterDataRTUController::class, 'read'])->name('masterdata-fasop-rtu.read');
            Route::post('/rtu/store', [MasterDataRTUController::class, 'store'])->name('masterdata-fasop-rtu.store');
            Route::post('/rtu/update', [MasterDataRTUController::class, 'update'])->name('masterdata-fasop-rtu.update');
            Route::delete('/rtu/{id}', [MasterDataRTUController::class, 'destroy'])->name('masterdata-fasop-rtu.destroy');


            Route::get('/region/read', [RefRegionController::class, 'read'])->name('region.read');
            Route::resource('region', RefRegionController::class);
            Route::post('/region/update/{id}', [RefRegionController::class, 'update'])->name('region.update');

            
            Route::get('/remote-control', [MasterDataRemoteControlController::class, 'index'])->name('masterdata-fasop-remote-control.index');
            Route::get('/remote-control/read', [MasterDataRemoteControlController::class, 'read'])->name('masterdata-fasop-remote-control.read');
            Route::post('/remote-control/store', [MasterDataRemoteControlController::class, 'store'])->name('masterdata-fasop-remote-control.store');
            Route::post('/remote-control/update', [MasterDataRemoteControlController::class, 'update'])->name('masterdata-fasop-remote-control.update');
            Route::delete('/remote-control/{id}', [MasterDataRemoteControlController::class, 'destroy'])->name('masterdata-fasop-remote-control.destroy');

            Route::get('/target-bulanan', [MasterDataTargetBulananController::class, 'index'])->name('masterdata-fasop-target-bulanan.index');
            Route::get('/target-bulanan/read', [MasterDataTargetBulananController::class, 'read'])->name('masterdata-fasop-target-bulanan.read');
            Route::post('/target-bulanan/store', [MasterDataTargetBulananController::class, 'store'])->name('masterdata-fasop-target-bulanan.store');
            Route::post('/target-bulanan/update', [MasterDataTargetBulananController::class, 'update'])->name('masterdata-fasop-target-bulanan.update');
            Route::delete('/target-bulanan/{id}', [MasterDataTargetBulananController::class, 'destroy'])->name('masterdata-fasop-target-bulanan.destroy');

            

        });

        Route::get('/cpoint/findValueBy', [CPointController::class, 'findValueBy'])->name('cpoint.findValueBy');
        Route::get('/cpoint/read', [CPointController::class, 'read'])->name('cpoint.read');
        Route::resource('cpoint', CPointController::class);
        Route::get('/pointtype/findValueBy', [PointTypeController::class, 'findValueBy'])->name('pointtype.findValueBy');
        Route::get('/pointtype', [PointTypeController::class, 'index'])->name('masterdata-pointtype.index');
        Route::get('/pointtype/read', [PointTypeController::class, 'read'])->name('masterdata-pointtype.read');
        Route::post('/pointtype/store', [PointTypeController::class, 'store'])->name('masterdata-pointtype.store');
        Route::get('/pointtype/detail/{id}', [PointTypeController::class, 'detail'])->name('masterdata-pointtype.detail');
        Route::post('/pointtype/update', [PointTypeController::class, 'update'])->name('masterdata-pointtype.update');
        Route::delete('/pointtype/{id}', [PointTypeController::class, 'destroy'])->name('masterdata-pointtype.destroy');
        Route::get('/pointtypestate/read', [PointTypeStateController::class, 'read'])->name('masterdata-pointtypestate.read');
        Route::get('/pointtypestate', [PointTypeStateController::class, 'index'])->name('masterdata-pointtypestate.index');
        Route::post('/pointtypestate/store', [PointTypeStateController::class, 'store'])->name('masterdata-pointtypestate.store');
        Route::get('/pointtypestate/detail/{id}', [PointTypeStateController::class, 'detail'])->name('masterdata-pointtypestate.detail');
        Route::post('/pointtypestate/update', [PointTypeStateController::class, 'update'])->name('masterdata-pointtypestate.update');
        Route::delete('/pointtypestate/{id}', [PointTypeStateController::class, 'destroy'])->name('masterdata-pointtypestate.destroy');
        Route::post('/pointtypestate/copy', [PointTypeStateController::class, 'copy'])->name('masterdata-pointtypestate.copy');

    });

    Route::prefix('fasop')->group(function () {
        Route::prefix('dashboard')->group(function () {
            Route::get('/kinerja-scada-harian', [KinerjaScadaHarianController::class, 'index'])->name('fasop.dashboard.kinerja-scada-harian.index');
            Route::get('/kinerja-scada-harian/read', [KinerjaScadaHarianController::class, 'read'])->name('fasop.dashboard.kinerja-scada-harian.read');

            Route::get('/kinerja-scada-bulanan', [KinerjaScadaBulananController::class, 'index'])->name('fasop.dashboard.kinerja-scada-bulanan.index');
            Route::get('/kinerja-scada-bulanan/read', [KinerjaScadaBulananController::class, 'read'])->name('fasop.dashboard.kinerja-scada-bulanan.read');

            Route::get('/target-bulanan', [TargetBulananController::class, 'index'])->name('fasop.dashboard.target-bulanan.index');
            Route::get('/target-bulanan/read', [TargetBulananController::class, 'read'])->name('fasop.dashboard.target-bulanan.read');
        });

        Route::prefix('histories')->group(function () {

            Route::get('/soe-alarm-proteksi', [SoeAlarmProteksiController::class, 'index'])->name('fasop.realtime.soe-alarm-proteksi.index');
            Route::get('/soe-alarm-proteksi/read', [SoeAlarmProteksiController::class, 'read'])->name('fasop.realtime.soe-alarm-proteksi.read');

            Route::get('/telemetering', [HistoryTelemeteringController::class, 'index'])->name('fasop.histories.telemetering.index');
            Route::get('/telemetering/read', [HistoryTelemeteringController::class, 'read'])->name('fasop.histories.telemetering.read');

            Route::get('/telesignals', [HistoryTelesignalController::class, 'index'])->name('fasop.histories.telesignals.index');
            Route::get('/telesignals/read', [HistoryTelesignalController::class, 'read'])->name('fasop.histories.telesignals.read');

            Route::get('/rtu', [HistoryRtuController::class, 'index'])->name('fasop.histories.rtu.index');
            Route::get('/rtu/read', [HistoryRtuController::class, 'read'])->name('fasop.histories.rtu.read');

            Route::get('/master-stations', [HistoryMeterStationController::class, 'index'])->name('fasop.histories.master-stations.index');
            Route::get('/master-stations/read', [HistoryMeterStationController::class, 'read'])->name('fasop.histories.master-stations.read');
            
            Route::get('/remote-control', [HistoryRemoteControlController::class, 'index'])->name('fasop.histories.remote-control.index');
            Route::get('/remote-control/read', [HistoryRemoteControlController::class, 'read'])->name('fasop.histories.remote-control.read');

            Route::get('/trip', [HistoryTripController::class, 'index'])->name('fasop.histories.trip.index');
            Route::get('/trip/read', [HistoryTripController::class, 'read'])->name('fasop.histories.trip.read');
        });

        Route::prefix('kinerja')->group(function () {
            Route::get('/telemetering', [KinerjaTelemeteringController::class, 'index'])->name('fasop.kinerja.telemetering.index');
            Route::get('/telemetering/read', [KinerjaTelemeteringController::class, 'read'])->name('fasop.kinerja.telemetering.read');

            Route::get('/telesignals', [KinerjaTelesignalController::class, 'index'])->name('fasop.kinerja.telesignals.index');
            Route::get('/telesignals/read', [KinerjaTelesignalController::class, 'read'])->name('fasop.kinerja.telesignals.read');

            Route::get('/rtu', [KinerjaRtuController::class, 'index'])->name('fasop.kinerja.rtu.index');
            Route::get('/rtu/read', [KinerjaRtuController::class, 'read'])->name('fasop.kinerja.rtu.read');

            Route::get('/master-stations', [KinerjaMeterStationController::class, 'index'])->name('fasop.kinerja.master-stations.index');
            Route::get('/master-stations/read', [KinerjaMeterStationController::class, 'read'])->name('fasop.kinerja.master-stations.read');

            Route::get('/remote-control', [KinerjaRemoteControlController::class, 'index'])->name('fasop.kinerja.remote-control.index');
            Route::get('/remote-control/read', [KinerjaRemoteControlController::class, 'read'])->name('fasop.kinerja.remote-control.read');

            Route::get('/trip', [KinerjaTripController::class, 'index'])->name('fasop.kinerja.trip.index');
            Route::get('/trip/read', [KinerjaTripController::class, 'read'])->name('fasop.kinerja.trip.read');
        });

        Route::prefix('realtime')->group(function () {
            Route::get('/telemetering', [TelemeteringController::class, 'index'])->name('fasop.realtime.telemetering.index');
            Route::get('/telemetering/read', [TelemeteringController::class, 'read'])->name('fasop.realtime.telemetering.read');

            Route::get('/telesignals', [TelesignalController::class, 'index'])->name('fasop.realtime.telesignals.index');
            Route::get('/telesignals/read', [TelesignalController::class, 'read'])->name('fasop.realtime.telesignals.read');

            Route::get('/rtu', [RtuController::class, 'index'])->name('fasop.realtime.rtu.index');
            Route::get('/rtu/read', [RtuController::class, 'read'])->name('fasop.realtime.rtu.read');

            Route::get('/master-stations', [MeterStationController::class, 'index'])->name('fasop.realtime.meter-stations.index');
            Route::get('/master-stations/read', [MeterStationController::class, 'read'])->name('fasop.realtime.meter-stations.read');
        });
    });


    Route::prefix('opsis')->group(function () {
        Route::get('/tm-30-col', [Tm30ColController::class, 'index'])->name('opsis.tm-30-col.index');
        Route::get('/tm-30-col/read', [Tm30ColController::class, 'read'])->name('opsis.tm-30-col.read');

        Route::get('/tm-30-row', [Tm30RowController::class, 'index'])->name('opsis.tm-30-row.index');
        Route::get('/tm-30-row/read', [Tm30RowController::class, 'read'])->name('opsis.tm-30-row.read');
    });
});