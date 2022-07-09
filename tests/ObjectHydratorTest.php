<?php

declare(strict_types=1);

namespace Tests;

use Blog\Entity\Post;
use Blog\Hydration\ObjectHydrator;
use Blog\Kernel;
use Cocur\Slugify\Slugify;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class ObjectHydratorTest extends TestCase
{
    private ObjectHydrator $hydrator;

    public function setUp(): void
    {
        $kernel = new Kernel('test');
        $container = $kernel->getContainer();

        $this->hydrator = $container->get(ObjectHydrator::class);
    }

    /**
     * @throws ReflectionException
     */
    public function testHydrateSingle()
    {
        $faker = Factory::create();

        $postData = [
            'title' => $faker->sentence,
            'content' => $faker->realText
        ];

        $post = new Post();
        $post
            ->setTitle($postData['title'])
            ->setContent($postData['content']);

        $hydratedObj = $this->hydrator->hydrateSingle($postData, new Post());

        $this->assertEquals($post->getTitle(), $hydratedObj->getTitle());
        $this->assertEquals($post->getContent(), $hydratedObj->getContent());

        $slugify = Slugify::create();

        $this->assertNotFalse($hydratedObj->getSlug());
        $this->assertEquals($hydratedObj->getSlug(), $slugify->slugify($postData['title']));
        $this->assertEquals(\DateTime::class, get_class($hydratedObj->getCreatedAt()));
        $this->assertNull($hydratedObj->getUpdatedAt());
    }

    public function testExtract()
    {
        $faker = Factory::create();

        $post = new Post();
        $post
            ->setTitle($faker->sentence)
            ->setContent($faker->realText);

        $postArray = $this->hydrator->extract($post);
        $this->assertArrayHasKey('title', $postArray);
        $this->assertArrayHasKey('filename', $postArray);
        $this->assertArrayHasKey('file', $postArray);
        $this->assertArrayHasKey('content', $postArray);
        $this->assertArrayHasKey('excerpt', $postArray);
        $this->assertArrayHasKey('updatedAt', $postArray);
        $this->assertArrayNotHasKey('slug', $postArray);
        $this->assertArrayNotHasKey('createdAt', $postArray);

        $post2 = new Post();
        $post2
            ->setTitle($faker->sentence)
            ->setContent($faker->realText);

        $this->assertCount(2, $this->hydrator->extract([$post, $post2]));
    }
}