<?php
class AccueilController extends Controller
{
    public function index(): void
    {
        $epreuvesAVenir = Epreuve::aVenir();

        $this->afficher('accueil/index', [
            'titre'          => 'Accueil',
            'epreuvesAVenir' => $epreuvesAVenir,
        ]);
    }
}
