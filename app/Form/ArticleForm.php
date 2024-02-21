<?php

namespace App\Form;

use App\Core\Form;
use App\Models\Article;

class ArticleForm extends Form
{
    
    public function __construct(string $action, ?Article $article = null)
    {
        $this
        ->startForm($action, 'POST', [
            'class' => 'form card p-3 w-75 mx-auto',
            'enctype' => 'multipart/form-data'
        ])
        ->startDiv(['class' => 'mb-3'])
        ->addLabel('titre', 'Titre:',['class' => 'form-label'])
        ->addInput('text', 'titre',[
            'id' => 'titre',
            'class' => 'form-control',
            'required' => true,
            'placeholder' => 'Titre de votre article',
            'value' => $article ? $article->getTitre() : null,
        ])
        ->endDiv()
        ->startDiv(['class' => 'mb-3'])
        ->addLabel('description', 'Description:',['class' => 'form-label'])
        ->addTextarea('description', $article ? $article->getDescription() : null, [
            'id' => 'description',
            'class' => 'form-control',
            'required' => true,
            'placeholder' => 'Titre de votre article',
            'rows' => 10,
        ])
        ->endDiv()
        ->startDiv(['class' => 'mb-3'])
        ->addLabel('image', 'Images:', ['class' => 'form-label'])
        ->addInput('file', 'image', [
            'id' => 'image',
            'class' => 'form-control',
        ])
        ->addImage($article && $article->getImageName() ? "/images/articles/{$article->getimageName()}" : '', [
            'class' => "img-fluid rounded mt-2",
            'loading' => "lazy",
            'alt' => $article ? $article->getTitre() : '',
        ])
        ->endDiv()
        ->startDiv(['class' => 'mb-3'])
        ->addInput('checkbox', 'actif',[
            'id' => 'actif',
            'class' => 'form-input-check',
            'checked' => $article ? $article->getActif() : false,
        ])
        ->addLabel('actif', 'Actif:', ['class' => 'form-label'])
        ->endDiv()
        ->addButton($article ? 'Modifier' : 'Créer', [
            'type' => 'submit',
            'class' => 'btn btn-primary'
        ])
        ->endForm();
    }
}