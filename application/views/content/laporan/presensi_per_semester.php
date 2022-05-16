<?php 
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    public function Header() {
        $this->SetFont('helvetica', 'B', 16);
        $this->SetY(13);
        $this->Cell(0, 16, 'REKAP PRESENSI SISWA', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page '. $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle($title);

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 11);

// add a page
$pdf->AddPage();

// create some HTML content

$html = <<<EOD
<hr><br>
<table>
  <tr>
    <th width="15%">Tahun Pelajaran</th>
    <th width="5%">:</th>
    <th width="80%">{$tapel}</th>
  </tr>
  <tr>
    <th width="15%">Semester</th>
    <th width="5%">:</th>
    <th width="80%">{$semester}</th>
  </tr>
  <tr>
    <th width="15%">Kelas</th>
    <th width="5%">:</th>
    <th width="80%">{$kelas}</th>
  </tr>
  <tr><td></td></tr>
</table>
<table border="1" style="border-collapse:collapse" cellpadding="5" style="width: 100%;">
    <thead>{$thead}</thead>
    <tbody>
EOD;

$no = 1;
foreach($query as $row) {
    $content = '';
    for ($m = 1; $m <= 6; $m++) { 
        for ($i = 1; $i <= 4; $i++) {
            $count = $this->db->join('jadwal_pelajaran jp', 'jp.jadwal_pelajaran_id = p.id_jadwal_pelajaran', 'left')->get_where('presensi p', [
                'MONTH(p.tanggal)'      => $this->include->intBulanSmt($id_semester, $m),
                'p.id_user'             => $row->user_id,
                'jp.id_tahun_pelajaran' => $id_tahun_pelajaran,
                'p.semester'            => $id_semester,
                'p.status'              => $i,
                'p.delete_at'           => NULL,
            ])->num_rows();

            $content .= '<td width="2.5%" style="text-align: center;">'. $count .'</td>';
        }
    }

    $jumlah = '';
    for ($i = 1; $i <= 4; $i++) { 
        $count = $this->db->join('jadwal_pelajaran jp', 'jp.jadwal_pelajaran_id = p.id_jadwal_pelajaran', 'left')->get_where('presensi p', [
            'p.id_user'             => $row->user_id,
            'jp.id_tahun_pelajaran' => $id_tahun_pelajaran,
            'p.semester'            => $id_semester,
            'p.status'              => $i,
            'p.delete_at'           => NULL,
        ])->num_rows();
        $jumlah .= '<td width="2.5%" style="text-align: center;">'. $count .'</td>';

    }

$html .= <<<EOD
    <tr>
        <td width="5%" style="text-align: center;">{$no}</td>
        <td width="10%">{$row->no_induk}</td>
        <td width="10%">{$row->full_name}</td>
        <td width="5%" style="text-align: center;">{$row->gender}</td>
        {$content}
        {$jumlah}
    </tr>
EOD;
$no++;
}

$html .= <<<EOD
    </tbody>
</table>
EOD;

// output the HTML content
$pdf->writeHTML($html, true, 0, true, 0);
// reset pointer to the last page
$pdf->lastPage();
// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output(''. time() .'.pdf', 'I');