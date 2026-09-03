<?php
class ClassementController extends Controller
{
    public function index(): void
    {
        $classement = Participation::classementGeneral();

        $this->afficher('classement/index', [
            'titre'      => 'Classement général',
            'classement' => $classement,
        ]);
    }
}
