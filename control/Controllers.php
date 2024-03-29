<?php
namespace control;

class Controllers
{

	public function  authenticateAction($userCheck, $dataUsers){

		// Si l'utilisateur n'a pas de session ouverte
		if( !isset($_SESSION['login']) ) {

			// Si la page d'origine est le formulaire de connexion ou de création de compte
			if( isset($_POST['login']) && isset($_POST['password']) )
			{
				if( !$userCheck->authenticate($_POST['login'], $_POST['password'], $dataUsers) )
				{
					// retourne une erreur si le compte n'est pas enregistré
					return 'bad login or pwd';

				}
				// Enregistrement des informations de session après une authentification réussie
				else {
					$_SESSION['login'] = $_POST['login'] ;
				}
			}
			else{
				// retourne une erreur si la personne ne passe pas par le forumlaire de création ou de connexion
				return 'not connected';
			}

		}
	}

	public function annoncesAction( $data, $annoncesCheck)
	{
		$annoncesCheck->getAllAnnonces($data);
	}

	public function postAction($id, $data, $annoncesCheck)
	{
		$annoncesCheck->getPost($id, $data);
	}

	public function annonceCreationAction($id, $info, $data, $annonceCreation)
	{
		$annonceCreation->createAnnonce($id, $info, $data);
	}
}
