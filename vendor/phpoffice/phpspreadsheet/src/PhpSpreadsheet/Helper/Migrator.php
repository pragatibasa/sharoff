<?php

namespace PhpOffice\PhpSpreadsheet\Helper;

use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use PhpOffice\PhpSpreadsheet\Calculation\Category;
use PhpOffice\PhpSpreadsheet\Calculation\Database;
use PhpOffice\PhpSpreadsheet\Calculation\DateTime;
use PhpOffice\PhpSpreadsheet\Calculation\Engine\CyclicReferenceStack;
use PhpOffice\PhpSpreadsheet\Calculation\Engine\Logger;
use PhpOffice\PhpSpreadsheet\Calculation\Engineering;
use PhpOffice\PhpSpreadsheet\Calculation\ExceptionHandler;
use PhpOffice\PhpSpreadsheet\Calculation\Financial;
use PhpOffice\PhpSpreadsheet\Calculation\FormulaParser;
use PhpOffice\PhpSpreadsheet\Calculation\FormulaToken;
use PhpOffice\PhpSpreadsheet\Calculation\Functions;
use PhpOffice\PhpSpreadsheet\Calculation\Logical;
use PhpOffice\PhpSpreadsheet\Calculation\LookupRef;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical;
use PhpOffice\PhpSpreadsheet\Calculation\TextData;
use PhpOffice\PhpSpreadsheet\Calculation\Token\Stack;
use PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Hyperlink;
use PhpOffice\PhpSpreadsheet\Cell\IValueBinder;
use PhpOffice\PhpSpreadsheet\Chart\Axis;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\GridLines;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Renderer\JpGraph;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Collection\Cells;
use PhpOffice\PhpSpreadsheet\Collection\CellsFactory;
use PhpOffice\PhpSpreadsheet\Comment;
use PhpOffice\PhpSpreadsheet\Document\Properties;
use PhpOffice\PhpSpreadsheet\Document\Security;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\HashTable;
use PhpOffice\PhpSpreadsheet\IComparable;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\NamedRange;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use PhpOffice\PhpSpreadsheet\Reader\DefaultReadFilter;
use PhpOffice\PhpSpreadsheet\Reader\Gnumeric;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;
use PhpOffice\PhpSpreadsheet\Reader\Slk;
use PhpOffice\PhpSpreadsheet\Reader\Xls\MD5;
use PhpOffice\PhpSpreadsheet\Reader\Xls\RC4;
use PhpOffice\PhpSpreadsheet\Reader\Xml;
use PhpOffice\PhpSpreadsheet\ReferenceHelper;
use PhpOffice\PhpSpreadsheet\RichText\ITextElement;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\RichText\Run;
use PhpOffice\PhpSpreadsheet\RichText\TextElement;
use PhpOffice\PhpSpreadsheet\Settings;
use PhpOffice\PhpSpreadsheet\Shared\CodePage;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Shared\Escher;
use PhpOffice\PhpSpreadsheet\Shared\Escher\DgContainer;
use PhpOffice\PhpSpreadsheet\Shared\Escher\DgContainer\SpgrContainer;
use PhpOffice\PhpSpreadsheet\Shared\Escher\DgContainer\SpgrContainer\SpContainer;
use PhpOffice\PhpSpreadsheet\Shared\Escher\DggContainer;
use PhpOffice\PhpSpreadsheet\Shared\Escher\DggContainer\BstoreContainer;
use PhpOffice\PhpSpreadsheet\Shared\Escher\DggContainer\BstoreContainer\BSE;
use PhpOffice\PhpSpreadsheet\Shared\Escher\DggContainer\BstoreContainer\BSE\Blip;
use PhpOffice\PhpSpreadsheet\Shared\File;
use PhpOffice\PhpSpreadsheet\Shared\JAMA\CholeskyDecomposition;
use PhpOffice\PhpSpreadsheet\Shared\JAMA\EigenvalueDecomposition;
use PhpOffice\PhpSpreadsheet\Shared\JAMA\LUDecomposition;
use PhpOffice\PhpSpreadsheet\Shared\JAMA\Matrix;
use PhpOffice\PhpSpreadsheet\Shared\JAMA\QRDecomposition;
use PhpOffice\PhpSpreadsheet\Shared\JAMA\SingularValueDecomposition;
use PhpOffice\PhpSpreadsheet\Shared\OLE;
use PhpOffice\PhpSpreadsheet\Shared\OLE\ChainedBlockStream;
use PhpOffice\PhpSpreadsheet\Shared\OLE\PPS;
use PhpOffice\PhpSpreadsheet\Shared\OLE\PPS\Root;
use PhpOffice\PhpSpreadsheet\Shared\OLERead;
use PhpOffice\PhpSpreadsheet\Shared\PasswordHasher;
use PhpOffice\PhpSpreadsheet\Shared\StringHelper;
use PhpOffice\PhpSpreadsheet\Shared\TimeZone;
use PhpOffice\PhpSpreadsheet\Shared\Trend\BestFit;
use PhpOffice\PhpSpreadsheet\Shared\Trend\ExponentialBestFit;
use PhpOffice\PhpSpreadsheet\Shared\Trend\LinearBestFit;
use PhpOffice\PhpSpreadsheet\Shared\Trend\LogarithmicBestFit;
use PhpOffice\PhpSpreadsheet\Shared\Trend\PolynomialBestFit;
use PhpOffice\PhpSpreadsheet\Shared\Trend\PowerBestFit;
use PhpOffice\PhpSpreadsheet\Shared\Trend\Trend;
use PhpOffice\PhpSpreadsheet\Shared\XMLWriter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Supervisor;
use PhpOffice\PhpSpreadsheet\Worksheet\AutoFilter;
use PhpOffice\PhpSpreadsheet\Worksheet\AutoFilter\Column\Rule;
use PhpOffice\PhpSpreadsheet\Worksheet\BaseDrawing;
use PhpOffice\PhpSpreadsheet\Worksheet\CellIterator;
use PhpOffice\PhpSpreadsheet\Worksheet\Column;
use PhpOffice\PhpSpreadsheet\Worksheet\ColumnCellIterator;
use PhpOffice\PhpSpreadsheet\Worksheet\ColumnDimension;
use PhpOffice\PhpSpreadsheet\Worksheet\ColumnIterator;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing\Shadow;
use PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooter;
use PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooterDrawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Iterator;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageMargins;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Protection;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use PhpOffice\PhpSpreadsheet\Worksheet\RowCellIterator;
use PhpOffice\PhpSpreadsheet\Worksheet\RowDimension;
use PhpOffice\PhpSpreadsheet\Worksheet\RowIterator;
use PhpOffice\PhpSpreadsheet\Worksheet\SheetView;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\BaseWriter;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use PhpOffice\PhpSpreadsheet\Writer\Ods;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Content;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Meta;
use PhpOffice\PhpSpreadsheet\Writer\Ods\MetaInf;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Mimetype;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Styles;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Thumbnails;
use PhpOffice\PhpSpreadsheet\Writer\Pdf;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xls\BIFFwriter;
use PhpOffice\PhpSpreadsheet\Writer\Xls\Parser;
use PhpOffice\PhpSpreadsheet\Writer\Xls\Xf;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Comments;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\ContentTypes;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\DocProps;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\RelsRibbon;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\RelsVBA;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\StringTable;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Theme;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Workbook;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\WriterPart;

