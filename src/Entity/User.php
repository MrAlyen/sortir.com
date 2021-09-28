<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $telephone;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="User")
     */
    private $campus;

    /**
     * @ORM\ManyToMany(targetEntity=Sortie::class, mappedBy="estInscrit")
     */
    private $estInscrit;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="organisateur", orphanRemoval=true)
     */
    private $organise_sorties;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active;

    public function __construct()
    {
        $this->estInscrit = new ArrayCollection();
        $this->organise_sorties = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getEstInscrit(): Collection
    {
        return $this->estInscrit;
    }

    public function addEstInscrit(Sortie $estInscrit): self
    {
        if (!$this->estInscrit->contains($estInscrit)) {
            $this->estInscrit[] = $estInscrit;
            $estInscrit->addEstInscrit($this);
        }

        return $this;
    }

    public function removeEstInscrit(Sortie $estInscrit): self
    {
        if ($this->estInscrit->removeElement($estInscrit)) {
            $estInscrit->removeEstInscrit($this);
        }

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getOrganiseSorties(): Collection
    {
        return $this->organise_sorties;
    }

    public function addOrganiseSorty(Sortie $organiseSorty): self
    {
        if (!$this->organise_sorties->contains($organiseSorty)) {
            $this->organise_sorties[] = $organiseSorty;
            $organiseSorty->setOrganisateur($this);
        }

        return $this;
    }

    public function removeOrganiseSorty(Sortie $organiseSorty): self
    {
        if ($this->organise_sorties->removeElement($organiseSorty)) {
            // set the owning side to null (unless already changed)
            if ($organiseSorty->getOrganisateur() === $this) {
                $organiseSorty->setOrganisateur(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->pseudo;
    }
   

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    } 


    
}
