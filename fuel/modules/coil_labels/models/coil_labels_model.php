<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(FUEL_PATH.'models/base_module_model.php');
require_once(MODULES_PATH.'/coil_labels/config/coil_labels_constants.php');
require_once(APPPATH.'helpers/tcpdf/config/lang/eng.php');
require_once(APPPATH.'helpers/tcpdf/tcpdf.php');

class coil_labels_model extends Base_module_model {
    function __construct() {
        parent::__construct('aspen_tblbilldetails');// table name
    }

    function getPaginatedCoilLabels($get) {

        $aColumns = array( 'vIRnumber', 'dReceivedDate', 'vStatus', 'nPartyName' );
	
	    /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "vIRnumber";
        
        $sTable = "aspen_tblinwardentry";

        $sLimit = "";
        if ( isset( $get['start'] ) && $get['length'] != '-1' )
        {
            $sLimit = "LIMIT ".mysql_real_escape_string( $get['start'] ).", ".
                mysql_real_escape_string( $get['length'] );
        }
        $sOrder = '';
        if ( isset( $get['iSortCol_0'] ) )
	    {
            $sOrder = "ORDER BY  ";
            for ( $i=0 ; $i<intval( $get['iSortingCols'] ) ; $i++ )
            {
                if ( $get[ 'bSortable_'.intval($get['iSortCol_'.$i]) ] == "true" )
                {
                    $sOrder .= $aColumns[ intval( $get['iSortCol_'.$i] ) ]."
                        ".mysql_real_escape_string( $get['sSortDir_'.$i] ) .", ";
                }
            }
            
            $sOrder = substr_replace( $sOrder, "", -2 );
            if ( $sOrder == "ORDER BY" )
            {
                $sOrder = "";
            }
        }
        $sWhere = "";
        if ( $get['search']['value'] != "" )
        {
            $sWhere = "WHERE (";
            for ( $i=0 ; $i<count($aColumns) ; $i++ )
            {
                $sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $get['search']['value'] )."%' OR ";
            }
            $sWhere = substr_replace( $sWhere, "", -3 );
            $sWhere .= ')';
        }

        /* Individual column filtering */
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
            if ( $get['columns'][$i]['searchable'] == "true" && $get['columns'][$i]['search']['value'] != '' )
            {
                if ( $sWhere == "" )
                {
                    $sWhere = "WHERE vStatus != 'RECEIVED'";
                }
                else
                {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($get['sSearch_'.$i])."%' ";
            }
        }

        $sQuery = "
		SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
        FROM   $sTable
        left join aspen_tblpartydetails on aspen_tblpartydetails.nPartyId = aspen_tblinwardentry.nPartyId 
		$sWhere
		$sOrder
		$sLimit
	";
    $rResult = $this->db->query( $sQuery );
    $arr = $rResult->result();
    
	/* Data set length after filtering */
	$sQuery = "
		SELECT FOUND_ROWS() as rowscount
	";
	$rResultFilterTotal = $this->db->query( $sQuery );
    $aResultFilterTotal = $rResultFilterTotal->result();
	$iFilteredTotal = $aResultFilterTotal[0]->rowscount;
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT(".$sIndexColumn.") as totalcount
		FROM   $sTable
	";
	$rResultTotal = $this->db->query( $sQuery );
    $aResultTotal = $rResultTotal->result();
	$iTotal = $aResultTotal[0]->totalcount;
	
	
	/*
	 * Output
	 */
	$output = array(
		// "sEcho" => intval($get['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);
    
    foreach($arr as $aRow) {
        $row = array();
        $aRow = (array) $aRow;
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			
			if ( $aColumns[$i] != ' ' )
			{
				/* General output */
				$row[] = $aRow[ $aColumns[$i] ];
			}
		}
		$output['aaData'][] = $row;
	}
	// print_r($output);exit;
	echo json_encode( $output );

    }

    function getBundleDetails($coilNumber,$bundleNo = '') {
        $arr = $this->getCoilDetails($coilNumber); 
        if($arr[0]->vprocess == 'Slitting') {
            $where = "asl.vIRnumber = '".$arr[0]->vIRnumber."'";
            if($bundleNo !== '') {
                $where .= "and nSno = '".$bundleNo."'";
            }

            $strSlittingBundleDetails = "select asl.*, ai.fThickness, ai.vprocess from aspen_tblslittinginstruction  as asl left join aspen_tblinwardentry as ai on asl.vIRnumber = ai.vIRnumber where $where order by nSno";
            $bundleResult = $this->db->query($strSlittingBundleDetails);
            $returnArr = [];
            foreach($bundleResult->result() as $arr) {
                $returnArr[] = (array) $arr;
            } 
            return $returnArr;
        } else if($arr[0]->vprocess == 'Cutting') {
            $where = "asl.vIRnumber = '".$arr[0]->vIRnumber."'";
            if($bundleNo !== '') {
                $where .= "and nSno = '".$bundleNo."'";
            }
            $strSlittingBundleDetails = "select asl.*, ai.fThickness, ai.vprocess from aspen_tblcuttinginstruction as asl left join aspen_tblinwardentry as ai on asl.vIRnumber = ai.vIRnumber $where order by nSno";
            $bundleResult = $this->db->query($strSlittingBundleDetails);
            $returnArr = [];
            foreach($bundleResult->result() as $arr) {
                $returnArr[] = (array) $arr;
            } 
            
            return $returnArr;
        }
    }

    function getCoilDetails($coilNumber) {
        $strCoilDetails = "select * from aspen_tblinwardentry as ai left join aspen_tblpartydetails as ap on ai.nPartyId = ap.nPartyId left join aspen_tblmatdescription as am on ai.nMatId = am.nMatId where ai.vIRnumber = '".$coilNumber."'";
        $rResult = $this->db->query( $strCoilDetails );
        return $rResult->result();
    }
}