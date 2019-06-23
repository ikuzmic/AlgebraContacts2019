<?php
	require "classes/Page.php";
	
	class UrediKontakt extends Page
	{
		protected function GetContent()
		{
			$this->HandleFormData();
			
			if(!isset($_GET["id"]) || $this->NotContactOwner($_GET["id"]))
				$this->BackToLanding();
			
			$contactId = $_GET["id"];
			
			$q = "SELECT * FROM contacts WHERE id = $contactId ;";
			
			foreach($this->_database->query($q) as $row)
			{
				$type = $row["contactType"];
				$data = $row["contactData"];
			}
			
			$nazivOsobe = $this->GetPersonNameForContact($contactId);
			
			$output = '';
			
			$output .= "<h1>Uredi kontakt za osobu <b>$nazivOsobe</b></h1>";
			
			$output .= '<form method="POST">';
			$output .= '<table>';
			$output .= '<tr><th>Tip kontakta:</th><td><input type="text" name="type" value="'.$type.'"/></td></tr>';
			$output .= '<tr><th>Detalji kontakta:</th><td><input type="text" name="value" value="'.$data.'"/></td></tr>';
			$output .= '<tr><td colspan="2"><input type="submit" name="btnSub" value="Uredi kontakt"/></td></tr>';
			$output .= '</table>';
			$output .= '<input type="hidden" name="contactId" value="'.$contactId.'"/>';
			$output .= '</form>';
			
			return $output;
		}
		
		private function GetPersonNameForContact($contactId)
		{
			$q = "SELECT p.name AS name FROM persons p JOIN contacts c ON c.personId = p.id WHERE c.id = $contactId;";
			
			foreach($this->_database->query($q) as $row)
				return $row["name"];
		}
		
		private function NotContactOwner($contactId)
		{
			$ownerId = $this->_authenticator->GetCurrentUserId();
			
			$q = "SELECT 1 FROM persons p JOIN contacts c on c.personId = p.id WHERE c.id = $contactId AND p.ownerId = $ownerId ;";
			$count = 0;
			
			foreach($this->_database->query($q) as $row)
			{
				$count++;
			}
			
			return $count === 0;
		}
		
		private function HandleFormData()
		{
			if(!isset($_POST["btnSub"])) return;
			
			$newType = $_POST["type"];
			$newData = $_POST["value"];
			$id = $_POST["contactId"];
			
			
			$q = "UPDATE contacts SET contactType = :type, contactData = :data WHERE id = :id;";
			
			if($stmt = $this->_database->prepare($q))
			{
				$stmt->bindParam(":type", $newType, PDO::PARAM_STR, 50);
				$stmt->bindParam(":data", $newData, PDO::PARAM_STR, 255);
				$stmt->bindParam(":id", $id, PDO::PARAM_INT);
				
				if($stmt->execute())
				{
					$this->BackToLanding();
				}
				else
				{
					echo "Pogreška u izvršavanju upita!";
				}
			}
			else
			{
				echo "Pogreška u pripremi upita!";
			}
		}
		
		protected function PageRequiresAuthenticUser()
		{
			return true;
		}
	}

	$site = new UrediKontakt();
	$site->Display('AlgebraContacts uredi kontakt');