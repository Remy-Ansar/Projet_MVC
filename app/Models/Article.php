<?php

namespace App\Models;

use DateTime;
use App\Models\Model;


class Article extends Model
{
    public function __construct(
        protected ?int $id = null,
        protected ?string $titre = null,
        protected ?string $description = null,
        protected ?DateTime $createdAt = null,
        protected ?DateTime $updatedAt = null,
        protected ?bool $actif = null,
        protected ?int $userId = null,
    ) {
        $this->table = 'articles';
    }

    public function findOneByTitre(string $titre): self|bool 
    {
        return $this->fetchHydrate(
            $this->runQuery("SELECT * FROM $this->table WHERE titre = :titre", ['titre' =>$titre])->fetch()
        );
    }



    public function getAuthor(): string
    {
        $user = $this->runQuery("SELECT u.nom, u.prenom FROM $this->table a JOIN users u ON a.userId = u.id WHERE a.id = :articleId",
    ['articleId' => $this->id])->fetch();

        return "$user->prenom $user->nom";
}

     /* Get the value of id
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
         * Get the value of userId
         *
         * @return ?int
         */
        public function getUserId(): ?int
        {
                return $this->userId;
        }

        /**
         * Set the value of userId
         *
         * @param ?int $userId
         *
         * @return self
         */
        public function setUserId(?int $userId): self
        {
                $this->userId = $userId;

                return $this;
        }

      
}
