<?php
	require "classes/Page.php";
	
	class DodajKontakt extends Page
	{
		protected function GetContent()
		{
			$this->HandleFormData();
			
			if(!isset($_GET["id"]) || $this->NotContactOwner($_GET["id"]))
				$this->BackToLanding();
			
			$osobaId = $_GET["id"];
			
			$q = "SELECT name FROM persons WHERE id = $osobaId ;";
			
			foreach($this->_database->query($q) as $row)
			{
				$nazivOsobe = $row["name"];
			}
			
			$output = '';
			
			$output .= "<h1>Dodaj kontakt za osobu <b>$nazivOsobe</b></h1>";
			
			$output .= '<form method="POST">';
			$output .= '<table>';
			$output .= '<tr><th>Tip kontakta:</th><td><input type="text" name="type"/></td></tr>';
			$output .= '<tr><th>Detalji kontakta:</th><td><input type="text" name="value"/></td></tr>';
			$output .= '<tr><td colspan="2"><input type="submit" name="btnSub" value="Dodaj kontakt"/></td></tr>';
			$output .= '</table>';
			$output .= '<input type="hidden" name="personId" value="'.$osobaId.'"/>';
			$output .= '</form>';
			
			return $output;
		}
		
		private function HandleFormData()
		{
			if(!isset($_POST["btnSub"])) return;
			
			$osobaId = $_POST["personId"];
			$type = $_POST["type"];
			$value = $_POST["value"];
			
			$q = "INSERT INTO contacts (personId, contactType, contactData) VALUES (:personId, :type, :value);";
			
			if($stmt = $this->_database->prepare($q))
			{
				$stmt->bindParam(":type", $type, PDO::PARAM_STR, 50);
				$stmt->bindParam(":value", $value, PDO::PARAM_STR, 255);
				$stmt->bindParam(":personId", $osobaId, PDO::PARAM_INT);
				
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

	$site = new DodajKontakt();
	$site->Display('AlgebraContacts dodaj kontakt za osobu');