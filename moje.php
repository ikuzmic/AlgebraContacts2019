<?php
	require "classes/Page.php";
	
	class Moje extends Page
	{
		protected function GetContent()
		{
			$output = '';
			
			$output .= $this->GetContactListTable();
			$output .= '';
			$output .= '';
			$output .= '';
			$output .= '';
			
			return $output;
		}
		
		private function GetContactListTable()
		{
			$output = '';
			
			$output .= '<table border="1">';
			
			$ownerId = $this->_authenticator->GetCurrentUserId();
			
			$q = "SELECT * FROM persons WHERE ownerId = $ownerId";
			$output .= '<tr><th>Naziv</th><th>Upravljanje</th></tr>';
			
			foreach($this->_database->query($q) as $row)
			{
				$name = $row["name"];
				$id = $row["id"];
				$owner = $this->_authenticator->GetCurrentUserName();
				$fileLoc = "/AlgebraBox/files/$owner/$name";
				
				$ctrls = '<a href="urediOsobu.php?id='.$id.'">Preimenuj</a>';
				$ctrls .= ' | <a href="pregled.php?id='.$id.'">Pogledaj kontakte</a>';
				$ctrls .= ' | <a href="obrisiOsobu.php?id='.$id.'">Obriši</a>';
				$ctrls .= ' | <a href="dodajKontakt.php?id='.$id.'">Dodaj kontakt</a>';
				
				$output .= "<tr><td>$name</td><td>$ctrls</td></tr>";
			}
			
			$output .= '<tr><td colspan="2"><a href="dodajOsobu.php">Dodaj osobu</a></td></tr>';
			
			$output .= '</table>';
			
			return $output;
		}
		
		protected function PageRequiresAuthenticUser()
		{
			return true;
		}
	}

	$site = new Moje();
	$site->Display('AlgebraContacts moji kontakti');