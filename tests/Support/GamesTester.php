<?php

declare(strict_types=1);

namespace App\Tests\Support;

use Codeception\Module\Symfony\DoctrineAssertionsTrait;

/**
 * Inherited Methods
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
*/
class GamesTester extends \Codeception\Actor
{
    use _generated\GamesTesterActions;

    /**
     * Define custom actions here
     */
}
