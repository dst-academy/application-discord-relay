<?php

namespace App\Utility;

class Parser
{
    private const PATTERN = '/^\[(?<time>\d{2}\:\d{2}\:\d{2})\]:[ ](?:\[(?<type>.+)\])(?:[ ]\((?<identifier>.+)\))??(?:[ ](?<user>.+):)??[ ](?<message>.*)$/U'; # https://regex101.com/r/Mrz4TO/6

    private static $patterns = [
        'Say' => [
            'identifier' => 'say',
            'expression' => '/^(?<message>.+)$/U',
        ],
        'Whisper'  => [
            'identifier' => 'whisper',
            'expression' => '/^(?<message>.+)$/U',
        ],
        'Join Announcement' => [
            'identifier' => 'join',
            'expression' => '/^(?<user>.+)$/U', # https://regex101.com/r/wurFQB/1
        ],
        'Leave Announcement' => [
            'identifier' => 'leave',
            'expression' => '/^(?<user>.+)$/U', # https://regex101.com/r/wurFQB/1
        ],
        'Roll Announcement' => [
            'identifier' => 'roll',
            'expression' => '/^(?<user>.+)[ ](?<score>\d+)[ ]\((?<minimum>\d+)-(?<maximum>\d+)\)$/U', # https://regex101.com/r/ZVKsMR/3
        ],
        'Skin Announcement' => [
            'identifier' => 'open',
            'expression' => '/^(?<user>.+)[ ](?<item>[\w]+)$/U', # https://regex101.com/r/prXTLq/3
        ],
        'Resurrect Announcement' => [
            'identifier' => 'resurrect',
            'expression' => '/^(?<user>.+) was resurrected by (?<inflictor>.+)\.$/U', # https://regex101.com/r/DkG544/2
        ],
        'Death Announcement' => [
            'identifier' => 'die',
            'expression' => '/^(?<user>.+) was killed by (?<inflictor>.+)\. .+$/U', # https://regex101.com/r/svZNl5/3
        ],
    ];

    public function parse(string $data): array
    {
        $data = $this->match($data, self::PATTERN);

        if ($data['message'] && ($pattern = self::$patterns[$data['type']])) {
            $data['type'] = $pattern['identifier'];
            $data = \array_merge($data, $this->match($data['message'], $pattern['expression']));
        }

        return $data;
    }

    private function match(string $message, string $pattern): array
    {
        \preg_match($pattern, $message, $matches);

        return \array_filter($matches, function ($key) {
            return !\is_numeric($key);
        }, ARRAY_FILTER_USE_KEY);
    }
}
