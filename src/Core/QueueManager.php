<?php

namespace App\Core;

class QueueManager
{
    private \Redis $redis;

    public function __construct()
    {
        $this->redis = new \Redis();
        $this->redis->connect(getenv('REDIS_HOST') ?: 'redis', (int)(getenv('REDIS_PORT') ?: 6379));
        $this->redis->auth(getenv('REDIS_PASS') ?: null);
    }

    public function push(string $queue, string $jobClass, array $payload): string
    {
        $jobId = bin2hex(random_bytes(16));
        $jobData = json_encode([
            'id' => $jobId,
            'job' => $jobClass,
            'payload' => $payload,
            'attempts' => 0,
            'queued_at' => microtime(true)
        ], JSON_THROW_ON_ERROR);

        // Guardar estado inicial para polling o websockets si el cliente consulta progreso
        $this->redis->hSet("job:status:$jobId", 'status', 'queued');
        $this->redis->expire("job:status:$jobId", 86400);

        // LPUSH en la cola correspondiente
        $this->redis->lPush("queue:$queue", $jobData);

        return $jobId;
    }
}
