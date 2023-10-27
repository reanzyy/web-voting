<?php

namespace App\Libraries;

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
    $this->AddPage();
    $this->SetFont('Arial', 'B', 14);
    $this->Cell(0, 10, 'BERITA ACARA PEMILIHAN KETUA DAN WAKIL KETUA OSIS', '', '', 'C');

    $date = Carbon::now()->locale('id');
    $date->settings(['formatFunction' => 'translatedFormat']);

    $candidate = $data->first();

    $pembuka = '      Pada hari ini, tanggal ' . $date->format('d F Y') . ', di  [Nama sekolah]  telah  dilaksanakan  pemilihan  ketua  OSIS  untuk masa bakti [tahun ajaran] dengan hasil perolehan suara sebagai berikut.';

    $bawahtable = '     Hasil ini disusun berdasarkan perolehan suara yang sah dan telah diumumkan secara langsung kepada peserta pemilihan dan seluruh warga sekolah. Panitia pemilihan telah memastikan bahwa pemilihan berlangsung dengan adil, terbuka, dan tanpa intervensi dari pihak mana pun.';

    $selamat = '     Kami ingin mengucapkan selamat kepada ' . $candidate->candidate_chairman . ' yang telah terpilih sebagai Ketua OSIS SMA/SMK [Nama Sekolah] periode [Tahun]. Semoga [Dia/Anda] dapat menjalankan tugas ini dengan baik, penuh dedikasi, dan menjadi teladan bagi seluruh siswa sekolah';

    $penutup = '     Demikian surat berita acara ini kami sampaikan untuk menjadi bahan perhatian Bapak/Ibu Kepala Sekolah. Apabila ada hal yang perlu diperjelas atau ditambahkan, kami siap untuk memberikan penjelasan lebih lanjut.

    Atas perhatian dan kerjasama Bapak/Ibu Kepala Sekolah, kami mengucapkan terima kasih.';

    $this->Ln(15);
    $this->SetFont('Arial', '', 12);
    $this->Write(5, $pembuka);
    $this->Ln(10);

    $this->SetFont('Arial', 'B', 12);
    $this->SetWidths([10, 100, 80]);
    $this->height = 6;
    $this->SetAligns(['C', 'C', 'C']);
    $this->Row([
      'No',
      'Nama Calon',
      'Jumlah Suara',
    ]);

    $this->SetFont('Arial', '', 12);
    $this->SetAligns(['L', 'L', 'C']);
    foreach ($data as $index => $candidate) {

      $this->Row([
        $index + 1,
        $candidate->candidate_chairman . ' & ' . $candidate->candidate_deputy_chairman,
        $candidate->vote_count
      ]);
    }

    $this->Ln();
    $this->Write(5, $bawahtable);
    $this->Ln();
    $this->Ln();
    $this->Write(5, $selamat);
    $this->Ln();
    $this->Ln();
    $this->Write(5, $penutup);

    $this->SetY(-65);
    $this->SetX(10 * 12);
    $this->Cell(60, 5, 'Kepala Sekolah', 0, 1, 'C');
    $this->SetX(10 * 12);
    $this->Cell(60, 5, 'Cirebon, ' . $date->format('d F Y'), 0, 1, 'C');
    $this->Ln();
    $this->Ln();
    $this->Ln();
    $this->Ln();
    $this->SetX(10 * 12);
    $this->Cell(60, 5, '', 0, 1, 'C');
    $this->SetX(10 * 12);
    $this->Cell(60, 5, '', 'T', 1, 'C');
  }
}
