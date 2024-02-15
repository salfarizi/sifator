@extends('layout.main')
@section('container')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title data-pelanggan">Cetak Laporan Pembelian</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mt-5">
                        <label class="text-label" for="laporan_perbulan">Laporan Pembelian Perbulan</label>
                        <form action="/pembelianSelectMonth" method="post" target="_blank">
                            @csrf
                            <div class="input-group mt-2">
                                <label class="input-group-text" for="inputGroupSelect01"><i class="bi-calendar2-month-fill icon-16 text-primary"></i></label>
                                <select name="bulan" class="form-select rounded-md-bottom-end rounded-md-top-end" id="inputGroupSelect04" aria-label="Example select with button addon" required>
                                    <option selected disabled value="">Pilih Bulan</option>
                                    <option value="01">Januari</option>
                                    <option value="02">Februari</option>
                                    <option value="03">Maret</option>
                                    <option value="04">April</option>
                                    <option value="05">Mei</option>
                                    <option value="06">Juni</option>
                                    <option value="07">Juli</option>
                                    <option value="08">Agustus</option>
                                    <option value="09">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                                <span class="mx-2"></span>
                                <button class="btn btn-primary rounded-md-bottom-start rounded-md-top-start" type="submit">
                                    <i data-acorn-icon="download" data-acorn-size="18"></i>
                                    PDF
                                </button>
                                <button class="btn btn-secondary" type="button">
                                    <i data-acorn-icon="print" data-acorn-size="18"></i>
                                    Print
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-12 mt-5">
                        <form action="/pembelianDate" method="post" target="_blank">
                            @csrf
                            <label class="text-label mb-2" for="laporan_pertanggal">Laporan Pembelian Pertanggal</label>
                            <div class="input-daterange input-group">
                                <input type="date" class="date form-control" name="tanggal_awal" placeholder="Tanggal Awal" required />
                                <span class="mx-2"></span>
                                <input type="date" class="date form-control" name="tanggal_akhir" placeholder="Tanggal Akhir" required />
                                <span class="mx-2"></span>
                                <button class="btn btn-primary rounded-md-bottom-start rounded-md-top-start" type="submit">
                                    <i data-acorn-icon="download" data-acorn-size="18"></i>
                                    PDF
                                </button>
                                <button type="button" class="btn btn-secondary">
                                    <i data-acorn-icon="print" data-acorn-size="18"></i>
                                    Print
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-12 mt-5">
                        <label class="text-label" for="laporan_pertanggal">Laporan Pembelian Hari Ini</label>
                        <div class="input-daterange input-group mt-2">
                            <a href="/pembelianDay" target="_blank" class="btn btn-primary rounded-md-bottom-start rounded-md-top-start">
                                <i data-acorn-icon="download" data-acorn-size="18"></i>
                                PDF
                            </a>
                            <button type="button" class="btn btn-secondary">
                                <i data-acorn-icon="print" data-acorn-size="18"></i>
                                Print
                            </button>
                        </div>
                    </div>
                    <div class="col-12 mt-5">
                        <label class="text-label" for="laporan_pertanggal">Laporan Pembelian Minggu Ini</label>
                        <div class="input-daterange input-group mt-2">
                            <a href="/pembelianWeek" target="_blank" class="btn btn-primary rounded-md-bottom-start rounded-md-top-start">
                                <i data-acorn-icon="download" data-acorn-size="18"></i>
                                PDF
                            </a>
                            <button type="button" class="btn btn-secondary">
                                <i data-acorn-icon="print" data-acorn-size="18"></i>
                                Print
                            </button>
                        </div>
                    </div>
                    <div class="col-12 mt-5">
                        <label class="text-label" for="laporan_pertanggal">Laporan Pembelian Bulan Ini</label>
                        <div class="input-daterange input-group mt-2">
                            <a href="/pembelianMonth" target="_blank" class="btn btn-primary rounded-md-bottom-start rounded-md-top-start">
                                <i data-acorn-icon="download" data-acorn-size="18"></i>
                                PDF
                            </a>
                            <button type="button" class="btn btn-secondary">
                                <i data-acorn-icon="print" data-acorn-size="18"></i>
                                Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
