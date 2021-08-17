<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Galerie;
use App\Entity\Tableau;
use Cocur\Slugify\Slugify;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{   
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {   

        $faker = Factory::create('fr_FR');
        $faker->addProvider(new PicsumPhotosProvider($faker));

        $slugify = new Slugify();

        $adminRole = new Role();    
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);
        $adminUser = new User();

        $adminRole2 = new Role();
        $adminRole2->setTitle('ROLE_EDITEUR');
        $manager->persist($adminRole2);

        $adminUser->setNom('admin')
                  ->setPrenom('admin')
                  ->setPassword($this->encoder->encodePassword($adminUser, 'password'))
                  ->setEmail('admin@symfony.com')
                  ->setInformations('Administrateur générique')
                  ->setRole($adminRole);
        $manager->persist($adminUser);

        $themes = ['Cosmique', 'Nature morte', 'Nu', 'Paysage'];

        $techniques = ['Aquarelle', 'Acrylique', 'Huile', 'Gouache', 'Pastel'];

        for($i = 1;$i <= 10; $i++) {
            $galerie = new Galerie();
            
            $title = $faker->sentence();
            $coverImage = $faker->imageUrl(1000,350,mt_rand(1,1000));
            $description = $faker->paragraph(2);
            $slug = $slugify->slugify($title);
            $createdAt = $faker->dateTimeBetween('-6 months');
            $updatedAt = $createdAt;

            $galerie->setTitle($title)
                    ->setParOrdre('OrdreTableauAsc')
                    ->setSlug($slug)
                    ->setCoverImage($coverImage)
                    ->setDescription($description)
                    ->setReference('svenrybin')
                    ->setTheme($themes[array_rand($themes)])
                    ->setCreateAt($createdAt)
                    ->setUpdatedAt($updatedAt)
                    ->setStatut(true);
            
            for ($j = 1; $j <= mt_rand(100,150); $j++) {
                $image= new Image();
                $tableau= new Tableau();

                $width = mt_rand(200,1000);
                $height = mt_rand(200,700);

                $tableau->setTechnique($techniques[array_rand($techniques)])
                        ->setTitle($faker->word(mt_rand(1,5))) // corriger ici !
                        ->setYear(mt_rand(1940,2000))
                        ->setWidth($width)
                        ->setHeight($height);
                
                $image->setUrl($faker->imageUrl($width, $height, mt_rand(1,1000)))
                      ->setCaption($faker->paragraph(2))
                      ->setOrdre($j)
                      ->setTableau($tableau)
                      ->setGalerie($galerie);
                      
                $manager->persist($image);      
            }
            $manager->persist($galerie);
        }

        $manager->flush();
    }
}
