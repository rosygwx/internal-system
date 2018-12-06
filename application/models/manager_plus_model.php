<?php
/**
  * Manager Plus Model
*/

class Manager_plus_model extends CI_Model{

	public function __construct(){
		$this->mp_db = $this->load->database('managerplus', true);
		//$this->truck_table = 'truck';
	}

	public function AssetReport($bdate = '2018-11-19', $edate = '2018-11-19'){
		/*************************/
		/*Work Order Cost = Parts + Labor*/
		$sql = "
		
		DECLARE @bdateeeeeeeeee  varchar(60) ;
		SET @bdateeeeeeeeee = '".$bdate."';
		DECLARE @edateeeeeeeeee varchar(60) ;
		SET @edateeeeeeeeee = '".$edate."';
		DECLARE @SQL VARCHAR(MAX)  
		SET @SQL = 'SELECT Assets.[Asset ID], Assets.[Asset Code],

			/* Part Cost */
			ISNULL((SELECT SUM(WOP.[Unit Cost] * WOP.[Quantity]) 
				FROM WorkOrders WO
				INNER JOIN WorkOrderParts WOP
				ON WO.[Work Order ID] = WOP.[Work Order ID]
				WHERE Assets.[Asset ID] = WO.[Asset ID]
				AND WO.[Status] = 300 --complete 300, active 100
				AND WO.[Date Created] >= ''' + @bdateeeeeeeeee + '''
				AND WO.[Date Created] <= ''' + @edateeeeeeeeee + '''
			), 0) [Part Cost],


			/* Labor Cost */
			ISNULL((SELECT SUM(WOL.[Hours] * WOL.[Labor Rate]) 
				FROM WorkOrders WO
				INNER JOIN WorkOrderLabor WOL
				ON WO.[Work Order ID] = WOL.[Work Order ID]
				WHERE Assets.[Asset ID] = WO.[Asset ID]
				AND WO.[Status] = 300 --complete 300, active 100
				AND WO.[Date Created] >= ''' + @bdateeeeeeeeee + '''
				AND WO.[Date Created] <= ''' + @edateeeeeeeeee + '''
			), 0) [Labor Cost],
			
			/* Purchase Cost other than Work Order*/
			ISNULL((SELECT SUM(PO.[Total Cost])
				FROM PO
				WHERE Assets.[Asset ID] = PO.[PO User Define Field 2]
				AND PO.[Expense Budget ID] = ''SERVICE COST''
				AND PO.[Date Ordered] >= ''' + @bdateeeeeeeeee + '''
				AND PO.[Date Ordered] <= ''' + @edateeeeeeeeee + '''
				), 0) [Purchase Cost],

			/* Fuel Cost from Log*/
			ISNULL((SELECT SUM(LD.[Fuel Cost])
				FROM LogDetail LD
				WHERE Assets.[Asset ID] = LD.[Asset ID]
				AND LD.[Post Date] >= ''' + @bdateeeeeeeeee + '''
				AND LD.[Post Date] <= ''' + @edateeeeeeeeee + '''
				), 0) [Fuel Cost]

		FROM Assets

		WHERE Assets.[Active] = ''true''
		AND Assets.[Budget ID] IN (''OPERATION'', ''PURCHASING'')

		ORDER BY Assets.[Asset Code] ASC
		;'

		--Print(@SQL)
		EXEC(@SQL)

		";

		//$sql = 'SELECT top 10 * from po;';
		// runs the query to your MS SQL connection (automatically)
		$query = $this->mp_db->query($sql);
		$result = $query->result();

		return $result;
	}
}
