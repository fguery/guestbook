<?php
declare(strict_types=1);

namespace Bark;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Bark\Model\Guestbook;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $c)
    {
        $c['db'] = function () use ($c): \PDO {
            return new \PDO(
                sprintf(
                    'pgsql:host=%s;dbname=%s;',
                    getenv('POSTGRES_HOST') ? getenv('POSTGRES_HOST') : 'localhost',
                    getenv('POSTGRES_DATABASE') ? getenv('POSTGRES_DATABASE') : 'guestbook'
                ),
                getenv('POSTGRES_USER') ? getenv('POSTGRES_USER') : 'guestbook',
                getenv('POSTGRES_PASSWORD') ? getenv('POSTGRES_PASSWORD') : '9gD@E0WO',
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                ]
            );
        };

        $c['guestbookModel'] = function () use ($c) {
            return new Guestbook($c['db']);
        };
    }
}
