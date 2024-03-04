<?php

use App\Http\Controllers\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BikeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ModalController;
// use App\Http\Controllers\KreditController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ConsumerController;
use App\Http\Controllers\KwitansiController;
use App\Http\Controllers\ChartDataController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\CetakTagihanController;
// use App\Http\Controllers\ListRegOrderController;
// use App\Http\Controllers\RegOrderKreditController;
use App\Http\Controllers\LaporanPembelianController;
use App\Http\Controllers\MailController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('index');
// });
Route::get('/', [Dashboard::class, 'index'])->middleware('auth');
Route::get('/home', [Dashboard::class, 'index'])->middleware('auth')->name('home')->middleware('auth');

Route::resource('/auth', AuthController::class)->middleware('guest');
Route::get('/auth/create', [AuthController::class, 'register'])->middleware('admin');
Route::get('/auth', [AuthController::class, 'index'])->name('login')->middleware('guest');

// PEMBELIAN
Route::resource('/pembelian', PembelianController::class)->middleware('pembelian');
// Cek apakah konsumen sudah terdaftar
Route::get('/cekNIK', [PembelianController::class, 'cek_nik'])->middleware('auth');
// Edit Data
Route::get('/edit-transaksi/{buy:unique}', [PembelianController::class, 'page_edit'])->middleware('pembelian');
//Ambil Data Transaksi
Route::get('/getDataTransaksi', [PembelianController::class, 'get_transaksi'])->middleware('auth');
//Load Individu
// Route::get('/loadIndividu', [PembelianController::class, 'load_individu'])->middleware('auth');

// DATA MOTOR
Route::resource('/motor', BikeController::class)->middleware('master');
//Ambil Data Motor
Route::get('/getDataMotor', [BikeController::class, 'get_motor'])->middleware('auth');

// PROFILE
Route::resource('/profile', ProfileController::class)->middleware('auth');
Route::post('/updateProfile', [ProfileController::class, 'update_data'])->middleware('auth');

// MODAL
Route::resource('/modal', ModalController::class)->middleware('modal');
//refresh page modal
Route::get('/refreshPage', [ModalController::class, 'refresh_page'])->middleware('auth');

// LAPORAN
Route::resource('/laporanPenjualan', LaporanController::class)->middleware('laporan');
// LAPORAN PEMBELIAN
Route::get('/laporanPembelian', [LaporanController::class, 'index_pembelian'])->middleware('laporan');

// DATA KONSUMEN
Route::resource('/consumer', ConsumerController::class)->middleware('master');

// PENJUALAN
//Rules
Route::resource('/penjualan', PenjualanController::class)->middleware('penjualan');
//Rules Penjualan
Route::post('/rulesPenjualan', [PenjualanController::class, 'rules_penjualan'])->middleware('penjualan');
//Tambah Penjualan Cash
Route::post('/tambahPenjualan', [PenjualanController::class, 'tambah_data'])->middleware('penjualan');
//Edit Penjualan
Route::get('/ambilDataPenjualan', [PenjualanController::class, 'get_data'])->middleware('penjualan');
//Edit Penjualan
Route::get('/getDataSele', [PenjualanController::class, 'get_data_detail'])->middleware('penjualan');
//Edit Penjualan Kredit
// Route::get('/getDataKredit', [KreditController::class, 'get_data'])->middleware('penjualan');
//Update Penjualan
Route::post('/updatePenjualan', [PenjualanController::class, 'update_data'])->middleware('penjualan');
//Cek Nik Penjual
Route::get('/cekNikPembeli', [PenjualanController::class, 'cek_nik'])->middleware('penjualan');
//Retur Motor
Route::get('/returMotor/{sele:unique}', [PenjualanController::class, 'retur_motor'])->middleware('penjualan');
//Retur Motor Kredit
// Route::get('/returMotorKredit/{kredit:unique}', [KreditController::class, 'retur_motor'])->middleware('penjualan');
//Refresh no polisi
Route::get('/refresh_no_polisi', [PenjualanController::class, 'refresh_no_polisi'])->middleware('penjualan');
//Ketika no register dipilih
// Route::get('/getListOrder', [KreditController::class, 'get_list_order'])->middleware('penjualan');
//Memasukan semua data register order
// Route::get('/getDataListOrderKredit', [KreditController::class, 'get_list_order_kredit'])->middleware('penjualan');

