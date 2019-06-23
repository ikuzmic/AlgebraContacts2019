<?php
	require "classes/Page.php";
	
	class Prijava extends Page
	{
		protected function GetContent()
		{
			$this->HandleFormData();
			
			$output = '';
			
			$output .= '<form method="POST">';
			$output .= '<table>';
			$output .= '<tr><th>Korisnicko ime:</th><td><input type="text" name="un"/></td></tr>';
			$output .= '<tr><th>Zaporka:</th><td><input type="password" name="pw"/></td></tr>';
			$output .= '<tr><th></th><td><input type="submit" name="btnSub" value="Prijavi se"/></td></tr>';
			$output .= '</table>';
			$output .= '</form>';
			
			return $output;
		}
		
		private function HandleFormData()
		{
			if(!isset($_POST["btnSub"])) return;
			
			$username = $_POST["un"];
			$password = $_POST["pw"];
			
			$this->_authenticator->AuthenticateUser($username, $password);
			
			if($this->_authenticator->UserIsAuthentic())
				$this->BackToLanding();
			else
				echo "Neuspjela prijava!";
		}
		
		protected function PageRequiresAuthenticUser()
		{
			return false;
		}
	}

	$site = new Prijava();
	$site->Display('AlgebraContacts Prijava');