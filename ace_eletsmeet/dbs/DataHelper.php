<?php
/**
* Script Name			: DataHelper.php
* Class Name			: DataHelper
* Purpose				: DataHelper stands for common data access layer for suporting database functionality 							with Mysql
* Parameters			: none
* Return Value			: none 
* Author				: Praveen Nair
* Creation Date			: 1/3/2007
* Modification Date		: 
* Comments				: 
**/
Class DataHelper
{
	//var $mObjConn	  ;			//Member Variable contains Connection object
	var $marrRecs	  ;			//Member Variable contains Recordset 	 
	var $mvarErrorCode = 0 ;	//Member Variable contains Error Code
	var $mvarRecCount  ;		//Member Variable contains RecordCount
	var $marrParams;            //Member Variable containing an array of IN/OUT params for Stored Procedures 
	var $objmysqli;				//Member variable used as an object for connection object 

	//DEFINED BY PRAVEEN
	var $IsTransaction;			//For Checking if transaction is on

	//PARAMETERS FOR CONNECTION OBJECT
	var $username;
	var $password;
	var $hostname;
	var $databasename;
	var $port;
                  var $affectedRows = 0;
	
	/**
	* Function Name			: constructor for the Class 
	* Purpose				: Constructor classDal DataHelper Class opens a mysqli connection
	* Parameters			: none
	* Return Value			: none 
	* Author				:  
	* Creation Date			: 1/3/2007
	* Modification Date		:
	*/

	function __construct($strConnectionString)
	{
		try
		{
			//SET TRANSACTION FLAG TO FALSE
			$this->IsTransaction = 0;
			//VALIDATE THE CONNECTION STRING, PARSE AND SET TO PRIVATE VARIABLES
			if(strlen(trim($strConnectionString)) == 0)
			{
				throw new Exception("Connection String Empty",101);
			}

			$arrConnectionParams = explode(":",$strConnectionString);
			
			if(count($arrConnectionParams) < 3)
			{
				throw new Exception("Connection String Parameters not valid",102);
			}

			$this->username = $arrConnectionParams[0];
			$this->password = $arrConnectionParams[1];
			$this->hostname = $arrConnectionParams[2];
			$this->databasename = $arrConnectionParams[3];
			$this->port = $arrConnectionParams[4];

		}
		catch(Exception $e)
		{
			if(isset($this->objmysqli))
			{
				unset($this->objmysqli);
			}
			throw new Exception("Datahelper:Constructer Failed".$e->getMessage(),$e->getCode());
		}
	}	

	/**
	* Function Name			: beginTrans()
	* Purpose				: used to mark the begining of a transaction by setting the autocommit as false 
								so that the transaction can be rolled back in case of any error/exception
	* Parameters			: none
	* Return Value			: none  
	* Author				: 
	* Creation Date			: 1/3/2007
	* Modification Date		:
	*/
	public function BeginTrans()
	{
		try
		{
			if($this->IsTransaction != 1)
			{
				//IF CONNECTION NOT OPEN
				if(!isset($this->objmysqli))
				{
					//CALL OPEN CONNECTION
					$this->OpenConnection();
				}
				//SET TRANSACTION MODE TO TRUE
				$this->objmysqli->autocommit(FALSE);
				$this->IsTransaction = 1;
			}
		}
		catch(Exception $e)
		{
			if(isset($this->objmysqli))
			{
				$this->objmysqli->close();
				unset($this->objmysqli);
			}
			throw new Exception("Datahelper:Begintrans():Begin Transaction failed:".$e->getMessage(),103);
		}
	}
	
	private function OpenConnection()
	{
		try
		{
			//CHECK IF CONNECTION STRING IS NOT EMPTY
			if(	(strlen(trim($this->username)) == 0) or 
			    (strlen(trim($this->password))==0)	 or 
				(strlen(trim($this->hostname)) == 0)  or 
				(strlen(trim($this->databasename))==0))
			{
				throw new Exception("Could not open Connection, Connection string params invalid",105);
				break;
			}

			//CHECK IF CONNECTION ALREADY OPEN
			if(!isset($this->objmysqli))
			{
				if ($this->port <> "")
                                {
                                        $this->objmysqli = new mysqli($this->hostname, $this->username, $this->password, $this->databasename, $this->port);
                                }
                                else
                                {
                                        $this->objmysqli = new mysqli($this->hostname, $this->username, $this->password, $this->databasename);
                                }

                                if (mysqli_connect_errno())
                                {
                                        throw new Exception("Could not open Connection".mysqli_error(),105);
                                        break;
                                }



				//OPEN CONNECTION
				/*
				if ($this->port <> "")
				{
					$this->objmysqli = new mysqli('p:'.$this->hostname, $this->username, $this->password, $this->databasename, $this->port);
				}
				else 
				{
					$this->objmysqli = new mysqli('p:'.$this->hostname, $this->username, $this->password, $this->databasename);
				}

				if (mysqli_connect_errno())
				{
					throw new Exception("Could not open Connection".mysqli_error(),105);
					break;
				}*/
			}
		}
		catch(Exception $e)
		{
			throw new Exception("Datahelper:OpenConnection() Failed".$e->getMessage(),$e->getCode());
		}
	}
	
	/**
	* Function Name			: putRecords() 
	* Purpose				: function is used perform INSERT/UPDATE using stored procedures or sql queries 
	* Parameters			: type -> can be qry for Query or SP from Stord Procedures
							  string -> can be a sql query or a Stored Procedure name
	* Return Value			: returns true/false for INSERT/UPDATE/DELETE in case of type=qry is 
								success/failure
	* Author				:  
	* Creation Date			: 1/3/2007
	* Modification Date		:
	*/	

	function putRecords($type,$sqlstatement)
	{
		try
		{
			//VALIDATE INPUT PARAMS
			if((strlen(trim($type)) == 0) or (strlen(trim($sqlstatement)) == 0))
			{
				throw new Exception("Put Records params invalid",107);
				break;
			}
			//IF CONNECTION IS NOT OPEN

			if($this->IsTransaction != 1)
			{
				/*
				//IF CONNECTION NOT OPEN
				if(!isset($this->objmysqli))
				{
					throw new Exception("Connection not opened",122);
				}
				//CALL OPEN CONNECTION
				*/
				$this->OpenConnection();
			}

			//CHECK TYPE & EXECUTE QUERY			
			if($type=='QR')
			{
				//Start for Sql Query  Processing 
				$qryResult = $this->objmysqli->query($sqlstatement);
                                                                        $this->affectedRows = mysqli_affected_rows($this->objmysqli);
				if($qryResult == FALSE)
				{
					throw new Exception("Insert/Update failed ".$this->objmysqli->error,108);
					break;
				}
				$this->clearParams();

				if ($this->IsTransaction <> 1){
					$this->objmysqli->close();
					unset($this->objmysqli);
				}				
                                                                        return $qryResult;
				//End for Sql Query  Processing 
			}
			else if($type=='SP')
			{	
				//Start for Stored Procedure Processing 

				/* Start for creating the parameter list for stored procedure */
				for($i=0;$i<sizeof($this->marrParams);$i++) 
				{
					if($this->marrParams[$i][1]=="O") 
					{
						$strSuffix = "@";
						if(strlen(trim($strOutParameter))==0) 
						{
							$strOutParameter = $strSuffix.$this->marrParams[$i][0];
						} 
						else 
						{
							$strOutParameter = $strOutParameter .",".$strSuffix.$this->marrParams[$i][0];
						}
					} 
					else 
					{
						if($i==0) 
						{
							$strParameter = $this->marrParams[$i][0];
						} 
						else	
						{
							$strParameter = $strParameter .",".$this->marrParams[$i][0];
						}
					}
				}
				/*End for creating the parameter list for stored procedure */

				/*Start for Calling the Stored Procedure*/
				if($strParameter!="" && $strOutParameter!="") 
				{
					$strProc = "call ". $sqlstatement."(".$strParameter.",".$strOutParameter.")";    
				}   
				if($strParameter!="" && $strOutParameter=="") 
				{
					$strProc = "call ". $sqlstatement."(".$strParameter.")";    
				}   
				if($strParameter=="" && $strOutParameter!="") 
				{
					$strProc = "call ". $sqlstatement."(".$strOutParameter.")";    
				}   
				if($strParameter=="" && $strOutParameter=="") 
				{
					$strProc = "call ". $sqlstatement."()"; 
				}

				//print "putRecords ".$strProc."<br>"; exit;
				//$this->trace_log("/tmp/","WEB_PutRecords",$strProc);

				$result = $this->objmysqli->query($strProc);
				if($result== FALSE) 
				{
					throw new Exception("Stored Procedure not executed".$this->objmysqli->error,109);
				}

				/*Start for populating the Out Parameter if any*/
				if(strlen(trim($strOutParameter))>0) 
				{
					$strOutParameter = "select ". $strOutParameter;
					$result = $this->objmysqli->query($strOutParameter);
					if ( !$result ) 
					{
					  throw new Exception("Problem in getting the OUT Params",110);
					}
					
					//$intCnt = 0;
					//while ($myrow = $result->fetch_array(MYSQLI_NUM))
					/*while ($myrow = $result->fetch_array(MYSQLI_ASSOC))
					{	
						 $arrRecs[$intCnt]= $myrow;
						 $intCnt++;
					}*/
					
				}
				if(mysqli_affected_rows($this->objmysqli)!=0)
				{
					$intCnt = 0;
					$arrRecs = array();	
					while ($myrow = $result->fetch_array(MYSQLI_ASSOC)) 
					{
						  $arrRecs[$intCnt]= $myrow;
						  $intCnt++;
					}
				}
				
				
				$this->marrRecs = $arrRecs;		// Record_Array[field_array[]]
				$this->mvarRecCount = sizeof($arrRecs);
				/*End for populating the Out Parameter if any*/

				$this->clearParams();

				if ($this->IsTransaction <> 1){
					$this->objmysqli->close();
					unset($this->objmysqli);
				}

				return $this->marrRecs;
				/*End for Calling the Stored Procedure*/

				//End for Stored Procedure Processing 
			}
			else
			{
				throw new Exception("Sql Query / Stored Procedure not defind",111);
			}
		}
		catch(Exception $e)
		{
			if(isset($this->objmysqli))
			{
				if ($this->IsTransaction <> 1)
				{
					unset($this->objmysqli);
				}
			}
			throw new Exception("Datahelper:putRecords() Failed : " .$e->getMessage(),$e->getCode());
		}	

	}
	
	
	/**
	* Function Name			: fetchRecords() 
	* Purpose				: function is used retrieve records using stored procedures or sql queries 
	* Parameters			: type -> can be qry for Query or SP from Stord Procedures
							  string -> can be a sql query or a Stored Procedure name 		
	* Return Value			: returns an associative array of result set in case type is qry
							  
	* Author				:  
	* Creation Date			: 1/3/2007
	* Modification Date		:
	*/
	
	function fetchRecords($type,$sqlstatement)
	{
		try
		{	

			if($sqlstatement=="")
			{
				throw new Exception("fetchRecords params invalid",112);
				break;
			}
			
			//OPEN CONNECTION ELSE USE THE OPENED CONNECTION

			//IF CONNECTION NOT OPEN
			if(!isset($this->objmysqli))
			{
				//CALL OPEN CONNECTION
				$this->OpenConnection();
			}			

			if($type=='QR')
			{
				$intCnt = 0;
				/*Start prcessing in case type is qry*/
				
				if (!$res = $this->objmysqli->query($sqlstatement)) 
				{
				  throw new Exception("<br>Sql Query failure ".$this->objmysqli->error,113);
				  break ;
				}
				else
				{	//$arrRecs ='';
                                                                                          $intCnt =0;
                                                                                          $arrRecs =array();
					while ($myrow = $res->fetch_array(MYSQLI_ASSOC))
					{
						$arrRecs[$intCnt]= $myrow;
						$intCnt++;
					} 
					$this->marrRecs = $arrRecs;		
					$this->mvarRecCount = sizeof($arrRecs);
					$this->clearParams(); 
					if ($this->IsTransaction <> 1)
					{
						$this->objmysqli->close();
						unset($this->objmysqli);
					} 
					return $this->marrRecs;				
				 }
				/*End prcessing in case type is qry*/
			}
			else if($type=='SP')
			{
				/*Start prcessing in case type is SP*/
				
				/*Start for creating Parameter list */
				for($i=0;$i<sizeof($this->marrParams);$i++)	
				{
					if($this->marrParams[$i][1]=="O") 
					{
						$strSuffix = "@";
						if(strlen(trim($strOutParameter))==0) 
						{
							$strOutParameter = $strSuffix.$this->marrParams[$i][0];
						} 
						else 
						{
							$strOutParameter = $strOutParameter .",".$strSuffix.$this->marrParams[$i][0];
						}
					} 
					else 
					{
						if($i==0) 
						{
							$strParameter = $this->marrParams[$i][0];
						} 
						else 
						{
							$strParameter = $strParameter .",".$this->marrParams[$i][0];
						}
					}
				}
				/*End for creating Parameter list */
				/*Start for Calling the Stored Procedure*/
				if($strParameter!="" && $strOutParameter!="")
				{
					$strProc = "call ". $sqlstatement."(".$strParameter.",".$strOutParameter.")";
				}   
				if($strParameter!="" && $strOutParameter=="") 
				{
					$strProc = "call ". $sqlstatement."(".$strParameter.")";    
				}   
				if($strParameter=="" && $strOutParameter!="") 
				{
					$strProc = "call ". $sqlstatement."(".$strOutParameter.")";    
				}   
				if($strParameter=="" && $strOutParameter=="") 
				{
					$strProc = "call ". $sqlstatement."()";    
				}
				/*Start for calling Stored Procedure*/
				
				//print "fetchRecords ".$strProc."; <br>";//exit;
				//$this->trace_log("/tmp/","WEB_FetchRecords",$strProc);
				
				$result = $this->objmysqli->query($strProc);
				if(!$result)
				{
					throw new Exception("<br>Stored Procedure not executed:<br>Mysql Error:".$this->objmysqli->error,114);
					break;
				}
				
				if(strlen(trim($strOutParameter))>0) 
				{
					$strOutParameter = "select ". $strOutParameter;
					//echo $strOutParameter;
					$result = $this->objmysqli->query($strOutParameter);
					if (!$result) 
					{
						throw new Exception("<br>Problem in getting the OUT Params",115);
						break;
					}
				}
				
				if(mysqli_affected_rows($this->objmysqli)!=0)
				{
				
					$intCnt = 0;
					$arrRecs = array();	
					while ($myrow = $result->fetch_array(MYSQLI_ASSOC)) 
					{
						 $arrRecs[$intCnt]= $myrow;
						 $intCnt++;
					}
				 }
					unset($result);
					$this->marrRecs = $arrRecs;		
					//Set Record Count
					$this->mvarRecCount = sizeof($arrRecs);
					$this->clearParams();
				
				if ($this->IsTransaction <> 1){
					$this->objmysqli->close();
					unset($this->objmysqli);
				}

				return $this->marrRecs;			
				/*End for calling Stored Procedure*/

				/*End prcessing in case type is SP*/
				
			}
			else
			{
				throw new Exception("<br>Sql Query/Stored Procedure not defind",116);
			}
		}
		catch(Exception $e)
		{
			if(isset($this->objmysqli))
			{
				$this->objmysqli->close();
				unset($this->objmysqli);
			}
			throw new Exception("<br>Datahelper:fetchRecords() Failed : " .$e->getMessage(),$e->getCode());
		}
	}
	

	/**
	*Function Name 	:	setParam
	*Purpose		:	Add the parameters to the stored proc being called.
						
	*Parameters	  	:	string parameter name, string parameter type (I-In param O-Outparam)
	*Return Value	:	
	*Author			: 	
	*Created date	:	1/3/2007
	*Modified date	:	
	*Comments
	**/
	function setParam($strParam, $strType)
	{
		//strtype = I for in param
		//strType = O for out param
		try
		{
			if($strParam =='')
			{
				throw new Exception("Parameter Name not defind",117);
			}
			if($strType == '')
			{
				throw new Exception("Parameter Type not defind",118);
			}

			$intCnt=0;
			if(sizeof($this->marrParams)>0) {
				$intCnt = sizeof($this->marrParams);
			}
			$this->marrParams[$intCnt][0]=$strParam;
			$this->marrParams[$intCnt][1]=$strType;	
		}
		catch(Exception $e)
		{
			if(isset($this->objmysqli))
			{
				unset($this->objmysqli);
			}
			throw new Exception("Datahelper:setParam() Failed : ".$e->getMessage(),$e->getCode());
		}

	}
	
	/**
	*Function Name 	:	clearParams
	*Purpose		:	for clearing the parmeters which were set for calling the stored procedures
						
	*Parameters	  	:	none
	*Return Value	:	void
	*Author			: 	
	*Created date	:	1/3/2007
	*Modified date	:	
	*Comments
	**/

	function clearParams()
	{
		unset($this->marrParams);
	}
	
	/**
	* Function Name			: commitTrans()
	* Purpose				: used commit or save a tracsaction to the point from where the begintrans() 
								was called 
	* Parameters			: none
	* Return Value			: none  
	* Author				: 
	* Creation Date			: 1/3/2007
	* Modification Date		:
	*/
	function CommitTrans()
	{
		try
		{
			//CHECK IF CONNECTION OPEN
			if(!isset($this->objmysqli))
			{
				throw new Exception("Connection not opened",105);
			}
			$this->objmysqli->commit();
			$this->objmysqli->close();
		}
		catch(Exception $e)
		{
			if(isset($this->objmysqli))
			{
				unset($this->objmysqli);
			}
			throw new Exception("Datahelper:commitTrans() Failed : ".$e->getMessage(),$e->getCode());
		}
	}

	/**
	* Function Name			: rollbackTrans()
	* Purpose				: used for rolling back the transacrtion in case of any exception/error 
	* Parameters			: none
	* Return Value			: none  
	* Author				: 
	* Creation Date			: 1/4/2007
	* Modification Date		:
	*/
	function RollbackTrans()
	{
		try
		{
			//CHECK IF CONNECTION IS OPEN
			if(!isset($this->objmysqli))
			{
				throw new Exception("Connection not opened. ",105);
			}
			if ($this->IsTransaction == 1) {
				$res = $this->objmysqli->rollback();
			}
			$this->objmysqli->close();
			$this->IsTransaction = 0;
		}
		catch(Exception $e)
		{
			if(isset($this->objmysqli))
			{
				unset($this->objmysqli);
			}
			throw new Exception("Datahelper:rollbackTrans() Failed : ".$e->getMessage(),$e->getCode());
		}
	}

	
	/**
	* Function Name			: getDataByPage()
	* Purpose				: function used to get limited records of a recordset
	* Parameters			: strSqlQuery -> sql Query
							  start -> the start index or the offset of the result Set 
							  limit -> No of records or row count 
								
	* Return Value			: assosiative array of records
	* Author				: 
	* Creation Date			: 1/3/2007
	* Modification Date		:
	*/
	
	function getDataByPage($type='QR', $strSqlQuery,$start,$limit)
	{	
		try
		{
			//IF CONNECTION IS OPEN
			if(!isset($this->objmysqli))
			{
				throw new Exception("Connection not opened",105);
			}
			//CALL OPEN CONNECTION
			$this->OpenConnection();
			
			if($strSqlQuery=='')
			{
				throw new Exception("Sql Query not defind ",119);
			}
			if(strlen(trim($start))==0)
			{
				throw new Exception("Offset Count not defind",120);
			}
			if($limit==0 || strlen(trim($limit))==0)
			{
				throw new Exception("No of rows not defind",121);
			}

			$strSqlQuery=$strSqlQuery." LIMIT ". $start ."," .$limit;
			$resultSql = $this->fetchRecords($type,$strSqlQuery);
			return $resultSql;	
		}
		catch(Exception $e)
		{
			if(isset($this->objmysqli))
			{
				$this->objmysqli->close();
				unset($this->objmysqli);
			}
			throw new Exception("Datahelper:getDataByPage() Failed : ".$e->getMessage(),$e->getCode());
		}
	}
	
	function __destruct()
	{
		//CHECK IF CONNECTION IS OPEN THEN CLOSE
		//DESTROY MYSQLI
		if((is_object($this->objmysqli)) && (isset($this->objmysqli)))
		{
			unset($this->objmysqli);
		}
	}	
}
?>
