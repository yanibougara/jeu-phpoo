

<?php
class Jeu {
    private $Difficulté;
    private $joueurs;
    private $niveauChoisi;
    private $personnage;
    private $billesActuelles;
    private $seuilVictoire;
    private $partieGagnee;
// fonction commencer la partie
    public function commencerPartie($difficultes, $adversaires) {
        echo "Bienvenue ! Nous allons commencer <br>";

        $this->Difficulté = $difficultes;
        $this->joueurs = $adversaires;
        $this->niveauChoisi = $this->Difficulté[random_int(0, count($this->Difficulté) - 1)];
        echo "Niveau de difficulté : $this->niveauChoisi <br>";
//tous les personnages disponibles
        $personnagesDisponibles = [
            new Heros("Yani", random_int(10, 30), random_int(1, 5), random_int(2, 4)),
            new Heros("Louca", random_int(10, 30), random_int(1, 5), random_int(2, 4)),
            new Heros("Yanis", random_int(10, 30), random_int(1, 5), random_int(2, 4)),
        ];
//choix aléatoire d'un perso
        $this->personnage = $personnagesDisponibles[random_int(0, count($personnagesDisponibles) - 1)];
        echo "Personnage choisi : " . $this->personnage->getNom() . " <br>";

        $this->billesActuelles = $this->personnage->getBilles();
        $this->seuilVictoire = 1;

        echo "Lancement de la partie... <br>";
        $this->jouer();
    }
//fonction jouer
    public function jouer() {
        $adversairesRestants = count($this->joueurs);

        while ($this->billesActuelles >= $this->seuilVictoire && $adversairesRestants > 0) {
            $adversaire = $this->rencontrerAdversaire();
            if ($adversaire === null) {
                break;
            }
//choix adversaires
            $choixJoueur = $this->choixAleatoire();
            $this->gererResultatRencontre($choixJoueur, $adversaire);

            $adversairesRestants--;
        }

        if ($this->billesActuelles >= $this->seuilVictoire) {
            $this->partieGagnee = true;
            $this->afficherMessageFinDePartie(true);
        } else {
            $this->partieGagnee = false;
            $this->afficherMessageFinDePartie(false);
        }
    }

    public function afficherMessageFinDePartie($estVictoire) {
        if ($estVictoire) {
            echo "Wow ! Vous avez gagné ! <br>";
        } else {
            echo "Mince... C'est perdu... <br>";
        }
    }

    public function choixAleatoire() {
        return random_int(0, 1);
    }

    private function rencontrerAdversaire() {
        if (empty($this->joueurs)) {
            echo "Il n'y a plus d'adversaires. Vous avez gagné  ! <br>";
            return null;
        }

        $adversaireIndex = random_int(0, count($this->joueurs) - 1);

      
        if (!isset($this->joueurs[$adversaireIndex])) {
            return null;
        }

        $adversaire = $this->joueurs[$adversaireIndex];
        echo "Adversaire choisi : " . $adversaire->getNom() . " <br>";
        echo "Vous avez " . $this->billesActuelles . " billes. <br>";
        return $adversaire;
    }

    private function devinerPairOuImpair($adversaire) {
        $choixJoueur = $this->choixAleatoire();

        if ($choixJoueur === 0) {
            echo "Vous pariez sur pair. <br>";
        } else {
            echo "Vous pariez sur impair. <br>";
        }

        $billesAdversaire = $adversaire->getBilles();
        $estPair = $billesAdversaire % 2 === 0;

        // Résultat de la rencontre
        if (($estPair && $choixJoueur === 0) || (!$estPair && $choixJoueur === 1)) {
            echo "Vous avez un bonus <br>";
            return true;
        } else {
            if ($estPair) {
                echo "Mauvaise réponse, le nombre était paire <br>";
            } else {
                echo "Mauvaise réponse, le nombre était impair <br>";
            }
            return false;
        }
    }

    private function gererResultatRencontre($choixJoueur, $adversaire) {
        $devinetteCorrecte = $this->devinerPairOuImpair($adversaire);

        $bonusJoueur = $this->personnage->getBonus();
        $malusJoueur = $this->personnage->getMalus();
        $billesAdversaire = $adversaire->getBilles();

        if ($devinetteCorrecte) {
            $this->billesActuelles += $billesAdversaire + $bonusJoueur;
            echo "Vous gagnez " . ($billesAdversaire + $bonusJoueur) . " billes ! <br>";
        } else {
            $this->billesActuelles -= $billesAdversaire - $malusJoueur;
            echo "Vous perdez " . ($billesAdversaire - $malusJoueur) . " billes... <br>";
        }

        echo "Il vous reste " . $this->billesActuelles . " billes. <br>";

        $this->eliminerAdversaire($adversaire);
    }

    private function eliminerAdversaire($adversaire) {
        foreach ($this->joueurs as $key => $joueur) {
            if ($joueur === $adversaire) {
                unset($this->joueurs[$key]);
                break;
            }
        }
    }
}

class Heros extends Personnage {
    public function __construct($nom, $billes, $malus, $bonus) {
        parent::__construct($nom, $billes, $malus, $bonus);
    }
}
class Ennemi extends Personnage {
    public function __construct($nom, $billes, $malus, $bonus) {
        parent::__construct($nom, $billes, $malus, $bonus);
    }
}
class Personnage {
    private string $nom;
    private int $billes;
    private int $malus;
    private int $bonus;

    public function __construct($nom, $billes, $malus, $bonus) {
        $this->nom = $nom;
        $this->billes = $billes;
        $this->malus = $malus;
        $this->bonus = $bonus;
    }

    public function getNom() {
        return $this->nom;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function getBilles() {
        return $this->billes;
    }

    public function setBilles($billes) {
        $this->billes = $billes;
    }

    public function getMalus() {
        return $this->malus;
    }

    public function setMalus($malus) {
        $this->malus = $malus;
    }

    public function getBonus() {
        return $this->bonus;
    }

    public function setBonus($bonus) {
        $this->bonus = $bonus;
    }
}
$adversaires = [
    new Ennemi("Adversaire1", random_int(1, 20), random_int(0, 5), random_int(0, 5)),
    new Ennemi("Adversaire2", random_int(1, 20), random_int(0, 5), random_int(0, 5)),
    new Ennemi("Adversaire3", random_int(1, 20), random_int(0, 5), random_int(0, 5)),
    new Ennemi("Adversaire4", random_int(1, 20), random_int(0, 5), random_int(0, 5)),
    new Ennemi("Adversaire5", random_int(1, 20), random_int(0, 5), random_int(0, 5)),
];
$jeu = new Jeu();
$jeu->commencerPartie([5, 10, 20], $adversaires);
?>