// PENJUALAN KREDIT
// Route::resource('/kredit', KreditController::class)->middleware('penjualan');
//Edit Penjualan
// Route::get('/getDataKredit', [KreditController::class, 'get_data'])->middleware('penjualan');

// SETTING
Route::resource('/setting', SettingController::class)->middleware('setting');

// REG ORDER KREDIT
// Route::resource('/regorderkredit', RegOrderKreditController::class)->middleware('register');
// Route::get('/getDataBuyerRegOrder', [RegOrderKreditController::class, 'get_data_buyer'])->middleware('auth');

// LIST REGISTER ORDER KREDIT
// Route::resource('/listRegOrder', ListRegOrderController::class)->middleware('auth');
// //get data list order
// Route::get('/getDataListOrder', [ListRegOrderController::class, 'get_data_list_order'])->middleware('auth');
// //get data list order
// Route::post('/listRegOrderUpdate', [ListRegOrderController::class, 'get_data_list_order_update'])->middleware('auth');
// //order disetujui
// Route::get('/statusDiSetujui', [ListRegOrderController::class, 'status_setuju'])->middleware('auth');
// //order ditolak
// Route::get('/statusDiTolak', [ListRegOrderController::class, 'status_tolak'])->middleware('auth');
// //order diproses
// Route::get('/statusDiProses', [ListRegOrderController::class, 'status_proses'])->middleware('auth');

// MAINTENANCE
Route::resource('/maintenance', MaintenanceController::class)->middleware('master');
Route::get('/getDataMaintenance', [MaintenanceController::class, 'get_maintenance'])->middleware('auth');
Route::get('/laporanMaintenance/{bike:unique}', [MaintenanceController::class, 'laporan_rekondisi_motor'])->middleware('auth');

// AUTHENTIKASI
// Login
// Route::post('/authenticate', [AuthController::class, 'authenticate']);
// Logout
Route::get('/logout', [AuthController::class, 'logout']);
// Register
Route::get('/register', [AuthController::class, 'index']);
Route::post('/register', [AuthController::class, 'store']);
//change password
Route::post('/changePassword', [AuthController::class, 'update_password'])->middleware('admin');
Route::post('/updateRoles', [UserController::class, 'update_roles'])->middleware('admin');
Route::get('/changeRoles', [UserController::class, 'change_roles'])->middleware('admin');
//otp
Route::post('/send-otp', [MailController::class, 'sendOtp'])->name('send-otp');
Route::get('/otp', function () {
    return view('otp');
})->name('otp')->middleware('guest');
Route::post('/check-otp', [MailController::class, 'checkOtp'])->name('check-otp');
Route::get('/resend-otp', [MailController::class, 'resendOtp'])->name('resend-otp');



// DATATABLES
Route::get('/datatablesPembelian', [PembelianController::class, 'dataTables'])->middleware('auth');
Route::get('/datatablesMotor', [BikeController::class, 'dataTables'])->middleware('auth');
Route::get('/datatablesIndividu', [ConsumerController::class, 'dataTables'])->middleware('auth');
Route::get('/datatablesDealer', [ConsumerController::class, 'dataTables2'])->middleware('auth');
Route::get('/dataTablesMotor', [ConsumerController::class, 'dataTablesMotor'])->middleware('auth');
Route::get('/dataTablesReady', [BikeController::class, 'dataTablesReady'])->middleware('auth');
Route::get('/dataTablesTerjual', [BikeController::class, 'dataTablesTerjual'])->middleware('auth');
Route::get('/dataTablesTerjualKredit', [BikeController::class, 'dataTablesTerjualKredit'])->middleware('auth');
Route::get('/dataTablesPenjualan', [PenjualanController::class, 'dataTables'])->middleware('auth');
// Route::get('/dataTablesPenjualanKredit', [KreditController::class, 'dataTables'])->middleware('auth');
Route::get('/dataTablesMaintenance', [MaintenanceController::class, 'dataTables'])->middleware('auth');
// Route::get('/datatablesRegOrderKredit', [RegOrderKreditController::class, 'dataTables'])->middleware('auth');
// Route::get('/listPengajualOrder', [ListRegOrderController::class, 'dataTables'])->middleware('auth');
Route::get('/datatablesRoles', [RoleController::class, 'dataTables'])->middleware('auth');
Route::get('/datatablesAccess', [RoleController::class, 'dataTablesAccess'])->middleware('auth');
Route::get('/datatablesUser', [UserController::class, 'dataTablesUser'])->middleware('auth');


