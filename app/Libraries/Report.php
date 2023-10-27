<?php

namespace App\Libraries;

use App\Models\Classroom;
use App\Models\SchoolYear;
use App\Models\Setting;
use App\Models\Vote;
use Carbon\Carbon;
use Codedge\Fpdf\Fpdf\Fpdf;

class Report extends Fpdf
{
  protected $height;
  protected $widths;
  protected $aligns;

  function SetWidths($w)
  {
    // Set the array of column widths
    $this->widths = $w;
  }

  function SetHeight($h)
  {
    // Set the array of column height
    $this->widths = $h;
  }

  function SetAligns($a)
  {
    // Set the array of column alignments
    $this->aligns = $a;
  }

  function Row($data)
  {
    // Calculate the height of the row
    $nb = 0;
    for ($i = 0; $i < count($data); $i++)
      $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
    $h = $this->height * $nb;
    // Issue a page break first if needed
    $this->CheckPageBreak($h);
    // Draw the cells of the row
    for ($i = 0; $i < count($data); $i++) {
      $w = $this->widths[$i];
      $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
      // Save the current position
      $x = $this->GetX();
      $y = $this->GetY();
      // Draw the border
      $this->Rect($x, $y, $w, $h);
      // Print the text
      $this->MultiCell($w, 5, $data[$i], 0, $a);
      // Put the position to the right of the cell
      $this->SetXY($x + $w, $y);
    }
    // Go to the next line
    $this->Ln($h);
  }

  function CheckPageBreak($h)
  {
    // If the height h would cause an overflow, add a new page immediately
    if ($this->GetY() + $h > $this->PageBreakTrigger)
      $this->AddPage($this->CurOrientation);
  }

  function NbLines($w, $txt)
  {
    // Compute the number of lines a MultiCell of width w will take
    if (!isset($this->CurrentFont))
      $this->Error('No font has been set');
    $cw = $this->CurrentFont['cw'];
    if ($w == 0)
      $w = $this->w - $this->rMargin - $this->x;
    $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
    $s = str_replace("\r", '', (string)$txt);
    $nb = strlen($s);
    if ($nb > 0 && $s[$nb - 1] == "\n")
      $nb--;
    $sep = -1;
    $i = 0;
    $j = 0;
    $l = 0;
    $nl = 1;
    while ($i < $nb) {
      $c = $s[$i];
      if ($c == "\n") {
        $i++;
        $sep = -1;
        $j = $i;
        $l = 0;
        $nl++;
        continue;
      }
      if ($c == ' ')
        $sep = $i;
      $l += $cw[$c];
      if ($l > $wmax) {
        if ($sep == -1) {
          if ($i == $j)
            $i++;
        } else
          $i = $sep + 1;
        $sep = -1;
        $j = $i;
        $l = 0;
        $nl++;
      } else
        $i++;
    }
    return $nl;
  }

  function WordWrap(&$text, $maxwidth)
  {
    $text = trim($text);
    if ($text === '')
      return 0;
    $space = $this->GetStringWidth(' ');
    $lines = explode("\n", $text);
    $text = '';
    $count = 0;

    foreach ($lines as $line) {
      $words = preg_split('/ +/', $line);
      $width = 0;

      foreach ($words as $word) {
        $wordwidth = $this->GetStringWidth($word);
        if ($wordwidth > $maxwidth) {
          // Word is too long, we cut it
          for ($i = 0; $i < strlen($word); $i++) {
            $wordwidth = $this->GetStringWidth(substr($word, $i, 1));
            if ($width + $wordwidth <= $maxwidth) {
              $width += $wordwidth;
              $text .= substr($word, $i, 1);
            } else {
              $width = $wordwidth;
              $text = rtrim($text) . "\n" . substr($word, $i, 1);
              $count++;
            }
          }
        } elseif ($width + $wordwidth <= $maxwidth) {
          $width += $wordwidth + $space;
          $text .= $word . ' ';
        } else {
          $width = $wordwidth + $space;
          $text = rtrim($text) . "\n" . $word . ' ';
          $count++;
        }
      }
      $text = rtrim($text) . "\n";
      $count++;
    }
    $text = rtrim($text);
    return $count;
  }

