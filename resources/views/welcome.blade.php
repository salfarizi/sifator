<?php
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
        $this->pdf->Cell(27, 7, 'Harga Modal', 1, '0', 'C', true);
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
            $this->pdf->Cell(27, 7, rupiah($row->harga_jual_kredit - $row->harga_beli), 1, '0', 'C', true);
            $this->pdf->Ln();
        }

    //---------------------------------------------------------
    function tanggal()
    {
        //DATA TOTAL JUAL DAN TAC
        $jumlah_jual = Kredit::where('tanggal_jual', '>=', $tanggal_awal)
            ->where('tanggal_jual', '<=', $tanggal_akhir)
            ->count('id');
        $tac = Kredit::where('tanggal_jual', '>=', $tanggal_awal)
            ->where('tanggal_jual', '<=', $tanggal_akhir)
            ->sum('komisi');
        $sum_harga_jual = Kredit::where('tanggal_jual', '>=', $tanggal_awal)->where('tanggal_jual', '<=', $tanggal_akhir)->sum('harga_jual_kredit');
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
    }
    function minggu()
    {
        $jumlah_jual = Kredit::where('tanggal_jual', '>=', $minggu_awal)
            ->where('tanggal_jual', '<=', $minggu_akhir)
            ->count('id');
        $tac = Kredit::where('tanggal_jual', '>=', $minggu_awal)
            ->where('tanggal_jual', '<=', $minggu_akhir)
            ->sum('komisi');
        $sum_harga_jual = Kredit::where('tanggal_jual', '>=', $minggu_awal)->where('tanggal_jual', '<=', $minggu_akhir)->sum('harga_jual_kredit');
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
    }
    function bulan() 
    {
        


        $jumlah_jual = Kredit::where('tanggal_jual', '>=', $bulan_awal)
            ->where('tanggal_jual', '<=', $bulan_akhir)
            ->count('id');
        $tac = Kredit::where('tanggal_jual', '>=', $bulan_awal)
            ->where('tanggal_jual', '<=', $bulan_akhir)
            ->sum('komisi');
        $sum_harga_jual = Kredit::sum('harga_jual_kredit');
        $sum_harga_beli = Kredit::sum('harga_beli');
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
    }
    function hari_ini()
    {
         //DATA TOTAL JUAL DAN TAC
         $jumlah_jual = Kredit::where('tanggal_jual', '>=', $hari_ini)
            ->where('tanggal_jual', '<=', $hari_ini)
            ->count('id');
        $tac = Kredit::where('tanggal_jual', '>=', $hari_ini)
            ->where('tanggal_jual', '<=', $hari_ini)
            ->sum('komisi');
        $sum_harga_jual = Kredit::where('tanggal_jual', '>=', $hari_ini)->where('tanggal_jual', '<=', $hari_ini)->sum('harga_jual_kredit');
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
    }
    