class Migrator
{
    /**
     * @var string[]
     */
    private $from;

    /**
     * @var string[]
     */
    private $to;

    public function __construct()
    {
        $this->from = array_keys($this->getMapping());
        $this->to = array_values($this->getMapping());
    }

    /**
     * Return the ordered mapping from old PHPExcel class names to new PhpSpreadsheet one.
     *
     * @return string[]
     */
    public function getMapping()
    {
        // Order matters here, we should have the deepest namespaces first (the most "unique" strings)
        $classes = [
            'PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE_Blip' => Blip::class,
            'PHPExcel_Shared_Escher_DgContainer_SpgrContainer_SpContainer' => SpContainer::class,
            'PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE' => BSE::class,
            'PHPExcel_Shared_Escher_DgContainer_SpgrContainer' => SpgrContainer::class,
            'PHPExcel_Shared_Escher_DggContainer_BstoreContainer' => BstoreContainer::class,
            'PHPExcel_Shared_OLE_PPS_File' => \PhpOffice\PhpSpreadsheet\Shared\OLE\PPS\File::class,
            'PHPExcel_Shared_OLE_PPS_Root' => Root::class,
            'PHPExcel_Worksheet_AutoFilter_Column_Rule' => Rule::class,
            'PHPExcel_Writer_OpenDocument_Cell_Comment' => \PhpOffice\PhpSpreadsheet\Writer\Ods\Cell\Comment::class,
            'PHPExcel_Calculation_Token_Stack' => Stack::class,
            'PHPExcel_Chart_Renderer_jpgraph' => JpGraph::class,
            'PHPExcel_Reader_Excel5_Escher' => \PhpOffice\PhpSpreadsheet\Reader\Xls\Escher::class,
            'PHPExcel_Reader_Excel5_MD5' => MD5::class,
            'PHPExcel_Reader_Excel5_RC4' => RC4::class,
            'PHPExcel_Reader_Excel2007_Chart' => \PhpOffice\PhpSpreadsheet\Reader\Xlsx\Chart::class,
            'PHPExcel_Reader_Excel2007_Theme' => \PhpOffice\PhpSpreadsheet\Reader\Xlsx\Theme::class,
            'PHPExcel_Shared_Escher_DgContainer' => DgContainer::class,
            'PHPExcel_Shared_Escher_DggContainer' => DggContainer::class,
            'CholeskyDecomposition' => CholeskyDecomposition::class,
            'EigenvalueDecomposition' => EigenvalueDecomposition::class,
            'PHPExcel_Shared_JAMA_LUDecomposition' => LUDecomposition::class,
            'PHPExcel_Shared_JAMA_Matrix' => Matrix::class,
            'QRDecomposition' => QRDecomposition::class,
            'PHPExcel_Shared_JAMA_QRDecomposition' => QRDecomposition::class,
            'SingularValueDecomposition' => SingularValueDecomposition::class,
            'PHPExcel_Shared_OLE_ChainedBlockStream' => ChainedBlockStream::class,
            'PHPExcel_Shared_OLE_PPS' => PPS::class,
            'PHPExcel_Best_Fit' => BestFit::class,
            'PHPExcel_Exponential_Best_Fit' => ExponentialBestFit::class,
            'PHPExcel_Linear_Best_Fit' => LinearBestFit::class,
            'PHPExcel_Logarithmic_Best_Fit' => LogarithmicBestFit::class,
            'polynomialBestFit' => PolynomialBestFit::class,
            'PHPExcel_Polynomial_Best_Fit' => PolynomialBestFit::class,
            'powerBestFit' => PowerBestFit::class,
            'PHPExcel_Power_Best_Fit' => PowerBestFit::class,
            'trendClass' => Trend::class,
            'PHPExcel_Worksheet_AutoFilter_Column' => \PhpOffice\PhpSpreadsheet\Worksheet\AutoFilter\Column::class,
            'PHPExcel_Worksheet_Drawing_Shadow' => Shadow::class,
            'PHPExcel_Writer_OpenDocument_Content' => Content::class,
            'PHPExcel_Writer_OpenDocument_Meta' => Meta::class,
            'PHPExcel_Writer_OpenDocument_MetaInf' => MetaInf::class,
            'PHPExcel_Writer_OpenDocument_Mimetype' => Mimetype::class,
            'PHPExcel_Writer_OpenDocument_Settings' => \PhpOffice\PhpSpreadsheet\Writer\Ods\Settings::class,
            'PHPExcel_Writer_OpenDocument_Styles' => Styles::class,
            'PHPExcel_Writer_OpenDocument_Thumbnails' => Thumbnails::class,
            'PHPExcel_Writer_OpenDocument_WriterPart' => \PhpOffice\PhpSpreadsheet\Writer\Ods\WriterPart::class,
            'PHPExcel_Writer_PDF_Core' => Pdf::class,
            'PHPExcel_Writer_PDF_DomPDF' => Dompdf::class,
            'PHPExcel_Writer_PDF_mPDF' => Mpdf::class,
            'PHPExcel_Writer_PDF_tcPDF' => Tcpdf::class,
            'PHPExcel_Writer_Excel5_BIFFwriter' => BIFFwriter::class,
            'PHPExcel_Writer_Excel5_Escher' => \PhpOffice\PhpSpreadsheet\Writer\Xls\Escher::class,
            'PHPExcel_Writer_Excel5_Font' => \PhpOffice\PhpSpreadsheet\Writer\Xls\Font::class,
            'PHPExcel_Writer_Excel5_Parser' => Parser::class,
            'PHPExcel_Writer_Excel5_Workbook' => \PhpOffice\PhpSpreadsheet\Writer\Xls\Workbook::class,
            'PHPExcel_Writer_Excel5_Worksheet' => \PhpOffice\PhpSpreadsheet\Writer\Xls\Worksheet::class,
            'PHPExcel_Writer_Excel5_Xf' => Xf::class,
            'PHPExcel_Writer_Excel2007_Chart' => \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Chart::class,
            'PHPExcel_Writer_Excel2007_Comments' => Comments::class,
            'PHPExcel_Writer_Excel2007_ContentTypes' => ContentTypes::class,
            'PHPExcel_Writer_Excel2007_DocProps' => DocProps::class,
            'PHPExcel_Writer_Excel2007_Drawing' => \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Drawing::class,
            'PHPExcel_Writer_Excel2007_Rels' => Rels::class,
            'PHPExcel_Writer_Excel2007_RelsRibbon' => RelsRibbon::class,
            'PHPExcel_Writer_Excel2007_RelsVBA' => RelsVBA::class,
            'PHPExcel_Writer_Excel2007_StringTable' => StringTable::class,
            'PHPExcel_Writer_Excel2007_Style' => \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Style::class,
            'PHPExcel_Writer_Excel2007_Theme' => Theme::class,
            'PHPExcel_Writer_Excel2007_Workbook' => Workbook::class,
            'PHPExcel_Writer_Excel2007_Worksheet' => \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet::class,
            'PHPExcel_Writer_Excel2007_WriterPart' => WriterPart::class,
            'PHPExcel_CachedObjectStorage_CacheBase' => Cells::class,
            'PHPExcel_CalcEngine_CyclicReferenceStack' => CyclicReferenceStack::class,
            'PHPExcel_CalcEngine_Logger' => Logger::class,
            'PHPExcel_Calculation_Functions' => Functions::class,
            'PHPExcel_Calculation_Function' => Category::class,
            'PHPExcel_Calculation_Database' => Database::class,
            'PHPExcel_Calculation_DateTime' => DateTime::class,
            'PHPExcel_Calculation_Engineering' => Engineering::class,
            'PHPExcel_Calculation_Exception' => \PhpOffice\PhpSpreadsheet\Calculation\Exception::class,
            'PHPExcel_Calculation_ExceptionHandler' => ExceptionHandler::class,
            'PHPExcel_Calculation_Financial' => Financial::class,
            'PHPExcel_Calculation_FormulaParser' => FormulaParser::class,
            'PHPExcel_Calculation_FormulaToken' => FormulaToken::class,
            'PHPExcel_Calculation_Logical' => Logical::class,
            'PHPExcel_Calculation_LookupRef' => LookupRef::class,
            'PHPExcel_Calculation_MathTrig' => MathTrig::class,
            'PHPExcel_Calculation_Statistical' => Statistical::class,
            'PHPExcel_Calculation_TextData' => TextData::class,
            'PHPExcel_Cell_AdvancedValueBinder' => AdvancedValueBinder::class,
            'PHPExcel_Cell_DataType' => DataType::class,
            'PHPExcel_Cell_DataValidation' => DataValidation::class,
            'PHPExcel_Cell_DefaultValueBinder' => DefaultValueBinder::class,
            'PHPExcel_Cell_Hyperlink' => Hyperlink::class,
            'PHPExcel_Cell_IValueBinder' => IValueBinder::class,
            'PHPExcel_Chart_Axis' => Axis::class,
            'PHPExcel_Chart_DataSeries' => DataSeries::class,
            'PHPExcel_Chart_DataSeriesValues' => DataSeriesValues::class,
            'PHPExcel_Chart_Exception' => \PhpOffice\PhpSpreadsheet\Chart\Exception::class,
            'PHPExcel_Chart_GridLines' => GridLines::class,
            'PHPExcel_Chart_Layout' => Layout::class,
            'PHPExcel_Chart_Legend' => Legend::class,
            'PHPExcel_Chart_PlotArea' => PlotArea::class,
            'PHPExcel_Properties' => \PhpOffice\PhpSpreadsheet\Chart\Properties::class,
            'PHPExcel_Chart_Title' => Title::class,
            'PHPExcel_DocumentProperties' => Properties::class,
            'PHPExcel_DocumentSecurity' => Security::class,
            'PHPExcel_Helper_HTML' => Html::class,
            'PHPExcel_Reader_Abstract' => BaseReader::class,
            'PHPExcel_Reader_CSV' => \PhpOffice\PhpSpreadsheet\Reader\Csv::class,
            'PHPExcel_Reader_DefaultReadFilter' => DefaultReadFilter::class,
            'PHPExcel_Reader_Excel2003XML' => Xml::class,
            'PHPExcel_Reader_Exception' => \PhpOffice\PhpSpreadsheet\Reader\Exception::class,
            'PHPExcel_Reader_Gnumeric' => Gnumeric::class,
            'PHPExcel_Reader_HTML' => \PhpOffice\PhpSpreadsheet\Reader\Html::class,
            'PHPExcel_Reader_IReadFilter' => IReadFilter::class,
            'PHPExcel_Reader_IReader' => IReader::class,
            'PHPExcel_Reader_OOCalc' => \PhpOffice\PhpSpreadsheet\Reader\Ods::class,
            'PHPExcel_Reader_SYLK' => Slk::class,
            'PHPExcel_Reader_Excel5' => \PhpOffice\PhpSpreadsheet\Reader\Xls::class,
            'PHPExcel_Reader_Excel2007' => \PhpOffice\PhpSpreadsheet\Reader\Xlsx::class,
            'PHPExcel_RichText_ITextElement' => ITextElement::class,
            'PHPExcel_RichText_Run' => Run::class,
            'PHPExcel_RichText_TextElement' => TextElement::class,
            'PHPExcel_Shared_CodePage' => CodePage::class,
            'PHPExcel_Shared_Date' => Date::class,
            'PHPExcel_Shared_Drawing' => \PhpOffice\PhpSpreadsheet\Shared\Drawing::class,
            'PHPExcel_Shared_Escher' => Escher::class,
            'PHPExcel_Shared_File' => File::class,
            'PHPExcel_Shared_Font' => \PhpOffice\PhpSpreadsheet\Shared\Font::class,
            'PHPExcel_Shared_OLE' => OLE::class,
            'PHPExcel_Shared_OLERead' => OLERead::class,
            'PHPExcel_Shared_PasswordHasher' => PasswordHasher::class,
            'PHPExcel_Shared_String' => StringHelper::class,
            'PHPExcel_Shared_TimeZone' => TimeZone::class,
            'PHPExcel_Shared_XMLWriter' => XMLWriter::class,
            'PHPExcel_Shared_Excel5' => \PhpOffice\PhpSpreadsheet\Shared\Xls::class,
            'PHPExcel_Style_Alignment' => Alignment::class,
            'PHPExcel_Style_Border' => Border::class,
            'PHPExcel_Style_Borders' => Borders::class,
            'PHPExcel_Style_Color' => Color::class,
            'PHPExcel_Style_Conditional' => Conditional::class,
            'PHPExcel_Style_Fill' => Fill::class,
            'PHPExcel_Style_Font' => Font::class,
            'PHPExcel_Style_NumberFormat' => NumberFormat::class,
            'PHPExcel_Style_Protection' => \PhpOffice\PhpSpreadsheet\Style\Protection::class,
            'PHPExcel_Style_Supervisor' => Supervisor::class,
            'PHPExcel_Worksheet_AutoFilter' => AutoFilter::class,
            'PHPExcel_Worksheet_BaseDrawing' => BaseDrawing::class,
            'PHPExcel_Worksheet_CellIterator' => CellIterator::class,
            'PHPExcel_Worksheet_Column' => Column::class,
            'PHPExcel_Worksheet_ColumnCellIterator' => ColumnCellIterator::class,
            'PHPExcel_Worksheet_ColumnDimension' => ColumnDimension::class,
            'PHPExcel_Worksheet_ColumnIterator' => ColumnIterator::class,
            'PHPExcel_Worksheet_Drawing' => Drawing::class,
            'PHPExcel_Worksheet_HeaderFooter' => HeaderFooter::class,
            'PHPExcel_Worksheet_HeaderFooterDrawing' => HeaderFooterDrawing::class,
            'PHPExcel_WorksheetIterator' => Iterator::class,
            'PHPExcel_Worksheet_MemoryDrawing' => MemoryDrawing::class,
            'PHPExcel_Worksheet_PageMargins' => PageMargins::class,
            'PHPExcel_Worksheet_PageSetup' => PageSetup::class,
            'PHPExcel_Worksheet_Protection' => Protection::class,
            'PHPExcel_Worksheet_Row' => Row::class,
            'PHPExcel_Worksheet_RowCellIterator' => RowCellIterator::class,
            'PHPExcel_Worksheet_RowDimension' => RowDimension::class,
            'PHPExcel_Worksheet_RowIterator' => RowIterator::class,
            'PHPExcel_Worksheet_SheetView' => SheetView::class,
            'PHPExcel_Writer_Abstract' => BaseWriter::class,
            'PHPExcel_Writer_CSV' => Csv::class,
            'PHPExcel_Writer_Exception' => \PhpOffice\PhpSpreadsheet\Writer\Exception::class,
            'PHPExcel_Writer_HTML' => \PhpOffice\PhpSpreadsheet\Writer\Html::class,
            'PHPExcel_Writer_IWriter' => IWriter::class,
            'PHPExcel_Writer_OpenDocument' => Ods::class,
            'PHPExcel_Writer_PDF' => Pdf::class,
            'PHPExcel_Writer_Excel5' => Xls::class,
            'PHPExcel_Writer_Excel2007' => Xlsx::class,
            'PHPExcel_CachedObjectStorageFactory' => CellsFactory::class,
            'PHPExcel_Calculation' => Calculation::class,
            'PHPExcel_Cell' => Cell::class,
            'PHPExcel_Chart' => Chart::class,
            'PHPExcel_Comment' => Comment::class,
            'PHPExcel_Exception' => Exception::class,
            'PHPExcel_HashTable' => HashTable::class,
            'PHPExcel_IComparable' => IComparable::class,
            'PHPExcel_IOFactory' => IOFactory::class,
            'PHPExcel_NamedRange' => NamedRange::class,
            'PHPExcel_ReferenceHelper' => ReferenceHelper::class,
            'PHPExcel_RichText' => RichText::class,
            'PHPExcel_Settings' => Settings::class,
            'PHPExcel_Style' => Style::class,
            'PHPExcel_Worksheet' => Worksheet::class,
        ];

        $methods = [
            'MINUTEOFHOUR' => 'MINUTE',
            'SECONDOFMINUTE' => 'SECOND',
            'DAYOFWEEK' => 'WEEKDAY',
            'WEEKOFYEAR' => 'WEEKNUM',
            'ExcelToPHPObject' => 'excelToDateTimeObject',
            'ExcelToPHP' => 'excelToTimestamp',
            'FormattedPHPToExcel' => 'formattedPHPToExcel',
            'Cell::absoluteCoordinate' => 'Coordinate::absoluteCoordinate',
            'Cell::absoluteReference' => 'Coordinate::absoluteReference',
            'Cell::buildRange' => 'Coordinate::buildRange',
            'Cell::columnIndexFromString' => 'Coordinate::columnIndexFromString',
            'Cell::coordinateFromString' => 'Coordinate::coordinateFromString',
            'Cell::extractAllCellReferencesInRange' => 'Coordinate::extractAllCellReferencesInRange',
            'Cell::getRangeBoundaries' => 'Coordinate::getRangeBoundaries',
            'Cell::mergeRangesInCollection' => 'Coordinate::mergeRangesInCollection',
            'Cell::rangeBoundaries' => 'Coordinate::rangeBoundaries',
            'Cell::rangeDimension' => 'Coordinate::rangeDimension',
            'Cell::splitRange' => 'Coordinate::splitRange',
            'Cell::stringFromColumnIndex' => 'Coordinate::stringFromColumnIndex',
        ];

        // Keep '\' prefix for class names
        $prefixedClasses = [];
        foreach ($classes as $key => &$value) {
            $value = str_replace('PhpOffice\\', '\\PhpOffice\\', $value);
            $prefixedClasses['\\' . $key] = $value;
        }
        $mapping = $prefixedClasses + $classes + $methods;

        return $mapping;
    }

