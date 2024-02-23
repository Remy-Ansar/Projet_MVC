<?php

namespace App\Form;

use App\Core\Form;
use App\Models\Produit;
use App\Models\Categorie;

class ProduitForm extends Form
{
    public function __construct(string $action, ?Produit $produit = null)
    { 
        $this 
            ->startForm($action, 'POST', [
                'class' => 'form card p-3 w-75 mx-auto',
                'enctype' => 'multipart/form-data'
            ])

            ->startDiv(['class' => 'mb-3'])
            ->addLabel('titre', 'Titre:', ['class' => 'form-label'])
            ->addInput('text', 'titre', [
                'id' => 'titre',
                'class' => 'form-control',
                'required' => true,
                'placeholder' => 'Nom de votre produit',
                'value' => $produit ? $produit->getTitre() : null,
            ])
            ->endDiv()

            ->startDiv(['class' => 'mb-3'])
            ->addLabel('description', 'Description:', ['class' => 'form-label'])
            ->addInput('text', 'description', [
                'id' => 'description',
                'class' => 'form-control',
                'required' => true,
                'placeholder' => 'Description de votre produit',
                'row' => 10,
            ])
            ->endDiv()

            ->startDiv(['class' => 'mb-3'])
            ->addLabel('image', 'Image:', ['class' => 'form-label'])
            ->addInput('file', 'image', [
                'id' => 'description',
                'class' => 'form-control',
            ])
            ->addImage($produit && $produit->getImageName() ? "/images/produits/{$produit->getImageName()}" : '', [
                'class' => "img-fluid rounded mt-2",
                'loading' => "lazy",
            ])
            ->endDiv()

            ->startDiv(['class' => 'mb-3'])
            ->addLabel('categorie', 'Catégorie:', ['class' => 'form-label'])
            ->addSelect(
                'categories', (new Categorie)->findAllForSelect($produit),
                ['class' => 'form-control', 'id' => 'categorie']
            )
            ->endDiv()

            ->startDiv(['class' => 'mb-3'])
            ->addLabel('TVA', 'TVA:', ['class' =>'form-label'])
            ->addSelect(
                'TVA',
                [
                    '0.2' => [
                        'label' => 'TVA 20%',
                        'attributs' => [
                            'selected' => $produit && $produit->getTVA() === '0.2' ? true : false,
                        ]
                    ]
                ],
                [
                    'class' => 'form-control',
                    'id' => 'TVA',
                ]
            )
            ->endDiv()

            ->startDiv(['class' => 'mb-3'])
            ->addLabel('prixht', 'Prix HT (€):', ['class' =>'form-label'])
            ->addInput('number', 'prixht', [
                'id' => 'prixht',
                'class' => 'form-label',
                'required' => true,
                'placeholder' => 'Prix de votre produit',
                'value' => $produit ? $produit->getPrixHT() . "€" : null,
                ])
            ->endDiv()

            ->startDiv(['class' => 'mb-3 form-check'])
            ->addInput('checkbox', 'actif', [
                'id' => 'actif',
                'class' => 'form-check-input',
                'checked' => $produit ? $produit->getActif() : false,
            ])
            ->addLabel('actif', 'Actif', ['class' => 'form-check-label'])
            ->endDiv()
            ->addButton($produit ? 'Modifier' : 'Créer', [
                'type' => 'submit',
                'class' => 'btn btn-primary'
            ])
            
            ->endForm();
    }   
}