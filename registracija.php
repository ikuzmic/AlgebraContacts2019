<?php
	require "classes/Page.php";
	
	class Registracija extends Page
	{
		protected function GetContent()
		{
			$this->HandleFormData();
			
			$output = '';
			
			$output .= '<form method="POST">';
			$output .= '<table>';
			$output .= '<tr><th>Korisnicko ime:</th><td><input type="text" name="un"/></td></tr>';
			$output .= '<tr><th>Zaporka:</th><td><input type="password" name="p1"/></td></tr>';
			$output .= '<tr><th>Ponovljena zaporka:</th><td><input type="password" name="p2"/></td></tr>';
			$output .= '<tr><th></th><td><input type="submit" name="btnSub" value="Registriraj se"/></td></tr>';
			$output .= '</table>';
			$output .= '</form>';
			
			return $output;
		}
		
		private function HandleFormData()
		{
			if(!isset($_POST["btnSub"])) return;
			
			if($_POST["p1"] !== $_POST["p2"])
			{
				echo "Zaporke se moraju poklapati!";
				return;
			}
			
			$username = $_POST["un"];
			$password = $_POST["p1"];
			$this->_authenticator->CreateUser($username, $password);
			
			$this->_authenticator->AuthenticateUser($username, $password);
			
			if($this->_authenticator->UserIsAuthentic())
				$this->BackToLanding();
		}
		
		protected function PageRequiresAuthenticUser()
		{
			return false;
		}
	}

	$site = new Registracija();
	$site->Display('AlgebraContacts Registracija');