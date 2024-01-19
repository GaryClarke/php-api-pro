<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;

require dirname(__DIR__) . '/vendor/autoload.php';

$container = new Container();
$container->delegate(new ReflectionContainer(true));

# Settings
$settings = require_once __DIR__ . '/settings.php';
$container->add('settings', new ArrayArgument($settings));

# Services
$container->addShared(EntityManagerInterface::class, function() use ($settings) : EntityManagerInterface {
    $config = ORMSetup::createAttributeMetadataConfiguration(
        paths: $settings['doctrine']['metadata_dirs'],
        isDevMode: $settings['doctrine']['dev_mode']
    );

    $dsn = $settings['doctrine']['connection']['dsn'];
    $dsnParser = new DsnParser();
    $connectionParams = $dsnParser->parse($dsn);

    $connection = DriverManager::getConnection(
        params: $connectionParams,
        config: $config
    );
    return new EntityManager($connection, $config);
});

$container->add(\Symfony\Component\Serializer\SerializerInterface::class, function() {
    $encoders = [
        new \Symfony\Component\Serializer\Encoder\XmlEncoder(),
        new \Symfony\Component\Serializer\Encoder\JsonEncoder()
    ];

    $classMetadataFactory = new \Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory(
        new \Symfony\Component\Serializer\Mapping\Loader\AttributeLoader()
    );

    $metadataAwareNameConverter = new \Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter($classMetadataFactory);

    $normalizers = [
        new \Symfony\Component\Serializer\Normalizer\DateTimeNormalizer(),
        new \Symfony\Component\Serializer\Normalizer\ObjectNormalizer(
            $classMetadataFactory,
            $metadataAwareNameConverter,
        ),
    ];

    return new \Symfony\Component\Serializer\Serializer($normalizers, $encoders);
});

return $container;

