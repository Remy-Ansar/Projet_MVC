<?php

namespace App\Models;


use App\Models\Model;
use DateTime;

class Categorie extends Model
{
    public function __construct(
        protected ?int $id = null,
        protected ?string $titre = null,
        protected ?bool $actif = null,
        protected ?DateTime $createdAt = null,
        protected ?DateTime $updatedAt = null,
    )
    {
        $this->table = 'categories';
    }


    public function findOneActifById(int $id): bool|self
    {
        return $this->fetchHydrate(
            $this->runQuery("SELECT * FROM $this->table WHERE actif = true AND id = :id", ['id' => $id])->fetch()
        );
    }

    public function findOneByTitre(string $titre): self|bool
    {
        return $this->fetchHydrate(
            $this->runQuery("SELECT * FROM $this->table WHERE titre = :titre", ['titre' => $titre])->fetch()
        );
    }

    public function findOneByCategorie(string $titre): self|bool
    {
        return $this->fetchHydrate(
            $this->runQuery("SELECT * FROM $this->table WHERE titre =:titre", ['titre' =>$titre])->fetch()
        );
    }

    public function findAllForSelect(?Article $article = null): array
    {
        $categories = $this->findAll();

        $choices = [];
        $choices [0] = [
                'label' => 'Sélectionner une catégorie',
                'attributs' => [
                        'selected' => !$article ? true : false,
                        'disabled' => true,
                ]
                ];
        foreach($categories as $categorie) {
                $choices[$categorie->getId()] = [
                        'label' => $categorie->getTitre(),
                        'attributs' => [
                        'selected' => $article && $article->getCategoriesId() === $categorie->getId() ? true : false,
                        ],
                ];
        }
        return $choices;
    }

    public function findLatestByActif(bool $actif = true): array
    {
        return $this->fetchHydrate(
            $this->runQuery("SELECT * FROM $this->table WHERE actif = :actif ORDER BY createdAt DESC", ['actif' => $actif])->fetchAll()
        );
    }

    // public function getCategorie(): string
    // {
    //     $titre = $this->runQuery(
    //         "SELECT c.titre FROM $this->table c JOIN articles a ON a.categoriesId = c.id WHERE a.id = :categoriesId",
    //         ['categoriesId' => $this->id]
    //     )->fetch();

    //     return "$titre->titre";
    // }


    // public function getAuthor(): string
    // {
    //     $user = $this->runQuery(
    //         "SELECT u.nom, u.prenom FROM $this->table a JOIN users u ON a.userId = u.id WHERE a.id = :articleId",
    //         ['articleId' => $this->id]
    //     )->fetch();

    //     return "$user->prenom $user->nom";
    // }
        /**
         * Get the value of id
         *
         * @return ?int
         */
        public function getId(): ?int
        {
                return $this->id;
        }

        /**
         * Set the value of id
         *
         * @param ?int $id
         *
         * @return self
         */
        public function setId(?int $id): self
        {
                $this->id = $id;

                return $this;
        }

        /**
         * Get the value of titre
         *
         * @return ?string
         */
        public function getTitre(): ?string
        {
                return $this->titre;
        }

        /**
         * Set the value of titre
         *
         * @param ?string $titre
         *
         * @return self
         */
        public function setTitre(?string $titre): self
        {
                $this->titre = $titre;

                return $this;
        }

        /**
         * Get the value of actif
         *
         * @return ?bool
         */
        public function getActif(): ?bool
        {
                return $this->actif;
        }

        /**
         * Set the value of actif
         *
         * @param ?bool $actif
         *
         * @return self
         */
        public function setActif(?bool $actif): self
        {
                $this->actif = $actif;

                return $this;
        }

        /**
         * Get the value of createdAt
         *
         * @return ?DateTime
         */
        public function getCreatedAt(): ?DateTime
        {
                return $this->createdAt;
        }

        /**
         * Set the value of createdAt
         *
         * @param ?DateTime $createdAt
         *
         * @return self
         */
        public function setCreatedAt(?DateTime $createdAt): self
        {
                $this->createdAt = $createdAt;

                return $this;
        }

        /**
         * Get the value of updatedAt
         *
         * @return ?DateTime
         */
        public function getUpdatedAt(): ?DateTime
        {
                return $this->updatedAt;
        }

        /**
         * Set the value of updatedAt
         *
         * @param ?DateTime $updatedAt
         *
         * @return self
         */
        public function setUpdatedAt(?DateTime $updatedAt): self
        {
                $this->updatedAt = $updatedAt;

                return $this;
        }
}

