<?php

namespace App\Models;

use DateTime;

class Produit extends Model
{
    public function __construct(
        protected ?int $id = null,
        protected ?string $titre = null,
        protected ?string $description = null,
        protected ?DateTime $createdAt = null,
        protected ?DateTime $updatedAt = null,
        protected ?bool $actif = null,
        protected ?int $categoriesId = null,
        protected ?string $imageName = null,
        protected ?float $TVA = null,
        protected ?float $prixHT = null,
    ) {
        $this->table = 'produits';
    }

    public function findOneByTitre(string $titre): self|bool
    {
        return $this->fetchHydrate(
            $this->runQuery("SELECT * FROM $this->table WHERE titre = :titre", ['titre' => $titre])->fetch()
        );
    }

    public function findOneActifById(int $id): bool|self
    {
        return $this->fetchHydrate(
            $this->runQuery("SELECT * FROM $this->table WHERE actif = true AND id = :id", ['id' => $id])->fetch()
        );
    }

    public function findLatestByActif(bool $actif = true): array
    {
        return $this->fetchHydrate(
            $this->runQuery("SELECT * FROM $this->table WHERE actif = :actif ORDER BY createdAt DESC", ['actif' => $actif])->fetchAll()
        );
    }

    public function getCategorie(): ?string
    {
        $categorie = $this->runQuery(
            "SELECT c.titre FROM $this->table p JOIN categories c ON p.categoriesId = c.id WHERE p.id = :produitId",
            ['produitId' => $this->id]
        )->fetch();

        return "$categorie->titre";
    }
        

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
         * Get the value of description
         *
         * @return ?string
         */
        public function getDescription(): ?string
        {
                return $this->description;
        }

        /**
         * Set the value of description
         *
         * @param ?string $description
         *
         * @return self
         */
        public function setDescription(?string $description): self
        {
                $this->description = $description;

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
         * Get the value of categoriesId
         *
         * @return ?int
         */
        public function getCategoriesId(): ?int
        {
                return $this->categoriesId;
        }

        /**
         * Set the value of categoriesId
         *
         * @param ?int $categoriesId
         *
         * @return self
         */
        public function setCategoriesId(?int $categoriesId): self
        {
                $this->categoriesId = $categoriesId;

                return $this;
        }

        /**
         * Get the value of imageName
         *
         * @return ?string
         */
        public function getImageName(): ?string
        {
                return $this->imageName;
        }

        /**
         * Set the value of imageName
         *
         * @param ?string $imageName
         *
         * @return self
         */
        public function setImageName(?string $imageName): self
        {
                $this->imageName = $imageName;

                return $this;
        }

        /**
         * Get the value of TVA
         *
         * @return ?float
         */
        public function getTVA(): ?float
        {
                return $this->TVA;
        }

        /**
         * Set the value of TVA
         *
         * @param ?float $TVA
         *
         * @return self
         */
        public function setTVA(?float $TVA): self
        {
                $this->TVA = $TVA;

                return $this;
        }

        /**
         * Get the value of prixHT
         *
         * @return ?float
         */
        public function getPrixHT(): ?float
        {
                return $this->prixHT;
        }

        /**
         * Set the value of prixHT
         *
         * @param ?float $prixHT
         *
         * @return self
         */
        public function setPrixHT(?float $prixHT): self
        {
                $this->prixHT = $prixHT;

                return $this;
        }
}