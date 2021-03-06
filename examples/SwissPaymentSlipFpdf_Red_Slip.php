<?php
/**
 * Swiss Payment Slip FPDF
 *
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @copyright 2012-2015 Some nice Swiss guys
 * @author Marc Würth <ravage@bluewin.ch>
 * @author Manuel Reinhard <manu@sprain.ch>
 * @author Peter Siska <pesche@gridonic.ch>
 * @link https://github.com/ravage84/SwissPaymentSlipFpdf
 */
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>SwissPaymentSlipFpdf Example 02: Create a red payment slip</title>
</head>
<body>
<h1>SwissPaymentSlipFpdf Example 02: Create a red payment slip</h1>
<?php
// Measure script execution/generating time
$time_start = microtime(true);

// Make sure the classes get auto-loaded
$loader = require __DIR__. '/../vendor/autoload.php';

// Import necessary classes
use SwissPaymentSlip\SwissPaymentSlip\RedPaymentSlip;
use SwissPaymentSlip\SwissPaymentSlip\RedPaymentSlipData;
use SwissPaymentSlip\SwissPaymentSlipFpdf\PaymentSlipFpdf;
use fpdf\FPDF;

// Make sure FPDF has access to the additional fonts
define('FPDF_FONTPATH', __DIR__.'/../src/SwissPaymentSlip/SwissPaymentSlipFpdf/Resources/font');

// Create an instance of FPDF, setup default settings
$fPdf = new FPDF('P','mm','A4');

// Add OCRB font to FPDF
$fPdf->AddFont('OCRB10');

// Add page, don't break page automatically
$fPdf->AddPage();
$fPdf->SetAutoPageBreak(false);

// Insert a dummy invoice text, not part of the payment slip itself
$fPdf->SetFont('Helvetica','',9);
$fPdf->Cell(50, 4, "Just some dummy text.");

// Create a payment slip data container (value object)
$paymentSlipData = new RedPaymentSlipData();

// Fill the data container with your data
$paymentSlipData->setBankData('Seldwyla Bank', '8021 Zürich');
$paymentSlipData->setAccountNumber('80-939-3');
$paymentSlipData->setRecipientData('Muster AG', 'Bahnhofstrasse 5', '8001 Zürich');
$paymentSlipData->setIban('CH3808888123456789012');
$paymentSlipData->setPayerData('M. Beispieler', 'Bahnhofstrasse 356', '', '7000 Chur');
$paymentSlipData->setAmount(8479.25);
$paymentSlipData->setPaymentReasonData('Rechnung', 'Nr.7496');

// Create a payment slip object, pass in the prepared data container
// for better performance, take it outside of the loop
$paymentSlip = new RedPaymentSlip($paymentSlipData, 0, 191);

// Create an instance of the FPDF implementation
$paymentSlipFpdf = new PaymentSlipFpdf($fPdf, $paymentSlip);

// "Print" the slip with its elements according to their attributes
$paymentSlipFpdf->createPaymentSlip($paymentSlip);

// Output PDF named example_fpdf_red_slip.pdf to examples folder
$pdfName = 'example_fpdf_red_slip.pdf';
$pdfPath = __DIR__ . DIRECTORY_SEPARATOR . $pdfName;
$fPdf->Output($pdfPath, 'F');

echo sprintf('Payment slip created in <a href="%s">%s</a><br>', $pdfName, $pdfPath);

echo "<br>";

$time_end = microtime(true);
$time = $time_end - $time_start;
echo "Generation took $time seconds <br>";
echo 'Peak memory usage: ' . memory_get_peak_usage() / 1024 / 1024;
?>
</body>
</html>
