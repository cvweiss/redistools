<?php

namespace cvweiss\redistools;

class RedisTtlCounter
{
    private $queueName;
    private $ttl = 3600;

    public function __construct($queueName, $ttl = 3600)
    {
        $this->queueName = $queueName;
        $this->ttl = $ttl;
    }

    public function add($value)
    {
        global $redis;

        $value = serialize($value);
        $redis->zAdd($this->queueName, time(), $value);
    }

    public function count()
    {
        global $redis;

        $redis->zRemRangeByScore($this->queueName, 0, (time() - $this->ttl));

        return $redis->zCard($this->queueName);
    }
}
