<?php
require_once 'nucleo/libs/fpdf/fpdf.php';
require_once 'nucleo/libs/aletras/NumeroALetras.php';

use Luecano\NumeroALetras\NumeroALetras;

class impresionesController extends controlador{
	
	function index(){				
		$this->setTitulo('Impresiones');
	}
	
	function reciboImpresiones($id){		
		$factura = new factura($this->conexion);
		$factura->buscar("ID", $id);
		
		$reporte = new FPDF('P', 'mm',);
		$reporte->AddPage();
		$reporte->Rect(5, 5, 100, 40, 'D');
		$reporte->Rect(105, 5, 100, 40, 'D');		
		$reporte->SetFillColor(255, 255, 255);
		$reporte->Rect(95, 5, 20, 20, 'FD');		
		$reporte->SetFont('Arial', 'B', 40);
		$reporte->SetXY(95, 5);
		$reporte->Cell(20, 20, $factura->getTipo(), 0, 0, 'C');
		$reporte->SetXY(118, 1);
		$reporte->SetFontSize(16);
		$reporte->Cell(20, 20, 'FACTURA', 0, 0, 'L');
		$reporte->SetXY(118, 10);
		$reporte->SetFontSize(12);
		$reporte->Cell(20, 20, utf8_decode('Nro. Comp.: '.$factura->getNumero()), 0, 0, 'L');
		$reporte->SetXY(118, 16);
		$reporte->SetFontSize(12);
		$reporte->Cell(20, 20, utf8_decode('Fecha de Emisión: '.$factura->getFecha()), 0, 0, 'L');
		$reporte->Rect(5, 45, 200, 10, 'D');
		
		$reporte->SetXY(5, 50);
		$reporte->SetFontSize(9);
		$reporte->Cell(20, 20, utf8_decode('Cliente: '.$factura->getCliente()->getNombreCompleto()), 0, 0, 'L');
		
		$reporte->SetXY(5, 60);
		$reporte->SetFontSize(9);
		$reporte->Cell(20, 20, utf8_decode('Domicilio: '.$factura->getCliente()->getDomicilio()), 0, 0, 'L');
		
		$reporte->SetXY(120, 50);
		$reporte->SetFontSize(9);
		$reporte->Cell(20, 20, utf8_decode('Resp. IVA: '.$factura->getCliente()->getCondicionText()), 0, 0, 'L');
		
		$reporte->SetXY(120, 60);
		$reporte->SetFontSize(9);
		$reporte->Cell(20, 20, utf8_decode('C.U.I.T.: '.$factura->getCliente()->getCuit()), 0, 0, 'L');		
		
		$reporte->SetXY(5, 75);
		$reporte->SetFont('Arial', 'B', 8);
		$reporte->SetFillColor(211, 211, 211);
		$reporte->Cell(20, 8, 'Codigo', 1, 0, 'C', true);
		$reporte->Cell(69, 8, 'Descripcion', 1, 0, 'C', true);
		$reporte->Cell(18, 8, 'Cantidad', 1, 0, 'C', true);
		$reporte->Cell(18, 8, 'U. Medida', 1, 0, 'C', true);
		$reporte->Cell(20, 8, 'Precio Unit.', 1, 0, 'C', true);
		$reporte->Cell(15, 8, '%Bonif', 1, 0, 'C', true);
		$reporte->Cell(15, 8, '%IVA', 1, 0, 'C', true);
		$reporte->Cell(25, 8, 'Total', 1, 0, 'C', true);
		$reporte->Ln(8);
		$reporte->SetX(5);
		$reporte->Cell(20, 8, '00001', 0, 0, 'R' );
		$reporte->Cell(69, 8, 'Horas Desarrollo', 0, 0, 'R');
		$reporte->Cell(18, 8, number_format($factura->getHs_desararollo_cant(), 2), 0, 0, 'R');
		$reporte->Cell(18, 8, '', 0, 0, 'R');
		$reporte->Cell(20, 8, number_format($factura->getHs_desararollo_imp(), 2), 0, 0, 'R');
		$reporte->Cell(15, 8, '0.00', 0, 0, 'R');
		$reporte->Cell(15, 8, number_format($factura->getIva(), 2), 0, 0, 'R');
		$reporte->Cell(25, 8, number_format($factura->getHs_desararollo_cant() *  $factura->getHs_desararollo_imp(), 2), 0, 1, 'R');		
		$reporte->SetX(5);
		$reporte->Cell(20, 8, '00002', 0, 0, 'R' );
		$reporte->Cell(69, 8, 'Horas Capacitacion', 0, 0, 'R');
		$reporte->Cell(18, 8, number_format($factura->getHs_capacitacion_cant(), 2), 0, 0, 'R');
		$reporte->Cell(18, 8, '', 0, 0, 'R');
		$reporte->Cell(20, 8, number_format($factura->getHs_capacitacion_imp(), 2), 0, 0, 'R');
		$reporte->Cell(15, 8, '0.00', 0, 0, 'R');
		$reporte->Cell(15, 8, number_format($factura->getIva(), 2), 0, 0, 'R');
		$reporte->Cell(25, 8,  number_format($factura->getHs_capacitacion_cant() *  $factura->getHs_capacitacion_imp(), 2), 0, 1, 'R');	
		$reporte->SetX(5);
		$reporte->Line($reporte->getX(), $reporte->getY(), $reporte->getX()+200, $reporte->getY());
		
		$reporte->Rect(5, 240, 200, 40, 'D');
		$reporte->SetXY(5, 240);
		$reporte->SetFontSize(12);
		$reporte->Cell(15, 8, 'Neto Gravado: $'.number_format($factura->getNetoGravado(), 2), 0, 0, 'L');
		
		$reporte->SetXY(5, 245);
		$reporte->Cell(15, 18, 'I.V.A. 21%: $'.number_format($factura->getNetoIva(), 2), 0, 0, 'L');
		$reporte->SetXY(5, 250);
		$reporte->Cell(200, 18, 'Importe Total: $'.number_format($factura->getImporte(), 2), 0, 0, 'R');
		$reporte->SetXY(5, 255);
		$reporte->Cell(200, 18, 'Son: pesos '.ucfirst(strtolower($this->aLetras($factura->getImporte()))), 0, 0, 'R');
		$reporte->Cell(200, 18, 'Importe Total: $'.number_format($factura->getImporte(), 2), 0, 0, 'R');
		
		$reporte->output('archivo.pdf', 'i');	
		
	}
	
