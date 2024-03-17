<?php

namespace service;

class AnnonceCreation
{

	public function createAnnonce($login, $info, $data)
	{
		$data->createAnnonce($login, $info);
	}
}