<?php

function lockedForEdit($tgl = array(), $t = array()) //t = current tgl
{
	if(count($tgl) < 3)
		return false;

	$bulan_ini = date('n');
	$bulan_input = intval($tgl[1]);
	$tgl_ini = date('j');
	$tgl_input = intval($tgl[0]);
	$tahun_ini = date('Y');
	$tahun_input = intval($tgl[2]);
	
	$bulan_old = intval($t[1]);

	$locked = false;
	
	if(($tahun_ini != $tahun_input) && ($bulan_ini == $bulan_input))
		$locked = true;
	
	if(($tahun_ini - $tahun_input) > 1)
		$locked = true;


	if(($tahun_ini != $tahun_input) && ($bulan_ini > 1))
		$locked = true;
	
	if(($bulan_input == $bulan_ini) && ($tgl_ini > 10) && ($bulan_old != $bulan_input))
		$locked = true;

	if($bulan_input != $bulan_ini)
	{
		if($bulan_ini == 1)
		{
			
			if(($bulan_input == 12))
			{
				if($tgl_ini <= 10)//10
				{
					//ok
				}
				else
				{
					/*if($tgl_input >= 15)
					{
						//ok
					}
					else
					{
						$locked = true;
						
					}*/
					$locked = true;
				}
			}
			else
			{
				$locked = true;
			}
			
		}
		else
		{
			
			if($bulan_input == ($bulan_ini - 1))
			{
				
				if($tgl_ini <= 10)//10
				{
					//ok
				}
				else
				{
					/*if($tgl_input >= 15)
					{
						//ok
					}
					else
					{
						$locked = true;
					}*/
					$locked = true;
				}
				
				
			}
			else
			{
				$locked = true;
			}

		}
	}
	
	
	
	if($bulan_old != $bulan_ini)
	{
		
		if($bulan_ini == 1)
		{
			if($bulan_old != 12)
				$locked = true;
		}
		else
		{
			
			if(($bulan_ini - $bulan_old)>1)
				$locked = true;
		}
	}
	
	
	
	
	
	
	return $locked;
}

function lockedForAdd($tgl = array())
{

	if(count($tgl) < 3)
		return false;

	$bulan_ini = date('n');
	$bulan_input = intval($tgl[1]);
	$tgl_ini = date('j');
	$tgl_input = intval($tgl[0]);
	$tahun_ini = date('Y');
	$tahun_input = intval($tgl[2]);

	$locked = false;
	
	if(($tahun_ini != $tahun_input) && ($bulan_ini == $bulan_input))
		$locked = true;
	
	if(($tahun_ini - $tahun_input) > 1)
		$locked = true;


	if(($tahun_ini != $tahun_input) && ($bulan_ini > 1))
		$locked = true;

	if($bulan_input != $bulan_ini)
	{
		if($bulan_ini == 1)
		{
			
			if(($bulan_input == 12))
			{
				if($tgl_ini <= 10)//10
				{
					//ok
				}
				else
				{
					/*if($tgl_input >= 15)
					{
						//ok
					}
					else
					{
						$locked = true;
						
					}*/
					$locked = true;
				}
			}
			else
			{
				$locked = true;
			}
			
		}
		else
		{
			
			if($bulan_input == ($bulan_ini - 1))
			{
				
				if($tgl_ini <= 10)//10
				{
					//ok
				}
				else
				{
					/*if($tgl_input >= 15)
					{
						//ok
					}
					else
					{
						$locked = true;
					}*/
					$locked = true;
				}
				
				
			}
			else
			{
				$locked = true;
			}

		}
	}
	
	return $locked;
}

?>