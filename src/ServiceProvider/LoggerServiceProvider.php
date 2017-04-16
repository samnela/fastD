<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\ServiceProvider;

use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Logger;

/**
 * Class LoggerServiceProvider.
 */
class LoggerServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $logger = new Logger(app()->getName());

        $logs = config()->get('log');
        $path = app()->getPath().'/runtime/logs';

        foreach ($logs as $logHandle) {
            list($handle, $name, $level, $format) = array_pad($logHandle, 4, null);
            if (is_string($handle)) {
                $handle = new $handle($path.'/'.$name, $level);
            }
            null !== $format && $handle->setFormatter(is_string($format) ? new LineFormatter($format) : $format);
            $logger->pushHandler($handle);
        }

        $container->add('logger', $logger);
    }
}
