<?php
namespace Core;

use TCPDF;
use App\Models\User;

class PDFService
{
    /**
     * Initialize a TCPDF instance.
     */
    protected static function initPDF(User $user, string $title): TCPDF
    {
        $pdf = new TCPDF();
        $pdf->SetCreator('Admin Tool');
        $pdf->SetAuthor($user->username);
        $pdf->SetTitle($title);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->setMargins(15, 15, 15);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);
        return $pdf;
    }

    /**
     * Generate a detail report PDF for any entity.
     * @param string $filename  Name of the generated PDF file
     * @param string $title     Title displayed in the PDF
     * @param array  $fields    Associative array label=>value
     * @param User   $user      Current user
     */
    public static function detail(string $filename, string $title, array $fields, User $user): void
    {
        $pdf = self::initPDF($user, $title);
        $printedAt = date('Y-m-d H:i:s');
        $html = "<h1 style=\"text-align:center;color:#1F4E79;font-size:18pt;margin-bottom:10px;\">$title</h1>";
        $html .= '<table style="width:100%;border-collapse:collapse;font-size:10pt;color:#333;">';
        foreach ($fields as $label => $value) {
            $html .= '<tr>'
                   . "<td style=\"padding:6px;border:1px solid #ddd;font-weight:bold;\">$label</td>"
                   . "<td style=\"padding:6px;border:1px solid #ddd;\">$value</td>"
                   . '</tr>';
        }
        $html .= '</table>';
        $html .= "<div style=\"text-align:right;font-size:8pt;color:#666;border-top:1px solid #ccc;margin-top:10px;\">"
               . "Printed by {$user->username} on {$printedAt}</div>";

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output($filename, 'I');
    }

    /**
     * Generate a list report PDF for any entity.
     * @param string $filename Name of the generated PDF file
     * @param string $title    Title displayed in the PDF
     * @param array  $headers  Array of column headers
     * @param array  $rows     Array of row data (each row is indexed array)
     * @param User   $user     Current user
     */
    public static function list(string $filename, string $title, array $headers, array $rows, User $user): void
    {
        $pdf = self::initPDF($user, $title);
        $printedAt = date('Y-m-d H:i:s');
        $html = "<h1 style=\"text-align:center;color:#1F4E79;font-size:18pt;margin-bottom:10px;\">$title</h1>";
        $html .= '<table style="width:100%;border-collapse:collapse;font-size:10pt;color:#333;">';
        // header row
        $html .= '<tr>';
        foreach ($headers as $h) {
            $html .= "<th style=\"color:black;padding:6px;border:1px solid #ddd;font-weight:bold;\">$h</th>";
        }
        $html .= '</tr>';
        // data rows
        foreach ($rows as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $html .= "<td style=\"padding:6px;border:1px solid #ddd;\">$cell</td>";
            }
            $html .= '</tr>';
        }
        $html .= '</table>';
        $html .= "<div style=\"text-align:right;font-size:8pt;color:#666;border-top:1px solid #ccc;margin-top:10px;\">"
               . "Printed by {$user->username} on {$printedAt}</div>";

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output($filename,'I');
    }
}