    /**
     * Search in all files in given directory.
     *
     * @param string $path
     */
    private function recursiveReplace($path)
    {
        $patterns = [
            '/*.md',
            '/*.txt',
            '/*.TXT',
            '/*.php',
            '/*.phpt',
            '/*.php3',
            '/*.php4',
            '/*.php5',
            '/*.phtml',
        ];

        foreach ($patterns as $pattern) {
            foreach (glob($path . $pattern) as $file) {
                if (strpos($path, '/vendor/') !== false) {
                    echo $file . " skipped\n";

                    continue;
                }
                $original = file_get_contents($file);
                $converted = $this->replace($original);

                if ($original !== $converted) {
                    echo $file . " converted\n";
                    file_put_contents($file, $converted);
                }
            }
        }

        // Do the recursion in subdirectory
        foreach (glob($path . '/*', GLOB_ONLYDIR) as $subpath) {
            if (strpos($subpath, $path . '/') === 0) {
                $this->recursiveReplace($subpath);
            }
        }
    }

    public function migrate()
    {
        $path = realpath(getcwd());
        echo 'This will search and replace recursively in ' . $path . PHP_EOL;
        echo 'You MUST backup your files first, or you risk losing data.' . PHP_EOL;
        echo 'Are you sure ? (y/n)';

        $confirm = fread(STDIN, 1);
        if ($confirm === 'y') {
            $this->recursiveReplace($path);
        }
    }

    /**
     * Migrate the given code from PHPExcel to PhpSpreadsheet.
     *
     * @param string $original
     *
     * @return string
     */
    public function replace($original)
    {
        $converted = str_replace($this->from, $this->to, $original);

        // The string "PHPExcel" gets special treatment because of how common it might be.
        // This regex requires a word boundary around the string, and it can't be
        // preceded by $ or -> (goal is to filter out cases where a variable is named $PHPExcel or similar)
        $converted = preg_replace('~(?<!\$|->)(\b|\\\\)PHPExcel\b~', '\\' . Spreadsheet::class, $converted);

        return $converted;
    }
}