	function reporteRankingImpresiones($titulo, $arreglo){		
		$reporte = new FPDF('P', 'mm',);
		$reporte->AddPage();
		$reporte->Rect(5, 5, 200, 40, 'D');		
		$reporte->SetFillColor(255, 255, 255);		
		$reporte->SetFont('Arial', 'B');				
		$reporte->SetFontSize(16);
		$reporte->SetXY(5, 1);
		$reporte->Cell(200, 20, $titulo, 0, 0, 'C');
		$reporte->SetXY(118, 10);
		
		$reporte->SetXY(120, 60);
		$reporte->SetFontSize(9);
		
		$reporte->SetXY(5, 40);
		$reporte->SetFont('Arial', 'B', 8);
		$reporte->SetFillColor(211, 211, 211);
		$reporte->Cell(25, 8, 'Nro', 1, 0, 'C', true);
		$reporte->Cell(75, 8, 'Cliente', 1, 0, 'C', true);
		$reporte->Cell(50, 8, 'Porcentaje de Ingresos', 1, 0, 'C', true);
		$reporte->Cell(50, 8, 'Importe Facturado', 1, 1, 'C', true);				
		$c=0;
		foreach($arreglo as $cliente){
			$reporte->SetX(5);
			$c++;
			$reporte->Cell(25, 8, $c, 1, 0, 'C');
			$reporte->Cell(75, 8, $cliente->getNombreCompleto(), 1, 0, 'C');
			$reporte->Cell(50, 8, $cliente->getIngresosPorc().'%', 1, 0, 'C');
			$reporte->Cell(50, 8, '$'.$cliente->getImporteFacturacionTotal(), 1, 1, 'C');
		}		
		$reporte->output('archivo.pdf', 'i');			
	}
	
	function reporteProyectosImpresiones($titulo, $arreglo){		
		$reporte = new FPDF('P', 'mm',);
		$reporte->AddPage();
		$reporte->Rect(5, 5, 200, 40, 'D');		
		$reporte->SetFillColor(255, 255, 255);		
		$reporte->SetFont('Arial', 'B');				
		$reporte->SetFontSize(16);
		$reporte->SetXY(5, 1);
		$reporte->Cell(200, 20, $titulo, 0, 0, 'C');
		$reporte->SetXY(118, 10);
		
		$reporte->SetXY(120, 60);
		$reporte->SetFontSize(9);
		
		$reporte->SetXY(5, 40);
		$reporte->SetFont('Arial', 'B', 8);
		$reporte->SetFillColor(211, 211, 211);
		$reporte->Cell(25, 8, 'Nro', 1, 0, 'C', true);
		$reporte->Cell(100, 8, 'Cliente', 1, 0, 'C', true);
		$reporte->Cell(75, 8, 'Cantidad de Proyectos', 1, 1, 'C', true);		
		$c=0;
		foreach($arreglo as $cliente){
			$reporte->SetX(5);
			$c++;
			$reporte->Cell(25, 8, $c, 1, 0, 'C');
			$reporte->Cell(100, 8, $cliente->getNombreCompleto(), 1, 0, 'C');
			$reporte->Cell(75, 8, count($cliente->getProyectos()), 1, 1, 'C');			
		}		
		$reporte->output('archivo.pdf', 'i');			
	}
	
	
	function aLetras($numero){		
		$formatter = new NumeroALetras;

        return $formatter->toWords($numero);		
	}	

}

?>