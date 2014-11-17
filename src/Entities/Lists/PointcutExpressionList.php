<?php
/**
 * File containing the PointcutExpressionList class
 *
 * PHP version 5
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace AppserverIo\Doppelgaenger\Entities\Lists;

/**
 * AppserverIo\Doppelgaenger\Entities\Lists\PointcutExpressionList
 *
 * A typed list for pointcuts
 *
 * @category   Library
 * @package    Doppelgaenger
 * @subpackage Entities
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class PointcutExpressionList extends AbstractTypedList
{
    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->itemType = 'AppserverIo\Doppelgaenger\Entities\PointcutExpression';
    }
}