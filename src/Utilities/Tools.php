<?php

namespace App\Utilities;

class Tools
{

    public function majEtatSortieFormulaire($dateInscription):int{

        $etat=null;

        if ($dateInscription <= ( new \DateTime())){
            $etat = 4;
        }elseif ($dateInscription > ( new \DateTime())){
            $etat = 2;
        }

     return $etat;
    }

    public function majNomLieuId($nomLieu):int{
       $idLieu=null;



       return $idLieu;
    }
}