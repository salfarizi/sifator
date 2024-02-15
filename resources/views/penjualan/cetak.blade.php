<!DOCTYPE html>
<html lang="en">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@200;300;400;500&display=swap" rel="stylesheet">
<style>
    * {
        font-size: 12px;
        font-family: 'Inconsolata', monospace;
        font-weight: 500;
    }



    @media print {

        .hidden-print,
        .hidden-print * {
            display: none !important;
        }
    }

</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <style>
        .ticket {
            margin: auto;
        }

    </style>
    <title>Bukti Pembayaran</title>
</head>

<body style="display: table;
       table-layout: fixed;
       padding-bottom: 2.5cm;
       height: auto;">
    <div class="ticket">
        <h1 style="text-align: center;">SIFATOR</h1>
        <p style="text-align: center;">Bukti Transaksi Pembelian <br> Jayaratu, Kec. Sariwangi, Kabupaten Tasikmalaya, Jawa Barat 46465</p>
        <br>
        <table class="ml-3" cellspacing="7">
            <thead>
            </thead>
            <tbody>
                <tr>
                    <td valign="top" class="quantity">Tanggal Jual</td>
                    <td valign="top" class="description">:</td>
                    <td valign="top" class="price" id="tanggal-pembayaran-l">{{ $tanggal_jual }}</td>
                </tr>
                <tr>
                    <td valign="top" class="quantity">Penjual</td>
                    <td valign="top" class="description">:</td>
                    <td valign="top" class="price" id="user-l">{{ auth()->user()->nama; }}</td>
                </tr>
            </tbody>
        </table>
        <p style="text-align: center;" class="mt-2">STRUK PEMBAYARAN PEMBELIAN</p>
        <table class="ml-3" cellspacing="7">
            <thead></thead>
            <tbody>
                <tr>
                    <td valign="top" class="quantity">Nota</td>
                    <td valign="top" class="description">:</td>
                    <td valign="top" class="price" id="nipd-l">{{ $nota }}</td>

                </tr>
                <tr>
                    <td valign="top" class="quantity">Pembeli</td>
                    <td valign="top" class="description">:</td>
                    <td valign="top" class="price" id="nama-siswa-l">{{ $pembeli }}</td>
                </tr>
                <tr>
                    <td valign="top" class="quantity">Harga</td>
                    <td valign="top" class="description">:</td>
                    <td valign="top" class="price" id="nama-jurusan-l">{{ $harga_jual }}</td>
                </tr>
                <tr>
                    <td valign="top" class="quantity">Jumlah Bayar</td>
                    <td valign="top" class="description">:</td>
                    <td valign="top" class="price" id="jumlah-bayar-l">{{ $jumlah_bayar }}</td>
                </tr>
                <tr>
                    <td valign="top" class="quantity">Kembali</td>
                    <td valign="top" class="description">:</td>
                    <td valign="top" class="price" id="kembali-l">{{ $kembali }}</td>
                </tr>
            </tbody>
        </table>
        <p class="centered" style="text-align:center" class="btn btn-primary">Simpan Struk Ini<br>sebagai <br> bukti pembayaran yang sah
        </p>
    </div>
    <br>
    <button class="m-auto hidden-print"><a href="/backToPenjualan">Kembali</a></button>
    <script>
        window.print();

    </script>
    <script src="script.js"></script>
</body>

</html>
