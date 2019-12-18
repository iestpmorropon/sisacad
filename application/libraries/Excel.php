<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// Incluimos el archivo fpdf
require_once APPPATH."/third_party/Classes/PHPExcel.php";
require_once APPPATH."/third_party/Classes/PHPExcel/Writer/Excel2007.php";

//Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
class Excel extends PHPExcel {

	public $objReader;

	public function __construct(){
		parent::__construct();
		$this->obj = new PHPExcel();
		$this->hoja = 'Hoja1';
	}

	public function cabecera(){
		$this->obj->getProperties()->setCreator("Acomajva")
					 ->setLastModifiedBy("Acomajva")
					 ->setTitle("Reporte de consumo de fluido eléctrico")
					 ->setSubject("Reporte de consumo de fluido eléctrico")
					 ->setDescription("Reporte de consumo de fluido eléctrico")
					 ->setKeywords("Hecho desde PHP por jsilva")
					 ->setCategory("Test result file");
	}

	public function ingresaDatos($ixpage = 0, $celda = '',$valor= ''){
		// Add some data
		$this->obj->setActiveSheetIndex($ixpage)
		            ->setCellValue($celda, $valor);
	}

	public function aplicarEstilo($celda,$estilo = array()){
		if(is_array($celda)){
			foreach ($celda as $key => $value) {
				$this->obj->getActiveSheet()->getStyle($value)->applyFromArray($estilo);
			}
		}
		else{
			$this->obj->getActiveSheet()->getStyle($celda)->applyFromArray($estilo);
		}
	}

	public function ingresaDatosFormato($ixpage = 0,$celda = '',$valor = '',$tipo = 's'){
		$this->obj->setActiveSheetIndex($ixpage)->setCellValueExplicit($celda,$valor,$tipo);
	}

	public function nombrarHoja($ixpage= 0,$nombre=''){
		$this->obj->setActiveSheetIndex($ixpage);
		$this->obj->getActiveSheet()->setTitle($nombre);
	}

	public function combina($cols = 'A1:A1',$ixpage = 0){
		$this->obj->setActiveSheetIndex($ixpage)->mergeCells($cols);
	}

	public function genera($title=''){
		if($title == '')
			$title = 'file '.date('d-m-Y H:i');

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$this->obj->setActiveSheetIndex(0);
		/*header('Content-type: application/vnd.ms-excel');

		// It will be called file.xls
		header('Content-Disposition: attachment; filename="prueba.xlsx"');*/
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$title.'.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		//header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		$objWriter = new PHPExcel_Writer_Excel2007($this->obj);
		//$objWriter = PHPExcel_IOFactory::createWriter($this->obj, 'Excel2007');
		$objWriter->save('php://output');
	}
	public function carga($file = '',$hoja = 'Hoja1'){
		if($file == '')
			return false;
		$objReader = new PHPExcel_Reader_Excel2007();
		$objReader->setReadDataOnly(true);
		$this->objPHPExcel = $objReader->load($file);
		$this->hoja = $hoja;
	}

	public function leer($cell = 'A1'){
		return $this->objPHPExcel->getActiveSheet($this->hoja)->getCell($cell)->getFormattedValue();
	}

	public function leerFecha($cell = 'A1'){
		return PHPExcel_Shared_Date::ExcelToPHP($this->objPHPExcel->getActiveSheet()->getCell($cell)->getValue());
	}

}