  function Report($data)
  {
    $setting = Setting::first();
    $school_year = SchoolYear::where('is_active', true)
      ->first();
    $classrooms = Classroom::where('school_year_id', $school_year->id)
      ->get();
    $totalVotes = Vote::distinct('student_id')
      ->count();

    $totalStudents = 0;
    foreach ($classrooms as $classroom) {
      $totalStudents += $classroom->students()->count();
    }

    $invalidVotes = $totalStudents - $totalVotes;

    $this->AddPage();
    $this->SetFont('Times', 'B', 16);
    $this->Cell(0, 10, 'BERITA ACARA', '', 1, 'C');
    $this->SetFont('Times', 'B', 14);
    $this->Cell(0, 6, 'PEMILIHAN KETUA DAN WAKIL KETUA OSIS', '', 1, 'C');
    $this->Cell(0, 6, strtoupper($setting->school_name), '', 1, 'C');
    $this->Cell(0, 6, 'MASA BAKTI ' . $school_year->name, '', 1, 'C');

    $date = Carbon::now()->locale('id');
    $date->settings(['formatFunction' => 'translatedFormat']);

    $candidate = $data->first();

    $pembuka = '      Pada hari ini, tanggal ' . $date->format('d F Y') . ', telah dilakukan Pemilihan Ketua dan Wakil ketua Organisasi Siswa Intra Sekolah (OSIS) ' . strtoupper($setting->school_name) . ' Masa Bakti ' . $school_year->name . ' dengan jumlah Hak Pemilih sebanyak ' . $totalStudents . ', jumlah daftar pemilih sebanyak ' . $totalVotes . ' dengan rincian perolehan suara sebagai berikut:';

    $bawahtable = '     Hasil ini disusun berdasarkan perolehan suara yang sah dan telah diumumkan secara langsung kepada peserta pemilihan dan seluruh warga sekolah. Panitia pemilihan telah memastikan bahwa pemilihan berlangsung dengan adil, terbuka, dan tanpa intervensi dari pihak mana pun.';

    $selamat = '     Dengan demikian Calon dengan No urut ' . $candidate->candidate_sequence . ' atas nama ' . $candidate->candidate_chairman . ' & ' . $candidate->candidate_deputy_chairman . ' dinyatakan terpilih dan berhak menjadi Ketua dan Wakil Ketua OSIS ' . strtoupper($setting->school_name) . ' Masa Bakti ' . $school_year->name . '.';

    $this->Ln(10);
    $this->SetFont('Times', '', 12);
    $this->MultiCell(0, 6, $pembuka, 0, 'J');
    $this->Ln(5);

    $this->SetFont('Times', 'B', 13);

    $this->Cell(15, 12, 'No', 1, '', 'C');
    $this->Cell(80, 12, 'Nama Pasangan Calon', 1, '', 'C');
    $this->Cell(40, 12, 'Jabatan', 1, '', 'C');
    $this->Cell(0, 12, 'Jumlah Perolehan Suara', 1, 1, 'C');

    $this->SetFont('Times', '', 12);

    foreach ($data as $index => $candidate) {

      $this->Cell(15, 12, $index + 1, 1, '', 'C');
      $this->Cell(80, 6, strtoupper($candidate->candidate_chairman), 1, '', 'L');
      $this->Cell(40, 6, 'Ketua Osis', 1, '', 'C');
      $this->Cell(0, 12, $candidate->vote_count, 1, '', 'C');
      $this->Cell(0, 6, '', '', 1, 'C');
      $this->Cell(15, 6, '', '', 0, 'C');
      $this->Cell(80, 6, strtoupper($candidate->candidate_deputy_chairman), 1, '', 'L');
      $this->Cell(40, 6, 'Wakil Ketua Osis', 1, 1, 'C');
    }

    $this->Cell(95, 10, 'Jumlah hak pilih yang tidak memilih / tidak sah', 1, '', 'C');
    $this->Cell(40, 10, '', 1, '', 'C');
    $this->Cell(0, 10, $invalidVotes, 1, 1, 'C');

    $this->Ln(5);
    $this->MultiCell(0, 6, $bawahtable, 0, 'J');
    $this->Ln(5);
    $this->MultiCell(0, 6, $selamat, 0, 'J');
    $this->Ln(5);

    $this->SetY(-95);
    $this->Cell(0, 5, 'Cirebon, ' . $date->format('d F Y'), 0, 1, 'C');
    $this->SetY(-80);
    $this->SetX(10 * 2);
    $this->Cell(60, 5, 'Mengetahui,', 0, 1, 'C');
    $this->SetX(10 * 2);
    $this->Cell(60, 5, 'Kepala ' . strtoupper($setting->school_name), 0, 1, 'C');
    $this->Ln();
    $this->Ln();
    $this->Ln();
    $this->Ln();
    $this->Ln();
    $this->SetX(10 * 2);
    $this->Cell(60, 5, '', 0, 1, 'C');
    $this->SetX(10 * 2);
    $this->Cell(60, 5, '.................................................', '', 1, 'C');

    $this->SetY(-80);
    $this->SetX(10 * 13);
    $this->Cell(60, 5, 'Mengetahui,', 0, 1, 'C');
    $this->SetX(10 * 13);
    $this->Cell(60, 5, 'Wakil Kepala ' . strtoupper($setting->school_name), 0, 1, 'C');
    $this->Ln();
    $this->Ln();
    $this->Ln();
    $this->Ln();
    $this->Ln();
    $this->SetX(10 * 13);
    $this->Cell(60, 5, '', 0, 1, 'C');
    $this->SetX(10 * 13);
    $this->Cell(60, 5, '.................................................', '', 1, 'C');
  }
}