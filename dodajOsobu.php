<?php
	require "classes/Page.php";
	
	class DodajOsobu extends Page
	{
		protected function GetContent()
		{
		
			$this->HandleFormData();
			$output = '';
			
			$output .= '<h2>Dodaj novu osobu</h2>';
			$output .= '<form method="POST">';
			$output .= 'Naziv kontakta:<input type="text" name="name"/>';
			$output .= '<input type="submit" name="btnSub" value="Dodaj"/>';
			$output .= '';
			
			return $output;
		}
		
		private function HandleFormData()
		{
			if(!isset($_POST["btnSub"])) return;
			
			$name = $_POST["name"];
			$ownerId = $this->_authenticator->GetCurrentUserId();
    /* @var $_SESSION int */
   // $ownerId=$_SESSION["userId"];  //Može i ovako ali gore je ispravnije
			
			$q = "INSERT INTO persons (name, ownerId) VALUES (:name, :ownerId)";
			
			if($stmt = $this->_database->prepare($q))
			{
				$stmt->bindParam(":name", $name, PDO::PARAM_STR, 50);
				$stmt->bindParam(':ownerId', $ownerId, PDO::PARAM_INT);
				
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

	$site = new DodajOsobu();
	$site->Display('AlgebraBox dodaj osobu');