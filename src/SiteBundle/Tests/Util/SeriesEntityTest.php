<?php

use SiteBundle\Entity\Series;

class SeriesEntityTest extends \PHPUnit_Framework_TestCase
{
    public function testAddSeries()
    {
        // First, mock the object to be used in the test
        $series = $this->getMock('\SiteBundle\Entity\Series');
        $series->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('Breaking Bad'));


        // Now, mock the repository so it returns the mock of the series
        $seriesRepository = $this
            ->getMockBuilder('\Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $seriesRepository->expects($this->once())
            ->method('find')
            ->will($this->returnValue($series));

        // Last, mock the EntityManager to return the mock of the repository
        $entityManager = $this
            ->getMockBuilder('\Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($seriesRepository));

        $series = $entityManager->getRepository('SiteBundle:Series')->find(1);
        $this->assertEquals('Breaking Bad', $series->getName());
    }

    public function testAddSeriesException()
    {
        $em = $this->getDoctrine()->getManager();
        
        $seriesA= new Series();
        $seriesA->setName('bbb');
        $em->persist($seriesA);
        $em->flush();

        $seriesB= new Series();
        $seriesB->setName('bbb');
        $em->persist($seriesB);
        $em->flush();

        $this->setExpectedException('This series already exists.');

    }


}