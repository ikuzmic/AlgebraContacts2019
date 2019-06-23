<?php
	require "classes/Page.php";
	
	class Pregled extends Page
	{
		protected function GetContent()
		{
      // Sprječava da dobijemo prikaz kontakta koji nisu naši
			if(!isset($_GET["id"]) || $this->NotContactOwner($_GET["id"]))
				$this->BackToLanding();
			
			$personId = $_GET["id"];
			
			$personName = $this->GetPersonName($personId);
			$output = '';
			
			$output .= "<h1>Kontakti za osobu <b>$personName</b></h1>";
			$output .= $this->GetContactsTableForPerson($personId);
			
			return $output;
		}
		
		private function GetPersonName($personId)
		{
			$q = "SELECT name FROM persons WHERE id=$personId";
			
			foreach($this->_database->query($q) as $row)
				return $row["name"];
		}
		
		private function GetContactsTableForPerson($personId)
		{
			$output = '';
			
			$output .= '<table border="1">';
			
			$ownerId = $this->_authenticator->GetCurrentUserId();
			
			$q = "SELECT * FROM contacts WHERE personId = $personId";
			$output .= '<tr><th>Tip kontakta</th><th>Kontaktni podaci</th><th>Upravljanje</th></tr>';
			
			foreach($this->_database->query($q) as $row)
			{
				$type = $row["contactType"];
				$data = $row["contactData"];
				$contactId= $row["id"];
				
				$ctrls = '<a href="urediKontakt.php?id='.$contactId.'">Uredi</a>';
				$ctrls .= ' | <a href="obrisiKontakt.php?id='.$contactId.'">Obriši</a>';
				
				$output .= "<tr><td>$type</td><td>$data</td><td>$ctrls</td></tr>";
			}
			
			$output .= '<tr><td colspan="3"><a href="dodajKontakt.php?id='.$personId.'">Dodaj kontakt</a></td></tr>';
			
			$output .= '</table>';
			
			return $output;
		}
		
		private function NotContactOwner($idContact)
		{
			$ownerId = $this->_authenticator->GetCurrentUserId();
			
			$q = "SELECT 1 FROM persons WHERE id = $idContact AND ownerId = $ownerId ;";
			$count = 0;
			
			foreach($this->_database->query($q) as $row)
			{
				$count++;
			}
			
			return $count === 0;
		}
		
		protected function PageRequiresAuthenticUser()
		{
			return true;
		}
	}

	$site = new Pregled();
	$site->Display('AlgebraContacts pregled kontakta');