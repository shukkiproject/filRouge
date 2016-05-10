<?php
namespace SiteBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use SiteBundle\Entity\User;
use Symfony\Component\HttpFoundation\File\File;

class LoadSiteData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Creates a user and returns it.
     *
     * @param string  $username
     * @param string  $firstname
     * @param string  $lastname
     * @param string  $birthday
     * @param string  $password
     * @param string  $email
     * @param Boolean $active
     *
     * @return \SiteBundle\Entity\User
     */
    public function createUser($username, $firstname, $lastname, $birthday, $password, $email, $active, $imagefile)
    {
        $dir='/home/imie/public_html/filRouge/web/images/users/';
        $user = new User();
        $user->setUsername($username);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $date = new \DateTime($birthday);
        $user->setBirthday($date);
        $user->setPlainPassword($password);
        $user->setEmail($email);
        $user->setEnabled((Boolean) $active);
        $file = new File($dir.$imagefile);
        $user->setImageFile($file);
        $user->setImageName($imagefile);
        return $user;
    }

    public function createSeries($name, $creator, $year, $synopsis, $language, $validated)
    {
        
        $user = new User();
        $user->setUsername($username);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $date = new \DateTime($birthday);
        $user->setBirthday($date);
        $user->setPlainPassword($password);
        $user->setEmail($email);
        $user->setEnabled((Boolean) $active);

        return $user;
    }

    public function load(ObjectManager $manager)
    {
        // Create super admins
        $xavierSupAdmin=$this->createUser('xavier', 'Xavier', 'Boule','01-01-2000', 123, 'xavier@a.fr', true, '57235d72c21fe.jpg');
        $xavierSupAdmin->setRoles(array('ROLE_MODERATOR', 'ROLE_SUPER_ADMIN'));
        $manager->persist($xavierSupAdmin);

        $matthieuSupAdmin=$this->createUser('matthieu', 'Matthieu', 'Le Naour', '01-01-2000', 123, 'matthieu@a.fr', true, '5729a1a7e85c6.jpg');
        $matthieuSupAdmin->setRoles(array('ROLE_MODERATOR', 'ROLE_SUPER_ADMIN'));
        $manager->persist($matthieuSupAdmin);

        $shukkiSupAdmin=$this->createUser('shukki', 'Shuk Ki', 'Hertz', '01-01-2000', 123, 'shukki@a.fr', true, '57287bd6616bd.jpg');
        $shukkiSupAdmin->setRoles(array('ROLE_MODERATOR', 'ROLE_SUPER_ADMIN'));
        $manager->persist($shukkiSupAdmin);

        //create a moderator
        $moderator=$this->createUser('moderator', 'mod', 'mod', '01-01-2000', 123, 'mod@a.fr', true, 'mod.jpg');
        $moderator->setRoles(array('ROLE_MODERATOR'));
        $manager->persist($moderator);

        //create a user
        $user=$this->createUser('user', 'user', 'user', '01-01-2000', 123, 'user@a.fr', true, 'Xavier_x3.jpg');
        $manager->persist($user);

        //create a series
        // $series= new Series();

        $manager->flush();
    }
}