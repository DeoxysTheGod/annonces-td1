<?php

namespace data;

include_once "service/AnnonceAccessInterface.php";
use service\AnnonceAccessInterface;

include_once "domain/Post.php";
use domain\Post;

class ApiEmploi implements AnnonceAccessInterface
{
	public function getAllAnnonces(): array
	{
		$token = $this->getToken();

		$api_url = "https://api.pole-emploi.io/partenaire/offresdemploi/v2/offres/search";

		$curlConnection = curl_init();
		$params = array(
			CURLOPT_URL => $api_url."?sort=1&domaine=M18",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer ".$token['access_token']
			)
		);

		curl_setopt_array($curlConnection, $params);
		$response = curl_exec($curlConnection);
		curl_close($curlConnection);

		if (!$response)
			echo curl_error($curlConnection);

		$response = json_decode($response, true);

		$annonces = array();

		foreach ($response['resultats'] as $offre) {
			$id = $offre['id'];
			$title = $offre['intitule'];
			$body = $offre['description'];

			if(isset($offre['salaire']['libelle']))
				$body .= '; ' . $offre['salaire']['libelle'];
			if(isset($offre['entreprise']['nom']))
				$body .= '; ' . $offre['entreprise']['nom'];
			if(isset($offre['contact']['coordonnees']))
				$body .= '; ' . $offre['contact']['cordonnees'];

			$currentPost = new Post($id, $title, $body, date('Y-m-d H:i:s'));
			$annonces[$id] = $currentPost;
		}
		return $annonces;
	}

	public function getPost($id): Post
	{
		$token = $this->getToken() ;

		$api_url = "https://api.pole-emploi.io/partenaire/offresdemploi/v2/offres/";

		$curlConnection  = curl_init();
		$params = array(
			CURLOPT_URL =>  $api_url.$id,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => array("Authorization: Bearer " . $token['access_token'] )
		);
		curl_setopt_array($curlConnection, $params);
		$response = curl_exec($curlConnection);
		curl_close($curlConnection);

		if( !$response )
			echo curl_error($curlConnection);

		$response = json_decode( $response, true );

		// récupération des informations et création du Post
		$id = $response['id'];
		$title = $response['intitule'];
		$body = $response['description'];

		if( isset($response['salaire']['libelle']) )
			$body.='; '.$response['salaire']['libelle'];
		if( isset($response['entreprise']['nom']) )
			$body.='; '.$response['entreprise']['nom'];
		if ( isset($response['contact']['coordonnees1']) )
			$body.='; '.$response['contact']['coordonnees1'];

		return  new Post($id, $title, $body, date("Y-m-d H:i:s") );
	}


	private function getToken(){
		$curl = curl_init();

		$url = "https://entreprise.pole-emploi.fr/connexion/oauth2/access_token";

		$auth_data = array(
			"grant_type" => "client_credentials",
			"client_id" => "PAR_annonces_db4613e1767ce6a81ded7d90e1228717ee5c19edb4e3f65f9680ff5969ecbab7",
			"client_secret" => "9c38be4000d5b9a83a6ef756eba45080e158ba82c4b63be9fa874c62e72cd89f",
			"scope" => "api_offresdemploiv2 o2dsoffre"
		);

		$params = array(
			CURLOPT_URL =>  $url."?realm=%2Fpartenaire",
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => http_build_query($auth_data),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => array(
				"content-type: application/x-www-form-urlencoded"
			)
		);

		curl_setopt_array($curl, $params);

		$response = curl_exec($curl);

		if(!$response)
			die("Connection Failure");

		curl_close($curl);

		return json_decode($response, true);
	}

}