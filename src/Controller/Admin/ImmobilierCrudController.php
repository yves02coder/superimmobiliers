<?php

namespace App\Controller\Admin;

use App\Entity\Immobilier;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ImmobilierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Immobilier::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),

            ImageField::new('imageName')->setBasePath('/asset ~/image.imageName')->setUploadDir('public\uploads'),
            AssociationField::new('category'),
            NumberField::new('price'),
            NumberField::new('surface'),
            NumberField::new('rooms'),
            NumberField::new('bedrooms'),


        ];
    }

}
