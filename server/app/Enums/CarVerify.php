<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Verified()
 * @method static static Refuse()
 */
final class CarVerify extends Enum
{
    const Verified = 1;
    const Refuse = 2;
}
