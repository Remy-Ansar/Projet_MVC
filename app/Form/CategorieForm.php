<?php 

namespace App\Form;

use App\Core\Form;
use App\Models\Categorie;

class CategorieForm extends Form
{
    public function __construct(string $action, ?Categorie $categorie = null)
    {
        $this
            ->startForm($action, 'POST', [
                'class' => 'form card p-3 w-50 mx-auto',
            ])
            ->startDiv(['class' => 'mb-3'])
            ->addLabel('titre', 'Titre:',  ['class' => 'form-label'])
            ->addInput('text', 'titre', [
                'id' => 'titre',
                'class' => 'form-control',
                'required' => true,
                'placeholder' => 'Titre de votre catégorie',
                'value' => $categorie ? $categorie->getTitre() : null,
            ])
            ->endDiv()
            ->startDiv(['class' => 'mb-3 form-check'])
            ->addInput('checkbox', 'actif', [
                'id' => 'actif',
                'class' => 'form-check-input',
                'checked' => $categorie ? $categorie->getActif() : false,
            ])
            ->addLabel('actif', 'Actif', ['class' => 'form-check-label'])
            ->endDiv()
            ->addButton($categorie ? 'Modifier' : 'Créer', [
                'type' => 'submit',
                'class' => 'btn btn-primary'
            ])
            ->endForm();
    }
}