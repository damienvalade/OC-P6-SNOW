<?php


namespace App\Form;

use App\Entity\Users;
use http\Client\Curl\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\IsTrue;

class UpdateRoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {

        $roles = [
            "Utilisateur" => Users::ROLE_USER,
            "Editeur" => Users::ROLE_EDITOR,
            "Modérateur" => Users::ROLE_MODERATOR,
            "Manager" => Users::ROLE_MANAGER,
            "Administrateur" => Users::ROLE_ADMIN
        ];

        for($i = 0, $iMax = count($options['data']); $i < $iMax; $i++) {
            $isRoles = "isRoles$i";
            $builder->add($isRoles, ChoiceType::class, [
                'choices' => $roles
            ]);
        }
    }
}