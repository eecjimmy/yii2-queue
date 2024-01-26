<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace yii\queue\closure;

use function Opis\Closure\unserialize as opis_unserialize;
use yii\queue\JobInterface;

/**
 * Closure Job.
 *
 * @author Roman Zhuravlev <zhuravljov@gmail.com>
 */
class Job implements JobInterface
{
    /**
     * @var string serialized closure
     */
    public $serialized;

    /**
     * @var mixed unserialized closure
     */
    private $unserialized;


    /**
     * Unserializes and executes a closure.
     * @inheritdoc
     */
    public function execute($queue)
    {
        $unserialized = $this->unserialize();
        if ($unserialized instanceof \Closure) {
            return $unserialized();
        }
        return $unserialized->execute($queue);
    }

    /**
     * Unserializes serialized job and caches it.
     * @return mixed
     */
    private function unserialize()
    {
        if ($this->unserialized === null) {
            $this->unserialized = opis_unserialize($this->serialized);
        }
        return $this->unserialized;
    }
}
