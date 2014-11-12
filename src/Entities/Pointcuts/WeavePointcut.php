<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @category   Appserver
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\Entities\Pointcuts;

/**
 * AppserverIo\Doppelgaenger\Entities\Pointcut\WeavePointcut
 *
 * Pointcut for direct weaving of advice logic.
 * Can only be used with a qualified method signature e.g. \AppserverIo\Doppelgaenger\Logger->log(__METHOD__)
 *
 * @category   Appserver
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class WeavePointcut extends AbstractSignaturePointcut
{

    /**
     * Whether or not the pointcut is considered static, meaning is has to be weaved and evaluated during runtime
     * anyway
     *
     * @var boolean IS_STATIC
     */
    const IS_STATIC = true;

    /**
     * The type of this pointcut
     *
     * @var string TYPE
     */
    const TYPE = 'weave';

    /**
     * Returns a string representation of the pointcut
     *
     * @return string
     */
    public function getString()
    {
        // we have to test whether or not we need an instance of the used class first.
        // if the call is not static then we do
        $string = '';
        $expression = $this->getExpression();
        if ($this->callType === self::CALL_TYPE_OBJECT) {

            // don't forget to create an instance first
            $variable = '$' . lcfirst(str_replace('\\', '', $this->structure));
            $string .= $variable . ' = new ' . $this->structure . '();
            ';
            $string .= $variable . $this->callType . $this->function . ';
            ';

        } else {

            $string .= $expression . ';
            ';
        }

        return $string;
    }

    /**
     * Whether or not the pointcut matches a given candidate.
     * Weave pointcuts will always return true, as they do not pose any condition
     *
     * @param mixed $candidate
     *
     * @return boolean
     */
    public function matches($candidate)
    {
        return true;
    }
}
