<?php

namespace App\Form;

use App\Core\Form;
use App\Models\User;

class UserForm extends Form
{
    public function __construct(string $action, ?User $user = null)
    {
        $this
            ->startForm($action, 'POST', [
                'class' => 'form card p-3 w-75 mx-auto'
            ])
            ->startDiv(['class' => 'mb-3'])
            ->addLabel('nom', 'Nom:', ['class' => 'form-label'])
            ->addInput('text', 'nom', [
                'class' => 'form-control',
                'id' => 'nom',
                'required' => true,
                'placeholder' => 'Doe',
                'value' => $user ? $user->getNom() : null,
            ])
            ->endDiv()
            ->startDiv(['class' => 'mb-3'])
            ->addLabel('prenom', 'Prénom:', ['class' => 'form-label'])
            ->addInput('text', 'prenom', [
                'class' => 'form-control',
                'id' => 'prenom',
                'required' => true,
                'placeholder' => 'John',
                'value' => $user ? $user->getPrenom() : null,
            ])
            ->endDiv()
            ->startDiv(['class' => 'mb-3'])
            ->addLabel('email', 'Email:', ['class' => 'form-label'])
            ->addInput('email', 'email', [
                'class' => 'form-control',
                'id' => 'email',
                'required' => true,
                'placeholder' => 'john@exemple.com',
                'value' => $user ? $user->getEmail() : null,
            ])
            ->endDiv()
            ->startDiv(['class' => 'mb-3'])
            ->addLabel('password', 'Mot de passe:', ['class' => 'form-label'])
            ->addInput('password', 'password', [
                'class' => 'form-control',
                'id' => 'password',
                'required' => $user ? false : true,
                'placeholder' => 'S3CR3T',
            ])
            ->endDiv();

        if ($user) {
            $this
                ->startDiv(['class' => 'mb-3'])
                ->addLabel('roles[]', 'Roles:', ['class' => 'form-label'])
                ->addSelect(
                    'roles[]',
                    [
                        'ROLE_USER' => [
                            'label' => 'Utilisateur',
                            'attributs' => [
                                'selected' => true,
                            ]
                        ],
                        'ROLE_EDITOR' => [
                            'label' => 'Éditeur',
                            'attributs' => [
                                'selected' => in_array('ROLE_EDITOR', $user->getRoles()) ? true : false,
                            ]
                        ],
                        'ROLE_ADMIN' => [
                            'label' => 'Administrateur',
                            'attributs' => [
                                'selected' => in_array('ROLE_ADMIN', $user->getRoles()) ? true : false,
                            ]
                        ]
                    ],
                    [
                        'class' => 'form-control',
                        'id' => 'roles[]',
                        'multiple' => true,
                    ]
                )
                ->endDiv();
        }

        $this
            ->addButton($user ? 'Modifier' : 'S\'inscrire', [
                'class' => 'btn btn-primary',
                'type' => 'submit'
            ])
            ->endForm();
    }
}
