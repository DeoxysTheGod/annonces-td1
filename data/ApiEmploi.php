<?php

namespace data;

class ApiEmploi implements AnnonceAccessInterface
{
	function getToken(){
		$curl = curl_init();

		$url = "https://entreprise.pole-emploi.fr/connexion/oauth2/access_token";

		$auth_data = array(
			"grant_type" => "client_credentials",
			"client_id" => CLIENT_ID,
			"client_secret" => CLIENT_SECRET,
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