//CETAK PDF
//Penjualan Cash Berdasar tanggal
Route::post('/penjualanDate', [PDFController::class, 'cetak_penjualan_cash_date'])->middleware('auth');
Route::get('/laporanNasabah', [PDFController::class, 'cetak_nasabah'])->middleware('auth');
//Penjualan Hari Ini
Route::get('/penjualanDay', [PDFController::class, 'cetak_day'])->middleware('auth');
//Penjualan Minggu Ini
Route::get('/penjualanWeek', [PDFController::class, 'cetak_week'])->middleware('auth');
//Penjualan BUlan Ini
Route::get('/penjualanMonth', [PDFController::class, 'cetak_month'])->middleware('auth');
//Penjualan Bulan Ini(Select)
Route::post('penjualanSelectMonth', [PDFController::class, 'cetak_select_month'])->middleware('auth');

//CETAK PDF CASH ONLY
//Penjualan Cash Berdasar tanggal
Route::post('/penjualanDateCash', [PDFController::class, 'cetak_penjualan_cash_date_only'])->middleware('auth');
//Penjualan Hari Ini
Route::get('/penjualanDayCash', [PDFController::class, 'cetak_day_cash_only'])->middleware('auth');
//Penjualan Minggu Ini
Route::get('/penjualanWeekCash', [PDFController::class, 'cetak_week_cash_only'])->middleware('auth');
//Penjualan BUlan Ini
Route::get('/penjualanMonthCash', [PDFController::class, 'cetak_month_cash_only'])->middleware('auth');
//Penjualan Bulan Ini(Select)
Route::post('penjualanSelectMonthCash', [PDFController::class, 'cetak_select_month_cash_only'])->middleware('auth');

//CETAK PDF CASH ONLY
//Penjualan Cash Berdasar tanggal
Route::post('/penjualanDateKredit', [PDFController::class, 'cetak_penjualan_kredit_date'])->middleware('auth');
//Penjualan Hari Ini
Route::get('/penjualanDayKredit', [PDFController::class, 'cetak_day_kredit_only'])->middleware('auth');
//Penjualan Minggu Ini
Route::get('/penjualanWeekKredit', [PDFController::class, 'cetak_week_kredit_only'])->middleware('auth');
//Penjualan BUlan Ini
Route::get('/penjualanMonthKredit', [PDFController::class, 'cetak_month_kredit_only'])->middleware('auth');
//Penjualan Bulan Ini(Select)
Route::post('penjualanSelectMonthKredit', [PDFController::class, 'cetak_select_month_kredit_only'])->middleware('auth');


// CETAK KWITANSI
Route::post('/kwitansi', [KwitansiController::class, 'cetak_kwitansi'])->middleware('auth');
Route::get('/kwitansiCash/{unique}', [KwitansiController::class, 'cetak_kwitansi_cash'])->middleware('auth');

// CETAK TAGIHAN
Route::post('/cetak_tagihan', [CetakTagihanController::class, 'cetak_tagihan'])->middleware('auth');

//Pembelian Cash Berdasar tanggal
Route::post('/pembelianDate', [PDFController::class, 'cetak_pembelian'])->middleware('auth');
//Pembelian Hari Ini
Route::get('/pembelianDay', [PDFController::class, 'cetak_day_buy'])->middleware('auth');
//Pembelian Minggu Ini
Route::get('/pembelianWeek', [PDFController::class, 'cetak_week_buy'])->middleware('auth');
//Pembelian BUlan Ini
Route::get('/pembelianMonth', [PDFController::class, 'cetak_month_buy'])->middleware('auth');
//Pembelian Bulan Ini(Select)
Route::post('pembelianSelectMonth', [PDFController::class, 'cetak_select_month_buy'])->middleware('auth');

//LIST ORDER
// Route::resource('/listorder', ListRegOrderController::class)->middleware('auth');

// ROLES
Route::resource('/roles', RoleController::class)->middleware('admin');

// USER
Route::resource('/user', UserController::class)->middleware('admin');
//Refresh List Access
Route::get('/refresh_access', [RoleController::class, 'list_access'])->middleware('auth');
Route::get('/tambah_access', [RoleController::class, 'tambah_access'])->middleware('auth');
Route::get('/hapus_access', [RoleController::class, 'hapus_access'])->middleware('auth');

//UPLOAD EXEL
Route::post('/upload-exel', [PembelianController::class, 'upload_excel'])->middleware('pembelian');


//METHOD HAPUS DATA

//Ambil Data Chart
// Route::get('/chart-data', 'ChartDataController@index');
