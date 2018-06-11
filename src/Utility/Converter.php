<?php

namespace App\Utility;

use App\Entity\Event\DieEvent;
use App\Entity\Event\JoinEvent;
use App\Entity\Event\OpenEvent;
use App\Entity\Event\ResurrectEvent;
use App\Entity\Event\RollEvent;
use App\Entity\Event\SayEvent;
use App\Entity\Event\WhisperEvent;
use App\Entity\EventInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class Converter
{
    private $parser;
    private $map = [
        'join' => JoinEvent::class,
        'leave' => JoinEvent::class,
        'resurrect' => ResurrectEvent::class,
        'die' => DieEvent::class,
        'say' => SayEvent::class,
        'whisper' => WhisperEvent::class,
        'roll' => RollEvent::class,
        'open' => OpenEvent::class,
    ];

    private $normalizer;

    public function __construct(Parser $parser, ObjectNormalizer $normalizer)
    {
        $this->parser = $parser;
        $this->normalizer = $normalizer;
    }

    public function convert(string $data): EventInterface
    {
        $data = $this->parser->parse($data);
        $class = $this->map[$data['type']];

        return $this->normalizer->denormalize($data, $class);
    }
}
