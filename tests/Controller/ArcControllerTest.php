<?php

namespace App\Tests\Controller;

use App\Entity\Arc;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ArcControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $arcRepository;
    private string $path = '/arc/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->arcRepository = $this->manager->getRepository(Arc::class);

        foreach ($this->arcRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Arc index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'arc[type]' => 'Testing',
            'arc[geoloc]' => 'Testing',
            'arc[userId]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->arcRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Arc();
        $fixture->setType('My Title');
        $fixture->setGeoloc('My Title');
        $fixture->setUserId('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Arc');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Arc();
        $fixture->setType('Value');
        $fixture->setGeoloc('Value');
        $fixture->setUserId('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'arc[type]' => 'Something New',
            'arc[geoloc]' => 'Something New',
            'arc[userId]' => 'Something New',
        ]);

        self::assertResponseRedirects('/arc/');

        $fixture = $this->arcRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getType());
        self::assertSame('Something New', $fixture[0]->getGeoloc());
        self::assertSame('Something New', $fixture[0]->getUserId());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Arc();
        $fixture->setType('Value');
        $fixture->setGeoloc('Value');
        $fixture->setUserId('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/arc/');
        self::assertSame(0, $this->arcRepository->count([]));
    }
}
