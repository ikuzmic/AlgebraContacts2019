<?php
	require "classes/Page.php";
	
	class Odjava extends Page
	{
		protected function GetContent()
		{
			session_unset();
			session_destroy();
			$this->BackToLanding();
		}
		
		protected function PageRequiresAuthenticUser()
		{
			return true;
		}
	}

	$site = new Odjava();
	$site->Display('AlgebraContacts Logout');