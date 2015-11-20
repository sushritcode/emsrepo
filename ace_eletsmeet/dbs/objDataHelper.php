<?php
try
{
	$db_ConnectionString = DB_CONNECTIONSTRING;
	//READ CONNECTIONSTRING FROM CONFIG
	if((strlen(trim($db_ConnectionString))==0))
	{
		throw new Exception(" Connection String not found",101);
	}
	else
	{
		$arrConnectionString = explode(":",$db_ConnectionString);
		if(!is_array($arrConnectionString))
		{
			throw new Exception("Invald Connection String ",102);
		}
		else
		{
			if(count($arrConnectionString) !=5)
			{
				throw new Exception("Invald Connection String ",102);
			}
		}
	}
	
	//CREATE DKMANAGER OBJECT
	$objDataHelper = new DataHelper($db_ConnectionString);
	if(!is_object($objDataHelper))
	{
		throw new Exception("DataHelper Object did not instantiate",104);
	}
}
catch(Exception $e)
{
	RaiseError($_SERVER["PHP_SELF"], $e->getCode(), $e->getMessage());
}
?>