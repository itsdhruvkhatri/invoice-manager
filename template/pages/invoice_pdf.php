<?php
ob_clean();


$stylesheet = "<link rel=\"stylesheet\" href=\"template/core/css/pdf.css\">";



$mpdf = new \Mpdf\Mpdf();

$mpdf->SetTitle("Invoice " . $invoice_pfx . $invoice_data->invoice_id);
$mpdf->SetAuthor("Kodly");
if($invoice_data->status == "paid"){
    $mpdf->SetWatermarkText("Paid");
    $mpdf->showWatermarkText = true;
    $mpdf->watermarkTextAlpha = 0.1;
}
if($invoice_data->status == "overdue"){
    $mpdf->SetWatermarkText("Overdue");
    $mpdf->showWatermarkText = true;
    $mpdf->watermarkTextAlpha = 0.1;
}
if($invoice_data->status == "cancelled"){
    $mpdf->SetWatermarkText("Cancelled");
    $mpdf->showWatermarkText = true;
    $mpdf->watermarkTextAlpha = 0.1;
}
$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($pdf,\Mpdf\HTMLParserMode::HTML_BODY);
$mpdf->Output();
$mpdf->Output("Invoice " . $invoice_pfx . $invoice_data->invoice_id . ".pdf", \Mpdf\Output\Destination::DOWNLOAD);


?>