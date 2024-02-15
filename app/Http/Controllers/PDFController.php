<?php

namespace App\Http\Controllers;

use App\Models\Buy;
use App\Models\Sele;
use App\Models\Buyer;
use App\Models\Kredit;
use App\Models\Setting;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class PDFController extends Controller
{

    protected $pdf;


    public function __construct()
    {
        $this->pdf = new Fpdf;
    }
    public function header2()
    {
        $setting = Setting::first();
        //Header
        $this->pdf->SetFont('Arial', 'B', 18);
        $this->pdf->Cell(30);
        $this->pdf->Cell(210, 5, $setting->nama_toko, 0, 1, 'C');

        $this->pdf->SetFont('Arial', 'B', 13);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->Cell(30);
        $this->pdf->Cell(210, 9, 'JUAL BELI MOTOR BEKAS', 0, 1, 'C');

        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->SetTextColor(0);
        $this->pdf->Cell(30);
        $this->pdf->Cell(210, 5, strtoupper($setting->alamat_toko), 0, 1, 'C');

        // Menambahkan garis header
        $this->pdf->SetLineWidth(1);
        $this->pdf->Line(10, 36, 287, 36);
        $this->pdf->SetLineWidth(0);
        $this->pdf->Line(10, 37, 287, 37);
        $this->pdf->Ln();
        //Header
    }
    public function header()
    {
        $setting = Setting::first();
        //Header
        $this->pdf->SetFont('Arial', 'B', 18);
        $this->pdf->Cell(30);
        $this->pdf->Cell(140, 5, $setting->nama_toko, 0, 1, 'C');

        $this->pdf->SetFont('Arial', 'B', 13);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->Cell(30);
        $this->pdf->Cell(140, 9, 'JUAL BELI MOTOR BEKAS', 0, 1, 'C');

        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->SetTextColor(0);
        $this->pdf->Cell(30);
        $this->pdf->Cell(140, 5, strtoupper($setting->alamat_toko), 0, 1, 'C');

        // Menambahkan garis header
        $this->pdf->SetLineWidth(1);
        $this->pdf->Line(10, 36, 200, 36);
        $this->pdf->SetLineWidth(0);
        $this->pdf->Line(10, 37, 200, 37);
        $this->pdf->Ln();
        //Header
    }
    //CETAK PENJUALAN
    public function cetak_penjualan_cash_date(Request $request)
    {
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        $query_cash = Sele::data_pertanggal($tanggal_awal, $tanggal_akhir);

        $this->pdf->AddPage('P', 'A4');
        $this->header();

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($tanggal_awal) . ' - ' . tanggal_hari($tanggal_akhir), '0', 1, 'L');

        //Membuat kolom judul tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(8, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(32, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(40, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Harga Jual', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data kredit
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_cash as $row) {
            $this->pdf->Cell(8, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(32, 7, $row->nama, 1, '0', 'C', true);
            $this->pdf->Cell(40, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_jual), 1, '0', 'C', true);
            $this->pdf->Ln();
        }
        //DATA TOTAL JUAL DAN LABA
        $jumlah_jual_sele = Sele::where('tanggal_jual', '>=', $tanggal_awal)
            ->where('tanggal_jual', '<=', $tanggal_akhir)
            ->count('id');
        $harga_beli = Sele::where('tanggal_jual', '>=', $tanggal_awal)
            ->where('tanggal_jual', '<=', $tanggal_akhir)
            ->sum('harga_beli');
        $harga_jual = Sele::where('tanggal_jual', '>=', $tanggal_awal)
            ->where('tanggal_jual', '<=', $tanggal_akhir)
            ->sum('harga_jual');
        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(96, 7, 'Jumlah Unit yang Terjual', 1, '0', 'C', true);
        $this->pdf->Cell(96, 7, 'Jumlah Laba yang Didapat', 1, '0', 'C', true);
        $this->pdf->Ln();

        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $this->pdf->Cell(96, 7, $jumlah_jual_sele, 1, '0', 'C', true);
        $this->pdf->Cell(96, 7, rupiah($harga_jual - $harga_beli), 1, '0', 'C', true);
        $this->pdf->Ln();

        $this->pdf->AddPage('L', 'A4');
        $this->header2();

        $query_kredit = Kredit::data_pertanggal($tanggal_awal, $tanggal_akhir);
        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN KREDIT', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($tanggal_awal) . ' - ' . tanggal_hari($tanggal_akhir), '0', 1, 'L');

        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(10, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(24, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(23, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Pencairan', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'DP Konsumen', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'TAC', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Modal + Reparasi', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Laba Kredit', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_kredit as $row) {
            $nama = explode(' ', $row->nama);
            $nama2 = $nama[0];
            $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
            $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->komisi), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_jual_kredit), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah(($row->dp + $row->pencairan) - $row->harga_beli), 1, '0', 'C', true);
            $this->pdf->Ln();
        }
        //DATA TOTAL JUAL DAN TAC
        $jumlah_jual = Kredit::where('tanggal_jual', '>=', $tanggal_awal)
            ->where('tanggal_jual', '<=', $tanggal_akhir)
            ->count('id');
        $tac = Kredit::where('tanggal_jual', '>=', $tanggal_awal)
            ->where('tanggal_jual', '<=', $tanggal_akhir)
            ->sum('komisi');
        $sum_dp = Kredit::where('tanggal_jual', '>=', $tanggal_awal)->where('tanggal_jual', '<=', $tanggal_akhir)->sum('dp');
        $sum_pencairan = Kredit::where('tanggal_jual', '>=', $tanggal_awal)->where('tanggal_jual', '<=', $tanggal_akhir)->sum('pencairan');
        $sum_harga_jual = $sum_dp + $sum_pencairan;
        $sum_harga_beli = Kredit::where('tanggal_jual', '>=', $tanggal_awal)->where('tanggal_jual', '<=', $tanggal_akhir)->sum('harga_beli');
        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(255);
        $this->pdf->SetTextColor(0);
        $this->pdf->Cell(50, 7, 'Jumlah Unit yang Terjual', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(27, 7, $jumlah_jual . ' Unit', 0, '0', 'L', true);
        $this->pdf->Ln();
        $this->pdf->Cell(50, 7, 'Jumlah Laba Kredit yang Didapat', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(50, 7, rupiah($sum_harga_jual - $sum_harga_beli) . '      (' . terbilang($sum_harga_jual - $sum_harga_beli) . ' Rupiah)', 0, '0', 'L', true);
        $this->pdf->Ln();
        $this->pdf->Cell(50, 7, 'Jumlah Komisi (TAC) yang Didapat', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(50, 7, rupiah($tac) . '      (' . terbilang($tac) . ' Rupiah)', 0, '0', 'L', true);
        $this->pdf->Ln();

        $this->pdf->AddPage('L', 'A4');
        $this->header2();

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN BY NASABAH (KREDIT)', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($tanggal_awal) . ' - ' . tanggal_hari($tanggal_akhir), '0', 1, 'L');

        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(10, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(24, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(23, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Pencairan', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'DP Konsumen', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'No Telepon', 1, '0', 'C', true);
        $this->pdf->Cell(54, 7, 'Alamat', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);

        $no = 1;
        foreach ($query_kredit as $row) {
            $nama = explode(' ', $row->nama);
            $nama2 = $nama[0];
            $alamat = explode(" ", $row->alamat);
            if (1 == count($alamat)) {
                $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
                $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
                $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, $row->no_telepon, 1, '0', 'C', true);
                $this->pdf->Cell(54, 7, $alamat[0], 1, '0', 'C', true);
                $this->pdf->Ln();
            } else {
                $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
                $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
                $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, $row->no_telepon, 1, '0', 'C', true);
                $this->pdf->Cell(54, 7, $alamat[0] . ' ' . $alamat[1], 1, '0', 'C', true);
                $this->pdf->Ln();
            }
        }

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN BY NASABAH (CASH)', '0', 1, 'C');

        //periode laporan

        //Membuat kolom judul tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(8, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(32, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(35, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Harga Jual', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'No Telepon', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Alamat', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_cash as $row) {
            $nama = explode(' ', $row->nama);
            $nama2 = $nama[0];
            $alamat = explode(" ", $row->alamat);
            if (1 == count($alamat)) {
                $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
                $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
                $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, $row->no_telepon, 1, '0', 'C', true);
                $this->pdf->Cell(54, 7, $alamat[0], 1, '0', 'C', true);
                $this->pdf->Ln();
            } else {
                $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
                $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
                $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, $row->no_telepon, 1, '0', 'C', true);
                $this->pdf->Cell(54, 7, $alamat[0] . ' ' . $alamat[1], 1, '0', 'C', true);
                $this->pdf->Ln();
            }
        }

        // Simpan file PDF ke server
        $this->pdf->Output('Laporan Penjualan (' . tanggal_hari($tanggal_awal) . ' - ' . tanggal_hari($tanggal_akhir) . ').pdf', 'I');
        exit;
    }

    public function cetak_day(Request $request)
    {
        $hari_ini =  date('Y-m-d', strtotime(Carbon::now()));
        $query_cash = Sele::data_hari_ini($hari_ini);

        $this->pdf->AddPage('P', 'A4');
        $this->header();

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($hari_ini), '0', 1, 'L');

        //Membuat kolom judul tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(8, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(32, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(40, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Harga Jual', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_cash as $row) {
            $this->pdf->Cell(8, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(32, 7, $row->nama, 1, '0', 'C', true);
            $this->pdf->Cell(40, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_jual), 1, '0', 'C', true);
            $this->pdf->Ln();
        }
        //DATA TOTAL JUAL DAN LABA
        $jumlah_jual_sele = Sele::where('tanggal_jual', '>=', $hari_ini)
            ->count('id');
        $harga_beli = Sele::where('tanggal_jual', '>=', $hari_ini)
            ->sum('harga_beli');
        $harga_jual = Sele::where('tanggal_jual', '>=', $hari_ini)
            ->sum('harga_jual');
        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(96, 7, 'Jumlah Unit yang Terjual', 1, '0', 'C', true);
        $this->pdf->Cell(96, 7, 'Jumlah Laba yang Didapat', 1, '0', 'C', true);
        $this->pdf->Ln();

        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $this->pdf->Cell(96, 7, $jumlah_jual_sele, 1, '0', 'C', true);
        $this->pdf->Cell(96, 7, rupiah($harga_jual - $harga_beli), 1, '0', 'C', true);
        $this->pdf->Ln();
        $this->pdf->AddPage('L', 'A4');
        $this->header2();

        $query_kredit = Kredit::data_hari_ini($hari_ini);;
        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN KREDIT', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($hari_ini), '0', 1, 'L');

        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(10, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(24, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(23, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Pencairan', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'DP Konsumen', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'TAC', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Modal + Reparasi', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Laba Kredit', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_kredit as $row) {
            $nama = explode(' ', $row->nama);
            $nama2 = $nama[0];
            $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
            $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->komisi), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_jual_kredit), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah(($row->dp + $row->pencairan) - $row->harga_beli), 1, '0', 'C', true);
            $this->pdf->Ln();
        }
        //DATA TOTAL JUAL DAN TAC
        $jumlah_jual = Kredit::where('tanggal_jual', '>=', $hari_ini)
            ->where('tanggal_jual', '<=', $hari_ini)
            ->count('id');
        $tac = Kredit::where('tanggal_jual', '>=', $hari_ini)
            ->where('tanggal_jual', '<=', $hari_ini)
            ->sum('komisi');
        $sum_dp = Kredit::where('tanggal_jual', '>=', $hari_ini)->where('tanggal_jual', '<=', $hari_ini)->sum('dp');
        $sum_pencairan = Kredit::where('tanggal_jual', '>=', $hari_ini)->where('tanggal_jual', '<=', $hari_ini)->sum('pencairan');
        $sum_harga_jual = $sum_dp + $sum_pencairan;
        $sum_harga_beli = Kredit::where('tanggal_jual', '>=', $hari_ini)->where('tanggal_jual', '<=', $hari_ini)->sum('harga_beli');
        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(255);
        $this->pdf->SetTextColor(0);
        $this->pdf->Cell(50, 7, 'Jumlah Unit yang Terjual', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(27, 7, $jumlah_jual . ' Unit', 0, '0', 'L', true);
        $this->pdf->Ln();
        $this->pdf->Cell(50, 7, 'Jumlah Laba Kredit yang Didapat', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(50, 7, rupiah($sum_harga_jual - $sum_harga_beli) . '      (' . terbilang($sum_harga_jual - $sum_harga_beli) . ' Rupiah)', 0, '0', 'L', true);
        $this->pdf->Ln();
        $this->pdf->Cell(50, 7, 'Jumlah Komisi (TAC) yang Didapat', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(50, 7, rupiah($tac) . '      (' . terbilang($tac) . ' Rupiah)', 0, '0', 'L', true);
        $this->pdf->Ln();
        $this->pdf->AddPage('L', 'A4');
        $this->header2();

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN BY NASABAH (KREDIT)', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($hari_ini) . ' - ' . tanggal_hari($hari_ini), '0', 1, 'L');

        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(10, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(24, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(23, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Pencairan', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'DP Konsumen', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'No Telepon', 1, '0', 'C', true);
        $this->pdf->Cell(54, 7, 'Alamat', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);

        $no = 1;
        foreach ($query_kredit as $row) {
            $nama = explode(' ', $row->nama);
            $nama2 = $nama[0];
            $alamat = explode(" ", $row->alamat);
            if (1 == count($alamat)) {
                $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
                $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
                $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, $row->no_telepon, 1, '0', 'C', true);
                $this->pdf->Cell(54, 7, $alamat[0], 1, '0', 'C', true);
                $this->pdf->Ln();
            } else {
                $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
                $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
                $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, $row->no_telepon, 1, '0', 'C', true);
                $this->pdf->Cell(54, 7, $alamat[0] . ' ' . $alamat[1], 1, '0', 'C', true);
                $this->pdf->Ln();
            }
        }

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN BY NASABAH (CASH)', '0', 1, 'C');

        //periode laporan

        //Membuat kolom judul tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(8, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(32, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(35, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Harga Jual', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'No Telepon', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Alamat', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_cash as $row) {
            $nama = explode(' ', $row->nama);
            $nama2 = $nama[0];
            $alamat = explode(" ", $row->alamat);
            if (1 == count($alamat)) {
                $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
                $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
                $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, $row->no_telepon, 1, '0', 'C', true);
                $this->pdf->Cell(54, 7, $alamat[0], 1, '0', 'C', true);
                $this->pdf->Ln();
            } else {
                $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
                $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
                $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, $row->no_telepon, 1, '0', 'C', true);
                $this->pdf->Cell(54, 7, $alamat[0] . ' ' . $alamat[1], 1, '0', 'C', true);
                $this->pdf->Ln();
            }
        }

        $this->pdf->Output('Laporan Penjualan Hari Ini (' . tanggal_hari(Carbon::now()) . ').pdf', 'I');
        exit;
    }

    public function cetak_week(Request $request)
    {
        $minggu_awal =  Carbon::now()->startOfWeek()->toDateString();
        $minggu_akhir =  Carbon::now()->endOfWeek()->toDateString();
        $query_cash = Sele::data_minggu_ini();

        $this->pdf->AddPage('P', 'A4');
        $this->header();

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($minggu_awal) . ' - ' . tanggal_hari($minggu_akhir), '0', 1, 'L');

        //Membuat kolom judul tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(8, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(32, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(40, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Harga Jual', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_cash as $row) {
            $this->pdf->Cell(8, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(32, 7, $row->nama, 1, '0', 'C', true);
            $this->pdf->Cell(40, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_jual), 1, '0', 'C', true);
            $this->pdf->Ln();
        }
        //DATA TOTAL JUAL DAN LABA
        $jumlah_jual_sele = Sele::where('tanggal_jual', '>=', $minggu_awal)
            ->where('tanggal_jual', '<=', $minggu_akhir)
            ->count('id');
        $harga_beli = Sele::where('tanggal_jual', '>=', $minggu_awal)
            ->where('tanggal_jual', '<=', $minggu_akhir)
            ->sum('harga_beli');
        $harga_jual = Sele::where('tanggal_jual', '>=', $minggu_awal)
            ->where('tanggal_jual', '<=', $minggu_akhir)
            ->sum('harga_jual');
        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(96, 7, 'Jumlah Unit yang Terjual', 1, '0', 'C', true);
        $this->pdf->Cell(96, 7, 'Jumlah Laba yang Didapat', 1, '0', 'C', true);
        $this->pdf->Ln();

        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $this->pdf->Cell(96, 7, $jumlah_jual_sele, 1, '0', 'C', true);
        $this->pdf->Cell(96, 7, rupiah($harga_jual - $harga_beli), 1, '0', 'C', true);
        $this->pdf->Ln();
        $this->pdf->AddPage('L', 'A4');
        $this->header2();

        $query_kredit = Kredit::data_minggu_ini();
        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN KREDIT', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($minggu_awal) . ' - ' . tanggal_hari($minggu_akhir), '0', 1, 'L');

        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(10, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(24, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(23, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Pencairan', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'DP Konsumen', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'TAC', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Modal + Reparasi', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Laba Kredit', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_kredit as $row) {
            $nama = explode(' ', $row->nama);
            $nama2 = $nama[0];
            $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
            $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->komisi), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_jual_kredit), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah(($row->dp + $row->pencairan) - $row->harga_beli), 1, '0', 'C', true);
            $this->pdf->Ln();
        }
        //DATA TOTAL JUAL DAN TAC
        $jumlah_jual = Kredit::where('tanggal_jual', '>=', $minggu_awal)
            ->where('tanggal_jual', '<=', $minggu_akhir)
            ->count('id');
        $tac = Kredit::where('tanggal_jual', '>=', $minggu_awal)
            ->where('tanggal_jual', '<=', $minggu_akhir)
            ->sum('komisi');
        $sum_dp = Kredit::where('tanggal_jual', '>=', $minggu_awal)->where('tanggal_jual', '<=', $minggu_akhir)->sum('dp');
        $sum_pencairan = Kredit::where('tanggal_jual', '>=', $minggu_awal)->where('tanggal_jual', '<=', $minggu_akhir)->sum('pencairan');
        $sum_harga_jual = $sum_dp + $sum_pencairan;
        $sum_harga_beli = Kredit::where('tanggal_jual', '>=', $minggu_awal)->where('tanggal_jual', '<=', $minggu_akhir)->sum('harga_beli');
        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(255);
        $this->pdf->SetTextColor(0);
        $this->pdf->Cell(50, 7, 'Jumlah Unit yang Terjual', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(27, 7, $jumlah_jual . ' Unit', 0, '0', 'L', true);
        $this->pdf->Ln();
        $this->pdf->Cell(50, 7, 'Jumlah Laba Kredit yang Didapat', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(50, 7, rupiah($sum_harga_jual - $sum_harga_beli) . '      (' . terbilang($sum_harga_jual - $sum_harga_beli) . ' Rupiah)', 0, '0', 'L', true);
        $this->pdf->Ln();
        $this->pdf->Cell(50, 7, 'Jumlah Komisi (TAC) yang Didapat', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(50, 7, rupiah($tac) . '      (' . terbilang($tac) . ' Rupiah)', 0, '0', 'L', true);
        $this->pdf->Ln();
        $this->pdf->AddPage('L', 'A4');
        $this->header2();

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN BY NASABAH (KREDIT)', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($minggu_awal) . ' - ' . tanggal_hari($minggu_akhir), '0', 1, 'L');

        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(10, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(24, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(23, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Pencairan', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'DP Konsumen', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'No Telepon', 1, '0', 'C', true);
        $this->pdf->Cell(54, 7, 'Alamat', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);

        $no = 1;
        foreach ($query_kredit as $row) {
            $nama = explode(' ', $row->nama);
            $nama2 = $nama[0];
            $alamat = explode(" ", $row->alamat);
            if (1 == count($alamat)) {
                $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
                $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
                $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, $row->no_telepon, 1, '0', 'C', true);
                $this->pdf->Cell(54, 7, $alamat[0], 1, '0', 'C', true);
                $this->pdf->Ln();
            } else {
                $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
                $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
                $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, $row->no_telepon, 1, '0', 'C', true);
                $this->pdf->Cell(54, 7, $alamat[0] . ' ' . $alamat[1], 1, '0', 'C', true);
                $this->pdf->Ln();
            }
        }

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN BY NASABAH (CASH)', '0', 1, 'C');

        //periode laporan

        //Membuat kolom judul tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(8, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(32, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(35, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Harga Jual', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'No Telepon', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Alamat', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_cash as $row) {
            $nama = explode(' ', $row->nama);
            $nama2 = $nama[0];
            $alamat = explode(" ", $row->alamat);
            if (1 == count($alamat)) {
                $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
                $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
                $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, $row->no_telepon, 1, '0', 'C', true);
                $this->pdf->Cell(54, 7, $alamat[0], 1, '0', 'C', true);
                $this->pdf->Ln();
            } else {
                $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
                $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
                $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, $row->no_telepon, 1, '0', 'C', true);
                $this->pdf->Cell(54, 7, $alamat[0] . ' ' . $alamat[1], 1, '0', 'C', true);
                $this->pdf->Ln();
            }
        }
        // Simpan file PDF ke server
        $this->pdf->Output('Laporan Penjualan (' . tanggal_hari($minggu_awal) . ' - ' . tanggal_hari($minggu_akhir) . ').pdf', 'I');
        exit;
    }

    public function cetak_month(Request $request)
    {
        $bulan_awal =  Carbon::now()->startOfMonth()->toDateString();
        $bulan_akhir =  Carbon::now()->endOfMonth()->toDateString();
        $query_cash = Sele::data_bulan_ini();

        $this->pdf->AddPage('P', 'A4');
        $this->header();

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($bulan_awal) . ' - ' . tanggal_hari($bulan_akhir), '0', 1, 'L');

        //Membuat kolom judul tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(8, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(32, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(40, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Harga Jual', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_cash as $row) {
            $this->pdf->Cell(8, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(32, 7, $row->nama, 1, '0', 'C', true);
            $this->pdf->Cell(40, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_jual), 1, '0', 'C', true);
            $this->pdf->Ln();
        }
        //DATA TOTAL JUAL DAN LABA
        $jumlah_jual_sele = Sele::where('tanggal_jual', '>=', $bulan_awal)
            ->where('tanggal_jual', '<=', $bulan_akhir)
            ->count('id');
        $harga_beli = Sele::where('tanggal_jual', '>=', $bulan_awal)
            ->where('tanggal_jual', '<=', $bulan_akhir)
            ->sum('harga_beli');
        $harga_jual = Sele::where('tanggal_jual', '>=', $bulan_awal)
            ->where('tanggal_jual', '<=', $bulan_akhir)
            ->sum('harga_jual');
        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(96, 7, 'Jumlah Unit yang Terjual', 1, '0', 'C', true);
        $this->pdf->Cell(96, 7, 'Jumlah Laba yang Didapat', 1, '0', 'C', true);
        $this->pdf->Ln();

        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $this->pdf->Cell(96, 7, $jumlah_jual_sele, 1, '0', 'C', true);
        $this->pdf->Cell(96, 7, rupiah($harga_jual - $harga_beli), 1, '0', 'C', true);
        $this->pdf->Ln();
        $this->pdf->AddPage('L', 'A4');
        $this->header2();

        $query_kredit = Kredit::data_bulan_ini();
        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN KREDIT', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($bulan_awal) . ' - ' . tanggal_hari($bulan_akhir), '0', 1, 'L');

        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(10, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(24, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(23, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Pencairan', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'DP Konsumen', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'TAC', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Modal + Reparasi', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Laba Kredit', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_kredit as $row) {
            $nama = explode(' ', $row->nama);
            $nama2 = $nama[0];
            $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
            $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->komisi), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_jual_kredit), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah(($row->dp + $row->pencairan) - $row->harga_beli), 1, '0', 'C', true);
            $this->pdf->Ln();
        }
        $jumlah_jual = Kredit::where('tanggal_jual', '>=', $bulan_awal)
            ->where('tanggal_jual', '<=', $bulan_akhir)
            ->count('id');
        $tac = Kredit::where('tanggal_jual', '>=', $bulan_awal)
            ->where('tanggal_jual', '<=', $bulan_akhir)
            ->sum('komisi');
        $sum_dp = Kredit::where('tanggal_jual', '>=', $bulan_awal)->where('tanggal_jual', '<=', $bulan_akhir)->sum('dp');
        $sum_pencairan = Kredit::where('tanggal_jual', '>=', $bulan_awal)->where('tanggal_jual', '<=', $bulan_akhir)->sum('pencairan');
        $sum_harga_jual = $sum_dp + $sum_pencairan;
        $sum_harga_beli = Kredit::where('tanggal_jual', '>=', $bulan_awal)->where('tanggal_jual', '<=', $bulan_akhir)->sum('harga_beli');
        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(255);
        $this->pdf->SetTextColor(0);
        $this->pdf->Cell(50, 7, 'Jumlah Unit yang Terjual', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(27, 7, $jumlah_jual . ' Unit', 0, '0', 'L', true);
        $this->pdf->Ln();
        $this->pdf->Cell(50, 7, 'Jumlah Laba Kredit yang Didapat', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(50, 7, rupiah($sum_harga_jual - $sum_harga_beli) . '      (' . terbilang($sum_harga_jual - $sum_harga_beli) . ' Rupiah)', 0, '0', 'L', true);
        $this->pdf->Ln();
        $this->pdf->Cell(50, 7, 'Jumlah Komisi (TAC) yang Didapat', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(50, 7, rupiah($tac) . '      (' . terbilang($tac) . ' Rupiah)', 0, '0', 'L', true);
        $this->pdf->Ln();
        $this->pdf->AddPage('L', 'A4');
        $this->header2();

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN BY NASABAH (KREDIT)', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($bulan_awal) . ' - ' . tanggal_hari($bulan_akhir), '0', 1, 'L');

        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(10, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(24, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(23, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Pencairan', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'DP Konsumen', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'No Telepon', 1, '0', 'C', true);
        $this->pdf->Cell(54, 7, 'Alamat', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);

        $no = 1;
        foreach ($query_kredit as $row) {
            $nama = explode(' ', $row->nama);
            $nama2 = $nama[0];
            $alamat = explode(" ", $row->alamat);
            if (1 == count($alamat)) {
                $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
                $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
                $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, $row->no_telepon, 1, '0', 'C', true);
                $this->pdf->Cell(54, 7, $alamat[0], 1, '0', 'C', true);
                $this->pdf->Ln();
            } else {
                $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
                $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
                $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, $row->no_telepon, 1, '0', 'C', true);
                $this->pdf->Cell(54, 7, $alamat[0] . ' ' . $alamat[1], 1, '0', 'C', true);
                $this->pdf->Ln();
            }
        }

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN BY NASABAH (CASH)', '0', 1, 'C');

        //periode laporan

        //Membuat kolom judul tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(8, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(32, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(35, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Harga Jual', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'No Telepon', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Alamat', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_cash as $row) {
            $nama = explode(' ', $row->nama);
            $nama2 = $nama[0];
            $alamat = explode(" ", $row->alamat);
            if (1 == count($alamat)) {
                $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
                $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
                $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, $row->no_telepon, 1, '0', 'C', true);
                $this->pdf->Cell(54, 7, $alamat[0], 1, '0', 'C', true);
                $this->pdf->Ln();
            } else {
                $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
                $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
                $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, $row->no_telepon, 1, '0', 'C', true);
                $this->pdf->Cell(54, 7, $alamat[0] . ' ' . $alamat[1], 1, '0', 'C', true);
                $this->pdf->Ln();
            }
        }
        // Simpan file PDF ke server
        $this->pdf->Output('Laporan Penjualan (' . tanggal_hari($bulan_awal) . ' - ' . tanggal_hari($bulan_akhir) . ').pdf', 'I');
        exit;
    }

    public function cetak_select_month(Request $request)
    {
        if ($request->bulan == '02') {
            if (Carbon::now()->year % 4 == 0) {
                $tanggal_akhir = '29';
            } else {
                $tanggal_akhir = '28';
            }
        } else if ($request->bulan == '01' || $request->bulan == '03' || $request->bulan == '05' || $request->bulan == '07' || $request->bulan == '08' || $request->bulan == '10' || $request->bulan == '12') {
            $tanggal_akhir = '31';
        } else {
            $tanggal_akhir = '30';
        }
        $bulan_awal = Carbon::now()->year . '-' . $request->bulan . '-01';
        $bulan_akhir = Carbon::now()->year . '-' . $request->bulan . '-' . $tanggal_akhir;

        $query_cash = Sele::data_bulan_ini_select($bulan_awal, $bulan_akhir);


        $this->pdf->AddPage('P', 'A4');
        $this->header();

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($bulan_awal) . ' - ' . tanggal_hari($bulan_akhir), '0', 1, 'L');

        //Membuat kolom judul tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(8, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(32, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(40, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Harga Jual', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_cash as $row) {
            $this->pdf->Cell(8, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(32, 7, $row->nama, 1, '0', 'C', true);
            $this->pdf->Cell(40, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_jual), 1, '0', 'C', true);
            $this->pdf->Ln();
        }

        //DATA TOTAL JUAL DAN LABA
        $jumlah_jual_sele = Sele::where('tanggal_jual', '>=', $bulan_awal)
            ->where('tanggal_jual', '<=', $bulan_akhir)
            ->count('id');
        $harga_beli = Sele::where('tanggal_jual', '>=', $bulan_awal)
            ->where('tanggal_jual', '<=', $bulan_akhir)
            ->sum('harga_beli');
        $harga_jual = Sele::where('tanggal_jual', '>=', $bulan_awal)
            ->where('tanggal_jual', '<=', $bulan_akhir)
            ->sum('harga_jual');
        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(96, 7, 'Jumlah Unit yang Terjual', 1, '0', 'C', true);
        $this->pdf->Cell(96, 7, 'Jumlah Laba yang Didapat', 1, '0', 'C', true);
        $this->pdf->Ln();

        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $this->pdf->Cell(96, 7, $jumlah_jual_sele, 1, '0', 'C', true);
        $this->pdf->Cell(96, 7, rupiah($harga_jual - $harga_beli), 1, '0', 'C', true);
        $this->pdf->Ln();

        $this->pdf->AddPage('L', 'A4');
        $this->header2();

        $query_kredit = Kredit::data_bulan_ini();
        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN KREDIT', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($bulan_awal) . ' - ' . tanggal_hari($bulan_akhir), '0', 1, 'L');

        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(10, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(24, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(23, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Pencairan', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'DP Konsumen', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'TAC', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Modal + Reparasi', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Laba Kredit', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_kredit as $row) {
            $nama = explode(' ', $row->nama);
            $nama2 = $nama[0];
            $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
            $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->komisi), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_jual_kredit), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah(($row->dp + $row->pencairan) - $row->harga_beli), 1, '0', 'C', true);
            $this->pdf->Ln();
        }
        $jumlah_jual = Kredit::where('tanggal_jual', '>=', $bulan_awal)
            ->where('tanggal_jual', '<=', $bulan_akhir)
            ->count('id');
        $tac = Kredit::where('tanggal_jual', '>=', $bulan_awal)
            ->where('tanggal_jual', '<=', $bulan_akhir)
            ->sum('komisi');
        $sum_dp = Kredit::where('tanggal_jual', '>=', $bulan_awal)->where('tanggal_jual', '<=', $bulan_akhir)->sum('dp');
        $sum_pencairan = Kredit::where('tanggal_jual', '>=', $bulan_awal)->where('tanggal_jual', '<=', $bulan_akhir)->sum('pencairan');
        $sum_harga_jual = $sum_dp + $sum_pencairan;
        $sum_harga_beli = Kredit::where('tanggal_jual', '>=', $bulan_awal)->where('tanggal_jual', '<=', $bulan_akhir)->sum('harga_beli');
        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(255);
        $this->pdf->SetTextColor(0);
        $this->pdf->Cell(50, 7, 'Jumlah Unit yang Terjual', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(27, 7, $jumlah_jual . ' Unit', 0, '0', 'L', true);
        $this->pdf->Ln();
        $this->pdf->Cell(50, 7, 'Jumlah Laba Kredit yang Didapat', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(50, 7, rupiah($sum_harga_jual - $sum_harga_beli) . '      (' . terbilang($sum_harga_jual - $sum_harga_beli) . ' Rupiah)', 0, '0', 'L', true);
        $this->pdf->Ln();
        $this->pdf->Cell(50, 7, 'Jumlah Komisi (TAC) yang Didapat', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(50, 7, rupiah($tac) . '      (' . terbilang($tac) . ' Rupiah)', 0, '0', 'L', true);
        $this->pdf->Ln();

        $this->pdf->AddPage('L', 'A4');
        $this->header2();

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN BY NASABAH (KREDIT)', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($bulan_awal) . ' - ' . tanggal_hari($bulan_akhir), '0', 1, 'L');

        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(10, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(24, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(23, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Pencairan', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'DP Konsumen', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'No Telepon', 1, '0', 'C', true);
        $this->pdf->Cell(54, 7, 'Alamat', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);

        $no = 1;
        foreach ($query_kredit as $row) {
            $nama = explode(' ', $row->nama);
            $nama2 = $nama[0];
            $alamat = explode(" ", $row->alamat);
            if (1 == count($alamat)) {
                $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
                $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
                $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, $row->no_telepon, 1, '0', 'C', true);
                $this->pdf->Cell(54, 7, $alamat[0], 1, '0', 'C', true);
                $this->pdf->Ln();
            } else {
                $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
                $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
                $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, $row->no_telepon, 1, '0', 'C', true);
                $this->pdf->Cell(54, 7, $alamat[0] . ' ' . $alamat[1], 1, '0', 'C', true);
                $this->pdf->Ln();
            }
        }

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN BY NASABAH (CASH)', '0', 1, 'C');

        //periode laporan

        //Membuat kolom judul tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(8, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(32, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(35, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Harga Jual', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'No Telepon', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Alamat', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_cash as $row) {
            $nama = explode(' ', $row->nama);
            $nama2 = $nama[0];
            $alamat = explode(" ", $row->alamat);
            if (1 == count($alamat)) {
                $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
                $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
                $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, $row->no_telepon, 1, '0', 'C', true);
                $this->pdf->Cell(54, 7, $alamat[0], 1, '0', 'C', true);
                $this->pdf->Ln();
            } else {
                $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
                $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
                $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
                $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
                // $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
                $this->pdf->Cell(29, 7, $row->no_telepon, 1, '0', 'C', true);
                $this->pdf->Cell(54, 7, $alamat[0] . ' ' . $alamat[1], 1, '0', 'C', true);
                $this->pdf->Ln();
            }
        }
        // Simpan file PDF ke server
        $this->pdf->Output('Laporan Penjualan (' . tanggal_hari($bulan_awal) . ' - ' . tanggal_hari($bulan_akhir) . ').pdf', 'I');
        exit;
    }
    //CETAK PEMBELIAN

    public function cetak_pembelian(Request $request)
    {
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        $query_cash = Buy::data_pertanggal($tanggal_awal, $tanggal_akhir);

        $this->pdf->AddPage('P', 'A4');
        $this->header();

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PEMBELIAN', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($tanggal_awal) . ' - ' . tanggal_hari($tanggal_akhir), '0', 1, 'L');

        //Membuat kolom judul tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(8, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(32, 7, 'Penjual', 1, '0', 'C', true);
        $this->pdf->Cell(40, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Tanggal Beli', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Harga Beli', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_cash as $row) {
            $this->pdf->Cell(8, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(32, 7, $row->nama, 1, '0', 'C', true);
            $this->pdf->Cell(40, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, tanggal_hari($row->tanggal_beli), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_beli), 1, '0', 'C', true);
            $this->pdf->Ln();
        }
        // Simpan file PDF ke server
        $this->pdf->Output('Laporan Pembelian (' . tanggal_hari($tanggal_awal) . ' - ' . tanggal_hari($tanggal_akhir) . ').pdf', 'I');
        exit;
    }

    public function cetak_day_buy(Request $request)
    {
        $hari_ini =  date('Y-m-d', strtotime(Carbon::now()));
        $query_cash = Buy::data_hari_ini($hari_ini);

        $this->pdf->AddPage('P', 'A4');
        $this->header();

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PEMBELIAN', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($hari_ini), '0', 1, 'L');

        //Membuat kolom judul tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(8, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(32, 7, 'Penjual', 1, '0', 'C', true);
        $this->pdf->Cell(40, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Tanggal Beli', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Harga Beli', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_cash as $row) {
            $this->pdf->Cell(8, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(32, 7, $row->nama, 1, '0', 'C', true);
            $this->pdf->Cell(40, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, tanggal_hari($row->tanggal_beli), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_beli), 1, '0', 'C', true);
            $this->pdf->Ln();
        }
        // Simpan file PDF ke server
        $this->pdf->Output('Laporan Pembelian Hari Ini (' . tanggal_hari(Carbon::now()) . ').pdf', 'I');
        exit;
    }

    public function cetak_week_buy(Request $request)
    {
        $minggu_awal =  Carbon::now()->startOfWeek()->toDateString();
        $minggu_akhir =  Carbon::now()->endOfWeek()->toDateString();
        $query_cash = Buy::data_minggu_ini();

        $this->pdf->AddPage('P', 'A4');
        $this->header();

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PEMBELIAN', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($minggu_awal) . ' - ' . tanggal_hari($minggu_akhir), '0', 1, 'L');

        //Membuat kolom judul tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(8, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(32, 7, 'Penjual', 1, '0', 'C', true);
        $this->pdf->Cell(40, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Tanggal Beli', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Harga Beli', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_cash as $row) {
            $this->pdf->Cell(8, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(32, 7, $row->nama, 1, '0', 'C', true);
            $this->pdf->Cell(40, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, tanggal_hari($row->tanggal_beli), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_beli), 1, '0', 'C', true);
            $this->pdf->Ln();
        }
        // Simpan file PDF ke server
        $this->pdf->Output('Laporan Pembelian (' . tanggal_hari($minggu_awal) . ' - ' . tanggal_hari($minggu_akhir) . ').pdf', 'I');
        exit;
    }

    public function cetak_month_buy(Request $request)
    {
        $bulan_awal =  Carbon::now()->startOfMonth()->toDateString();
        $bulan_akhir =  Carbon::now()->endOfMonth()->toDateString();
        $query_cash = Buy::data_bulan_ini();

        $this->pdf->AddPage('P', 'A4');
        $this->header();

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PEMBELIAN', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($bulan_awal) . ' - ' . tanggal_hari($bulan_akhir), '0', 1, 'L');

        //Membuat kolom judul tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(8, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(32, 7, 'Penjual', 1, '0', 'C', true);
        $this->pdf->Cell(40, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Tanggal Beli', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Harga Beli', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_cash as $row) {
            $this->pdf->Cell(8, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(32, 7, $row->nama, 1, '0', 'C', true);
            $this->pdf->Cell(40, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, tanggal_hari($row->tanggal_beli), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_beli), 1, '0', 'C', true);
            $this->pdf->Ln();
        }
        // Simpan file PDF ke server
        $this->pdf->Output('Laporan Pembelian (' . tanggal_hari($bulan_awal) . ' - ' . tanggal_hari($bulan_akhir) . ').pdf', 'I');
        exit;
    }

    public function cetak_select_month_buy(Request $request)
    {
        if ($request->bulan == '02') {
            if (Carbon::now()->year % 4 == 0) {
                $tanggal_akhir = '29';
            } else {
                $tanggal_akhir = '28';
            }
        } else if ($request->bulan == '01' || $request->bulan == '03' || $request->bulan == '05' || $request->bulan == '07' || $request->bulan == '08' || $request->bulan == '10' || $request->bulan == '12') {
            $tanggal_akhir = '31';
        } else {
            $tanggal_akhir = '30';
        }
        $bulan_awal = Carbon::now()->year . '-' . $request->bulan . '-01';
        $bulan_akhir = Carbon::now()->year . '-' . $request->bulan . '-' . $tanggal_akhir;

        $query_cash = Buy::data_bulan_ini_select($bulan_awal, $bulan_akhir);

        $this->pdf->AddPage('P', 'A4');
        $this->header();

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PEMBELIAN', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($bulan_awal) . ' - ' . tanggal_hari($bulan_akhir), '0', 1, 'L');

        //Membuat kolom judul tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(8, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(32, 7, 'Penjual', 1, '0', 'C', true);
        $this->pdf->Cell(40, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Tanggal Beli', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Harga Beli', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_cash as $row) {
            $this->pdf->Cell(8, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(32, 7, $row->nama, 1, '0', 'C', true);
            $this->pdf->Cell(40, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, tanggal_hari($row->tanggal_beli), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_beli), 1, '0', 'C', true);
            $this->pdf->Ln();
        }
        // Simpan file PDF ke server
        $this->pdf->Output('Laporan Pembelian (' . tanggal_hari($bulan_awal) . ' - ' . tanggal_hari($bulan_akhir) . ').pdf', 'I');
        exit;
    }

    // JUST PRINT PENJUALAN CASH
    public function cetak_penjualan_cash_date_only(Request $request)
    {
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        $query_cash = Sele::data_pertanggal($tanggal_awal, $tanggal_akhir);

        $this->pdf->AddPage('P', 'A4');
        $this->header();

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($tanggal_awal) . ' - ' . tanggal_hari($tanggal_akhir), '0', 1, 'L');

        //Membuat kolom judul tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(8, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(32, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(40, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Harga Jual', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data kredit
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_cash as $row) {
            $this->pdf->Cell(8, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(32, 7, $row->nama, 1, '0', 'C', true);
            $this->pdf->Cell(40, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_jual), 1, '0', 'C', true);
            $this->pdf->Ln();
        }
        //DATA TOTAL JUAL DAN LABA
        $jumlah_jual_sele = Sele::where('tanggal_jual', '>=', $tanggal_awal)
            ->where('tanggal_jual', '<=', $tanggal_akhir)
            ->count('id');
        $harga_beli = Sele::where('tanggal_jual', '>=', $tanggal_awal)
            ->where('tanggal_jual', '<=', $tanggal_akhir)
            ->sum('harga_beli');
        $harga_jual = Sele::where('tanggal_jual', '>=', $tanggal_awal)
            ->where('tanggal_jual', '<=', $tanggal_akhir)
            ->sum('harga_jual');
        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(96, 7, 'Jumlah Unit yang Terjual', 1, '0', 'C', true);
        $this->pdf->Cell(96, 7, 'Jumlah Laba yang Didapat', 1, '0', 'C', true);
        $this->pdf->Ln();

        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $this->pdf->Cell(96, 7, $jumlah_jual_sele, 1, '0', 'C', true);
        $this->pdf->Cell(96, 7, rupiah($harga_jual - $harga_beli), 1, '0', 'C', true);
        $this->pdf->Ln();
        // Simpan file PDF ke server
        $this->pdf->Output('Laporan Penjualan (' . tanggal_hari($tanggal_awal) . ' - ' . tanggal_hari($tanggal_akhir) . ').pdf', 'I');
        exit;
    }

    public function cetak_day_cash_only(Request $request)
    {
        $hari_ini =  date('Y-m-d', strtotime(Carbon::now()));
        $query_cash = Sele::data_hari_ini($hari_ini);

        $this->pdf->AddPage('P', 'A4');
        $this->header();

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($hari_ini), '0', 1, 'L');

        //Membuat kolom judul tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(8, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(32, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(40, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Harga Jual', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_cash as $row) {
            $this->pdf->Cell(8, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(32, 7, $row->nama, 1, '0', 'C', true);
            $this->pdf->Cell(40, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_jual), 1, '0', 'C', true);
            $this->pdf->Ln();
        }
        //DATA TOTAL JUAL DAN LABA
        $jumlah_jual_sele = Sele::where('tanggal_jual', '>=', $hari_ini)
            ->count('id');
        $harga_beli = Sele::where('tanggal_jual', '>=', $hari_ini)
            ->sum('harga_beli');
        $harga_jual = Sele::where('tanggal_jual', '>=', $hari_ini)
            ->sum('harga_jual');
        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(96, 7, 'Jumlah Unit yang Terjual', 1, '0', 'C', true);
        $this->pdf->Cell(96, 7, 'Jumlah Laba yang Didapat', 1, '0', 'C', true);
        $this->pdf->Ln();

        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $this->pdf->Cell(96, 7, $jumlah_jual_sele, 1, '0', 'C', true);
        $this->pdf->Cell(96, 7, rupiah($harga_jual - $harga_beli), 1, '0', 'C', true);
        $this->pdf->Ln();
        $this->pdf->Output('Laporan Penjualan Hari Ini (' . tanggal_hari(Carbon::now()) . ').pdf', 'I');
        exit;
    }

    public function cetak_week_cash_only(Request $request)
    {
        $minggu_awal =  Carbon::now()->startOfWeek()->toDateString();
        $minggu_akhir =  Carbon::now()->endOfWeek()->toDateString();
        $query_cash = Sele::data_minggu_ini();

        $this->pdf->AddPage('P', 'A4');
        $this->header();

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($minggu_awal) . ' - ' . tanggal_hari($minggu_akhir), '0', 1, 'L');

        //Membuat kolom judul tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(8, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(32, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(40, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Harga Jual', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_cash as $row) {
            $this->pdf->Cell(8, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(32, 7, $row->nama, 1, '0', 'C', true);
            $this->pdf->Cell(40, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_jual), 1, '0', 'C', true);
            $this->pdf->Ln();
        }
        //DATA TOTAL JUAL DAN LABA
        $jumlah_jual_sele = Sele::where('tanggal_jual', '>=', $minggu_awal)
            ->where('tanggal_jual', '<=', $minggu_akhir)
            ->count('id');
        $harga_beli = Sele::where('tanggal_jual', '>=', $minggu_awal)
            ->where('tanggal_jual', '<=', $minggu_akhir)
            ->sum('harga_beli');
        $harga_jual = Sele::where('tanggal_jual', '>=', $minggu_awal)
            ->where('tanggal_jual', '<=', $minggu_akhir)
            ->sum('harga_jual');
        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(96, 7, 'Jumlah Unit yang Terjual', 1, '0', 'C', true);
        $this->pdf->Cell(96, 7, 'Jumlah Laba yang Didapat', 1, '0', 'C', true);
        $this->pdf->Ln();

        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $this->pdf->Cell(96, 7, $jumlah_jual_sele, 1, '0', 'C', true);
        $this->pdf->Cell(96, 7, rupiah($harga_jual - $harga_beli), 1, '0', 'C', true);
        $this->pdf->Ln();
        // Simpan file PDF ke server
        $this->pdf->Output('Laporan Penjualan (' . tanggal_hari($minggu_awal) . ' - ' . tanggal_hari($minggu_akhir) . ').pdf', 'I');
        exit;
    }

    public function cetak_month_cash_only(Request $request)
    {
        $bulan_awal =  Carbon::now()->startOfMonth()->toDateString();
        $bulan_akhir =  Carbon::now()->endOfMonth()->toDateString();
        $query_cash = Sele::data_bulan_ini();

        $this->pdf->AddPage('P', 'A4');
        $this->header();

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($bulan_awal) . ' - ' . tanggal_hari($bulan_akhir), '0', 1, 'L');

        //Membuat kolom judul tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(8, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(32, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(40, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Harga Jual', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_cash as $row) {
            $this->pdf->Cell(8, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(32, 7, $row->nama, 1, '0', 'C', true);
            $this->pdf->Cell(40, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_jual), 1, '0', 'C', true);
            $this->pdf->Ln();
        }
        //DATA TOTAL JUAL DAN LABA
        $jumlah_jual_sele = Sele::where('tanggal_jual', '>=', $bulan_awal)
            ->where('tanggal_jual', '<=', $bulan_akhir)
            ->count('id');
        $harga_beli = Sele::where('tanggal_jual', '>=', $bulan_awal)
            ->where('tanggal_jual', '<=', $bulan_akhir)
            ->sum('harga_beli');
        $harga_jual = Sele::where('tanggal_jual', '>=', $bulan_awal)
            ->where('tanggal_jual', '<=', $bulan_akhir)
            ->sum('harga_jual');
        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(96, 7, 'Jumlah Unit yang Terjual', 1, '0', 'C', true);
        $this->pdf->Cell(96, 7, 'Jumlah Laba yang Didapat', 1, '0', 'C', true);
        $this->pdf->Ln();

        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $this->pdf->Cell(96, 7, $jumlah_jual_sele, 1, '0', 'C', true);
        $this->pdf->Cell(96, 7, rupiah($harga_jual - $harga_beli), 1, '0', 'C', true);
        $this->pdf->Ln();
        // Simpan file PDF ke server
        $this->pdf->Output('Laporan Penjualan (' . tanggal_hari($bulan_awal) . ' - ' . tanggal_hari($bulan_akhir) . ').pdf', 'I');
        exit;
    }

    public function cetak_select_month_cash_only(Request $request)
    {
        if ($request->bulan == '02') {
            if (Carbon::now()->year % 4 == 0) {
                $tanggal_akhir = '29';
            } else {
                $tanggal_akhir = '28';
            }
        } else if ($request->bulan == '01' || $request->bulan == '03' || $request->bulan == '05' || $request->bulan == '07' || $request->bulan == '08' || $request->bulan == '10' || $request->bulan == '12') {
            $tanggal_akhir = '31';
        } else {
            $tanggal_akhir = '30';
        }
        $bulan_awal = Carbon::now()->year . '-' . $request->bulan . '-01';
        $bulan_akhir = Carbon::now()->year . '-' . $request->bulan . '-' . $tanggal_akhir;

        $query_cash = Sele::data_bulan_ini_select($bulan_awal, $bulan_akhir);

        $this->pdf->AddPage('P', 'A4');
        $this->header();

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($bulan_awal) . ' - ' . tanggal_hari($bulan_akhir), '0', 1, 'L');

        //Membuat kolom judul tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(8, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(32, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(40, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Harga Jual', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_cash as $row) {
            $this->pdf->Cell(8, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(32, 7, $row->nama, 1, '0', 'C', true);
            $this->pdf->Cell(40, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_jual), 1, '0', 'C', true);
            $this->pdf->Ln();
        }
        //DATA TOTAL JUAL DAN LABA
        $jumlah_jual_sele = Sele::where('tanggal_jual', '>=', $bulan_awal)
            ->where('tanggal_jual', '<=', $bulan_akhir)
            ->count('id');
        $harga_beli = Sele::where('tanggal_jual', '>=', $bulan_awal)
            ->where('tanggal_jual', '<=', $bulan_akhir)
            ->sum('harga_beli');
        $harga_jual = Sele::where('tanggal_jual', '>=', $bulan_awal)
            ->where('tanggal_jual', '<=', $bulan_akhir)
            ->sum('harga_jual');
        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(96, 7, 'Jumlah Unit yang Terjual', 1, '0', 'C', true);
        $this->pdf->Cell(96, 7, 'Jumlah Laba yang Didapat', 1, '0', 'C', true);
        $this->pdf->Ln();

        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $this->pdf->Cell(96, 7, $jumlah_jual_sele, 1, '0', 'C', true);
        $this->pdf->Cell(96, 7, rupiah($harga_jual - $harga_beli), 1, '0', 'C', true);
        $this->pdf->Ln();
        // Simpan file PDF ke server
        $this->pdf->Output('Laporan Penjualan (' . tanggal_hari($bulan_awal) . ' - ' . tanggal_hari($bulan_akhir) . ').pdf', 'I');
        exit;
    }

    // JUST PRINT PENJUALAN KREDIT

    public function cetak_penjualan_kredit_date(Request $request)
    {
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;


        $this->pdf->AddPage('L', 'A4');
        $this->header2();

        $query_kredit = Kredit::data_pertanggal($tanggal_awal, $tanggal_akhir);
        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN KREDIT', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($tanggal_awal) . ' - ' . tanggal_hari($tanggal_akhir), '0', 1, 'L');

        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(10, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(24, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(23, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Pencairan', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'DP Konsumen', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'TAC', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Modal + Reparasi', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Laba Kredit', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_kredit as $row) {
            $nama = explode(' ', $row->nama);
            $nama2 = $nama[0];
            $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
            $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->komisi), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_beli), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah(($row->dp + $row->pencairan) - $row->harga_beli), 1, '0', 'C', true);
            $this->pdf->Ln();
        }
        //DATA TOTAL JUAL DAN TAC
        $jumlah_jual = Kredit::where('tanggal_jual', '>=', $tanggal_awal)
            ->where('tanggal_jual', '<=', $tanggal_akhir)
            ->count('id');
        $tac = Kredit::where('tanggal_jual', '>=', $tanggal_awal)
            ->where('tanggal_jual', '<=', $tanggal_akhir)
            ->sum('komisi');
        $sum_dp = Kredit::where('tanggal_jual', '>=', $tanggal_awal)->where('tanggal_jual', '<=', $tanggal_akhir)->sum('dp');
        $sum_pencairan = Kredit::where('tanggal_jual', '>=', $tanggal_awal)->where('tanggal_jual', '<=', $tanggal_akhir)->sum('pencairan');
        $sum_harga_jual = $sum_dp + $sum_pencairan;
        $sum_harga_beli = Kredit::where('tanggal_jual', '>=', $tanggal_awal)->where('tanggal_jual', '<=', $tanggal_akhir)->sum('harga_beli');
        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(255);
        $this->pdf->SetTextColor(0);
        $this->pdf->Cell(50, 7, 'Jumlah Unit yang Terjual', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(27, 7, $jumlah_jual . ' Unit', 0, '0', 'L', true);
        $this->pdf->Ln();
        $this->pdf->Cell(50, 7, 'Jumlah Laba Kredit yang Didapat', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(50, 7, rupiah($sum_harga_jual - $sum_harga_beli) . '      (' . terbilang($sum_harga_jual - $sum_harga_beli) . ' Rupiah)', 0, '0', 'L', true);
        $this->pdf->Ln();
        $this->pdf->Cell(50, 7, 'Jumlah Komisi (TAC) yang Didapat', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(50, 7, rupiah($tac) . '      (' . terbilang($tac) . ' Rupiah)', 0, '0', 'L', true);
        $this->pdf->Ln();
        // Simpan file PDF ke server
        $this->pdf->Output('Laporan Penjualan (' . tanggal_hari($tanggal_awal) . ' - ' . tanggal_hari($tanggal_akhir) . ').pdf', 'I');
        exit;
    }

    public function cetak_day_kredit_only(Request $request)
    {
        $hari_ini =  date('Y-m-d', strtotime(Carbon::now()));
        $this->pdf->AddPage('L', 'A4');
        $this->header2();

        $query_kredit = Kredit::data_hari_ini($hari_ini);;
        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN KREDIT', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($hari_ini), '0', 1, 'L');

        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(10, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(24, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(23, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Pencairan', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'DP Konsumen', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'TAC', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Modal + Reparasi', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Laba Kredit', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_kredit as $row) {
            $nama = explode(' ', $row->nama);
            $nama2 = $nama[0];
            $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
            $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->komisi), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_beli), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah(($row->dp + $row->pencairan) - $row->harga_beli), 1, '0', 'C', true);
            $this->pdf->Ln();
        }
        //DATA TOTAL JUAL DAN TAC
        $jumlah_jual = Kredit::where('tanggal_jual', '>=', $hari_ini)
            ->where('tanggal_jual', '<=', $hari_ini)
            ->count('id');
        $tac = Kredit::where('tanggal_jual', '>=', $hari_ini)
            ->where('tanggal_jual', '<=', $hari_ini)
            ->sum('komisi');
        $sum_dp = Kredit::where('tanggal_jual', '>=', $hari_ini)->where('tanggal_jual', '<=', $hari_ini)->sum('dp');
        $sum_pencairan = Kredit::where('tanggal_jual', '>=', $hari_ini)->where('tanggal_jual', '<=', $hari_ini)->sum('pencairan');
        $sum_harga_jual = $sum_dp + $sum_pencairan;
        $sum_harga_beli = Kredit::where('tanggal_jual', '>=', $hari_ini)->where('tanggal_jual', '<=', $hari_ini)->sum('harga_beli');
        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(255);
        $this->pdf->SetTextColor(0);
        $this->pdf->Cell(50, 7, 'Jumlah Unit yang Terjual', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(27, 7, $jumlah_jual . ' Unit', 0, '0', 'L', true);
        $this->pdf->Ln();
        $this->pdf->Cell(50, 7, 'Jumlah Laba Kredit yang Didapat', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(50, 7, rupiah($sum_harga_jual - $sum_harga_beli) . '      (' . terbilang($sum_harga_jual - $sum_harga_beli) . ' Rupiah)', 0, '0', 'L', true);
        $this->pdf->Ln();
        $this->pdf->Cell(50, 7, 'Jumlah Komisi (TAC) yang Didapat', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(50, 7, rupiah($tac) . '      (' . terbilang($tac) . ' Rupiah)', 0, '0', 'L', true);
        $this->pdf->Ln();
        $this->pdf->Output('Laporan Penjualan Hari Ini (' . tanggal_hari(Carbon::now()) . ').pdf', 'I');
        exit;
    }

    public function cetak_week_kredit_only(Request $request)
    {
        $minggu_awal =  Carbon::now()->startOfWeek()->toDateString();
        $minggu_akhir =  Carbon::now()->endOfWeek()->toDateString();


        $this->pdf->AddPage('L', 'A4');
        $this->header2();

        $query_kredit = Kredit::data_minggu_ini();
        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN KREDIT', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($minggu_awal) . ' - ' . tanggal_hari($minggu_akhir), '0', 1, 'L');

        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(10, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(24, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(23, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Pencairan', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'DP Konsumen', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'TAC', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Modal + Reparasi', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Laba Kredit', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_kredit as $row) {
            $nama = explode(' ', $row->nama);
            $nama2 = $nama[0];
            $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
            $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->komisi), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_beli), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah(($row->dp + $row->pencairan) - $row->harga_beli), 1, '0', 'C', true);
            $this->pdf->Ln();
        }
        $jumlah_jual = Kredit::where('tanggal_jual', '>=', $minggu_awal)
            ->where('tanggal_jual', '<=', $minggu_akhir)
            ->count('id');
        $tac = Kredit::where('tanggal_jual', '>=', $minggu_awal)
            ->where('tanggal_jual', '<=', $minggu_akhir)
            ->sum('komisi');
        $sum_dp = Kredit::where('tanggal_jual', '>=', $minggu_awal)->where('tanggal_jual', '<=', $minggu_akhir)->sum('dp');
        $sum_pencairan = Kredit::where('tanggal_jual', '>=', $minggu_awal)->where('tanggal_jual', '<=', $minggu_akhir)->sum('pencairan');
        $sum_harga_jual = $sum_dp + $sum_pencairan;
        $sum_harga_beli = Kredit::where('tanggal_jual', '>=', $minggu_awal)->where('tanggal_jual', '<=', $minggu_akhir)->sum('harga_beli');
        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(255);
        $this->pdf->SetTextColor(0);
        $this->pdf->Cell(50, 7, 'Jumlah Unit yang Terjual', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(27, 7, $jumlah_jual . ' Unit', 0, '0', 'L', true);
        $this->pdf->Ln();
        $this->pdf->Cell(50, 7, 'Jumlah Laba Kredit yang Didapat', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(50, 7, rupiah($sum_harga_jual - $sum_harga_beli) . '      (' . terbilang($sum_harga_jual - $sum_harga_beli) . ' Rupiah)', 0, '0', 'L', true);
        $this->pdf->Ln();
        $this->pdf->Cell(50, 7, 'Jumlah Komisi (TAC) yang Didapat', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(50, 7, rupiah($tac) . '      (' . terbilang($tac) . ' Rupiah)', 0, '0', 'L', true);
        $this->pdf->Ln();
        // Simpan file PDF ke server
        $this->pdf->Output('Laporan Penjualan (' . tanggal_hari($minggu_awal) . ' - ' . tanggal_hari($minggu_akhir) . ').pdf', 'I');
        exit;
    }

    public function cetak_month_kredit_only(Request $request)
    {
        $bulan_awal =  Carbon::now()->startOfMonth()->toDateString();
        $bulan_akhir =  Carbon::now()->endOfMonth()->toDateString();

        $this->pdf->AddPage('L', 'A4');
        $this->header2();

        $query_kredit = Kredit::data_bulan_ini();
        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN KREDIT', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($bulan_awal) . ' - ' . tanggal_hari($bulan_akhir), '0', 1, 'L');

        //Membuat kolom judul tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(10, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(24, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(23, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Pencairan', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'DP Konsumen', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'TAC', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Modal + Reparasi', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Laba Kredit', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_kredit as $row) {
            $nama = explode(' ', $row->nama);
            $nama2 = $nama[0];
            $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
            $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->komisi), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_beli), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah(($row->dp + $row->pencairan) - $row->harga_beli), 1, '0', 'C', true);
            $this->pdf->Ln();
        }
        ///DATA TOTAL JUAL DAN TAC
        $jumlah_jual = Kredit::where('tanggal_jual', '>=', $bulan_awal)
            ->where('tanggal_jual', '<=', $bulan_akhir)
            ->count('id');
        $tac = Kredit::where('tanggal_jual', '>=', $bulan_awal)
            ->where('tanggal_jual', '<=', $bulan_akhir)
            ->sum('komisi');
        $sum_dp = Kredit::where('tanggal_jual', '>=', $bulan_awal)->where('tanggal_jual', '<=', $bulan_akhir)->sum('dp');
        $sum_pencairan = Kredit::where('tanggal_jual', '>=', $bulan_awal)->where('tanggal_jual', '<=', $bulan_akhir)->sum('pencairan');
        $sum_harga_jual = $sum_dp + $sum_pencairan;
        $sum_harga_beli = Kredit::where('tanggal_jual', '>=', $bulan_awal)->where('tanggal_jual', '<=', $bulan_akhir)->sum('harga_beli');
        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(255);
        $this->pdf->SetTextColor(0);
        $this->pdf->Cell(50, 7, 'Jumlah Unit yang Terjual', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(27, 7, $jumlah_jual . ' Unit', 0, '0', 'L', true);
        $this->pdf->Ln();
        $this->pdf->Cell(50, 7, 'Jumlah Laba Kredit yang Didapat', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(50, 7, rupiah($sum_harga_jual - $sum_harga_beli) . '      (' . terbilang($sum_harga_jual - $sum_harga_beli) . ' Rupiah)', 0, '0', 'L', true);
        $this->pdf->Ln();
        $this->pdf->Cell(50, 7, 'Jumlah Komisi (TAC) yang Didapat', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(50, 7, rupiah($tac) . '      (' . terbilang($tac) . ' Rupiah)', 0, '0', 'L', true);
        $this->pdf->Ln();
        // Simpan file PDF ke server
        $this->pdf->Output('Laporan Penjualan (' . tanggal_hari($bulan_awal) . ' - ' . tanggal_hari($bulan_akhir) . ').pdf', 'I');
        exit;
    }

    public function cetak_select_month_kredit_only(Request $request)
    {
        if ($request->bulan == '02') {
            if (Carbon::now()->year % 4 == 0) {
                $tanggal_akhir = '29';
            } else {
                $tanggal_akhir = '28';
            }
        } else if ($request->bulan == '01' || $request->bulan == '03' || $request->bulan == '05' || $request->bulan == '07' || $request->bulan == '08' || $request->bulan == '10' || $request->bulan == '12') {
            $tanggal_akhir = '31';
        } else {
            $tanggal_akhir = '30';
        }
        $bulan_awal = Carbon::now()->year . '-' . $request->bulan . '-01';
        $bulan_akhir = Carbon::now()->year . '-' . $request->bulan . '-' . $tanggal_akhir;


        $this->pdf->AddPage('L', 'A4');
        $this->header2();

        $query_kredit = Kredit::data_bulan_ini();
        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN PENJUALAN KREDIT', '0', 1, 'C');

        //periode laporan

        $this->pdf->SetFont('Arial', '', '12');
        $this->pdf->Cell(0, 12, 'Periode Laporan: ' . tanggal_hari($bulan_awal) . ' - ' . tanggal_hari($bulan_akhir), '0', 1, 'L');

        //Membuat kolom judul tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(10, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(24, 7, 'Pembeli', 1, '0', 'C', true);
        $this->pdf->Cell(23, 7, 'No Polisi', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Merk', 1, '0', 'C', true);
        $this->pdf->Cell(26, 7, 'Type', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Tanggal Jual', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'Pencairan', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'DP Konsumen', 1, '0', 'C', true);
        $this->pdf->Cell(29, 7, 'TAC', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Modal + Reparasi', 1, '0', 'C', true);
        $this->pdf->Cell(27, 7, 'Laba Kredit', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query_kredit as $row) {
            $nama = explode(' ', $row->nama);
            $nama2 = $nama[0];
            $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(24, 7, $nama2, 1, '0', 'C', true);
            $this->pdf->Cell(23, 7, $row->no_polisi, 1, '0', 'C', true);
            $this->pdf->Cell(26, 7, $row->merek, 1, '0', 'C', true);
            $this->pdf->Cell(26, 7, $row->type, 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, tanggal_hari($row->tanggal_jual), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->pencairan), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->dp), 1, '0', 'C', true);
            $this->pdf->Cell(29, 7, rupiah($row->komisi), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah($row->harga_beli), 1, '0', 'C', true);
            $this->pdf->Cell(27, 7, rupiah(($row->dp + $row->pencairan) - $row->harga_beli), 1, '0', 'C', true);
            $this->pdf->Ln();
        }
        //DATA TOTAL JUAL DAN TAC
        $jumlah_jual = Kredit::where('tanggal_jual', '>=', $bulan_awal)
            ->where('tanggal_jual', '<=', $bulan_akhir)
            ->count('id');
        $tac = Kredit::where('tanggal_jual', '>=', $bulan_awal)
            ->where('tanggal_jual', '<=', $bulan_akhir)
            ->sum('komisi');
        $sum_dp = Kredit::where('tanggal_jual', '>=', $bulan_awal)->where('tanggal_jual', '<=', $bulan_akhir)->sum('dp');
        $sum_pencairan = Kredit::where('tanggal_jual', '>=', $bulan_awal)->where('tanggal_jual', '<=', $bulan_akhir)->sum('pencairan');
        $sum_harga_jual = $sum_dp + $sum_pencairan;
        $sum_harga_beli = Kredit::where('tanggal_jual', '>=', $bulan_awal)->where('tanggal_jual', '<=', $bulan_akhir)->sum('harga_beli');
        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(255);
        $this->pdf->SetTextColor(0);
        $this->pdf->Cell(50, 7, 'Jumlah Unit yang Terjual', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(27, 7, $jumlah_jual . ' Unit', 0, '0', 'L', true);
        $this->pdf->Ln();
        $this->pdf->Cell(50, 7, 'Jumlah Laba Kredit yang Didapat', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(50, 7, rupiah($sum_harga_jual - $sum_harga_beli) . '      (' . terbilang($sum_harga_jual - $sum_harga_beli) . ' Rupiah)', 0, '0', 'L', true);
        $this->pdf->Ln();
        $this->pdf->Cell(50, 7, 'Jumlah Komisi (TAC) yang Didapat', 0, '0', 'L', true);
        $this->pdf->Cell(5, 7, ': ', 0, '0', 'L', true);
        $this->pdf->Cell(50, 7, rupiah($tac) . '      (' . terbilang($tac) . ' Rupiah)', 0, '0', 'L', true);
        $this->pdf->Ln();

        // Simpan file PDF ke server
        $this->pdf->Output('Laporan Penjualan (' . tanggal_hari($bulan_awal) . ' - ' . tanggal_hari($bulan_akhir) . ').pdf', 'I');
        exit;
    }

    public function cetak_nasabah()
    {
        $query = Buyer::all();
        $this->pdf->AddPage('P', 'A4');
        $this->header();

        $this->pdf->SetFont('Arial', 'B', '16');
        $this->pdf->Cell(0, 16, 'LAPORAN DAFTAR NASABAH', '0', 1, 'C');

        //periode laporan

        //Membuat kolom judul tabel
        $this->pdf->SetFont('Arial', '', '8');
        $this->pdf->SetFillColor(9, 132, 227);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->Cell(10, 7, 'No', 1, '0', 'C', true);
        $this->pdf->Cell(50, 7, 'Nama Nasbah', 1, '0', 'C', true);
        $this->pdf->Cell(50, 7, 'Alamat', 1, '0', 'C', true);
        $this->pdf->Cell(50, 7, 'No Telepon', 1, '0', 'C', true);
        $this->pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $this->pdf->SetFont('Arial', '', '7');
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetTextColor(0);
        $no = 1;
        foreach ($query as $row) {
            $this->pdf->Cell(10, 7, $no++, 1, '0', 'C', true);
            $this->pdf->Cell(50, 7, $row->nama, 1, '0', 'C', true);
            $this->pdf->Cell(50, 7, $row->alamat, 1, '0', 'C', true);
            $this->pdf->Cell(50, 7, $row->no_telepon, 1, '0', 'C', true);
            $this->pdf->Ln();
        }

        $this->pdf->Output('Laporan Nasabah.pdf', 'I');
        exit;
    }
}
