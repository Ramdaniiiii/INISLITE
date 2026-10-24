<?php
/**
 * excel_writer.php
 * Membuat file Excel (.xls) TANPA library tambahan (tanpa composer/PhpSpreadsheet).
 * Memakai format "Excel 2003 XML Spreadsheet" — dibuka normal oleh Microsoft Excel.
 */

function unduh_excel(string $namaFile, array $header, array $rows, string $namaSheet = 'Sheet1'): void
{
    header('Content-Type: application/vnd.ms-excel; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $namaFile . '.xls"');
    header('Cache-Control: max-age=0');

    $esc = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');

    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<?mso-application progid="Excel.Sheet"?>' . "\n";
    echo '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
        xmlns:o="urn:schemas-microsoft-com:office:office"
        xmlns:x="urn:schemas-microsoft-com:office:excel"
        xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">
    <Styles>
        <Style ss:ID="header">
            <Font ss:Bold="1" ss:Color="#FFFFFF"/>
            <Interior ss:Color="#161D29" ss:Pattern="Solid"/>
        </Style>
    </Styles>' . "\n";

    echo '<Worksheet ss:Name="' . $esc($namaSheet) . '"><Table>' . "\n";

    echo '<Row>';
    foreach ($header as $h) {
        echo '<Cell ss:StyleID="header"><Data ss:Type="String">' . $esc($h) . '</Data></Cell>';
    }
    echo '</Row>' . "\n";

    foreach ($rows as $row) {
        echo '<Row>';
        foreach ($row as $val) {
            $isNumber = is_numeric($val) && $val !== '' && !preg_match('/^0[0-9]/', (string) $val);
            $type = $isNumber ? 'Number' : 'String';
            echo '<Cell><Data ss:Type="' . $type . '">' . $esc($val) . '</Data></Cell>';
        }
        echo '</Row>' . "\n";
    }

    echo '</Table></Worksheet></Workbook>';
    exit;
}
