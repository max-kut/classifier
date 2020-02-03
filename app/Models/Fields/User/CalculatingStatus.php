<?php

namespace App\Models\Fields\User;

use MyCLabs\Enum\Enum;

/**
 * Class CalculatingStatus
 *
 * @method static static PENDING()
 * @method static static HAS_PREDICTED()
 */
class CalculatingStatus extends Enum
{
    private const PENDING = 'PENDING';
    private const HAS_PREDICTED = 'HAS_PREDICTED';
}
