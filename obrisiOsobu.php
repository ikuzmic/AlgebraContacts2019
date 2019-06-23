<?php
	require "classes/Page.php";
	
	class ObrisiOsobu extends Page
	{
		protected function GetContent()
		{
			$this->HandleFormData();
			
			if(!isset($_GET["id"]) || $this->NotContactOwner($_GET["id"]))
				$this->BackToLanding();
			
			$id = $_GET["id"];
			$name = $this->GetPersonName($id);
			$output = '';
			
			$output .= '<h1>Jeste li sigurni da želite izbrisati osobu '.$name.' i sve vezane kontakte?</h1>';
			$output .= '<form method="POST">';
			$output .= '<input type="submit" name="btnSub" value="Da"/>';
			$output .= '<input type="hidden" name="id" value="'.$id.'"/>';
			$output .= '</form>';
			$output .= '<a href="moje.php">Povratak</a>';
			return $output;
		}
		
		private function GetPersonName($id)
		{
			$q = "SELECT name FROM persons WHERE id = $id";
			
			foreach($this->_database->query($q) as $row)
			{
				return $row["name"];
			}
		}
		
		private function HandleFormData()
		{
			if(!isset($_POST["btnSub"])) return;
			
			$personId = $_POST["id"];
			
			$this->_database->beginTransaction();
			
			if(!$this->DeleteAllContactsForPerson($personId))
			{
				echo "Pogreška pri brisanju kontakata!";
				$this->_database->rollBack();
				return;
			}
			
			$q = "DELETE FROM persons WHERE id = $personId";
			
			
			if($this->_database->exec($q) !== 1)
			{
				echo "Pogreška pri brisanju osobe!";
				$this->_database->rollBack();
				return;
			}
			
			$this->_database->commit();
			
			$this->BackToLanding();
		}
		
		private function DeleteAllContactsForPerson($personId)
		{
			$q = "DELETE FROM contacts WHERE personId = $personId";
			
			try
			{
				$this->_database->exec($q);
				return true;	
			}
			catch(Exception $e)
			{
				return false;
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

	$site = new ObrisiOsobu();
	$site->Display('AlgebraContacts Obriši osobu');