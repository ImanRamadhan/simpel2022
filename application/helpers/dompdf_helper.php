<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * PDF helper
 */

function pdf_create($html, $filename = '')
{
    // need to enable magic quotes for the
    $magic_quotes_enabled = get_magic_quotes_runtime();

    if(!$magic_quotes_enabled)
    {
    	ini_set('magic_quotes_runtime', TRUE);
    }
	
	$options = new Dompdf\Options();
	$options->set('isHtml5ParserEnabled', true);
	$options->set('isRemoteEnabled', true);
	$dompdf = new Dompdf\Dompdf($options);

    //$dompdf = new Dompdf\Dompdf();
	// $dompdf->loadHtml($html);
    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->render();
	
	$dompdf->set_paper('A4', 'portrait');

    if(!$magic_quotes_enabled)
    {
		ini_set('magic_quotes_runtime', $magic_quotes_enabled);
	}

    if($filename != '')
    {
		// ob_end_clean();//new 19/05/22
        // $dompdf->stream($filename . '.pdf');
		$dompdf->stream($filename, array("Attachment" => false));
    }
    else
    {
        return $dompdf->output();
    }
}
?>
