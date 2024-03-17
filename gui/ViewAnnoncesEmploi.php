<?php

namespace gui;

include_once "View.php";

class ViewAnnoncesEmploi extends View
{
	public function __construct($layout, $login, $presenter)
	{
		parent::__construct($layout, $login);

		$this->title = "Liste des annonces d'emploi";
		$this->content = $presenter->getAllEmploiHTML();
	}
}