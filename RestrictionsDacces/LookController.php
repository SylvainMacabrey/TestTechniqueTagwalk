<?php 

date_default_timezone_set('Europe/Paris');

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route; 

/**
 * @IsGranted("ROLE_EMPLOYE", message="Vous n'avez pas accès à cette ressource.")
 */
class LookController 
{ 

    /** 
     * @Route("/{slug}/edit", methods={"GET", "POST"}, name="look_edit") 
     */ 
    public function edit(Request $request, string $slug): Response 
    { 
        //$this->denyAccessUnlessGranted('ROLE_EMPLOYE');

        // SOLUTION 1: vérification dans le controleur

        // on vérifie si aujourd'hui n'est ni samedi ni dimanche, et que l'heure actuelle est comprise entre 9h et 18h
        if (date('w') == 6 || date('w') == 0 || date('H:i:s') > "18:00:00" || date('H:i:s') < "09:00:00") {
            throw $this->createAccessDeniedException('Il faut vous reposez!');
        }
        // on vérifie si la date d'aujourd'hui n'est pas après la date d'expiration
        if(new \DateTime('now') > $this->getUser()->getExpirationDate()) {
            throw $this->createAccessDeniedException('Vous ne travaillez plus ici!');
        }


        // SOLUTION 2: vérification avec les Voters (voir LookVoter.php)
        $this->denyAccessUnlessGranted('look', $look);
        $this->denyAccessUnlessGranted('edit', $look);

        // reste du code
    } 

}

