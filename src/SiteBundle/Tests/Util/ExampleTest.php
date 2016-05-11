<?php
// namespace SiteBundle\Tests\Util;

// use Doctrine\ORM\EntityManager;
// use SiteBundle\Entity\Series;

// class SeriesTest extends \PHPUnit_Framework_TestCase
// {
//     public function testAddSeriesException()
//     {
//         $em = $this->getDoctrine()->getManager();
        
//         $seriesA= new Series();
//         $seriesA->setName('bbb');
//         $em->persist($seriesA);
//         $em->flush();

//         $seriesB= new Series();
//         $seriesB->setName('bbb');
//         $em->persist($seriesB);
//         $em->flush();

//         $this->setExpectedException('This series already exists.');

//     }
// }

// class ExceptionTest extends PHPUnit_Framework_TestCase
// {
//     public function testException()
//     {
//         $this->setExpectedException('InvalidArgumentException');
//     }
// }