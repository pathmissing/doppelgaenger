<?php

/**
 * \AppserverIo\Doppelgaenger\Entities\Assertions\ChainedAssertion
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */

namespace AppserverIo\Doppelgaenger\Entities\Assertions;

use AppserverIo\Doppelgaenger\Entities\Lists\AssertionList;

/**
 * This class provides the possibility to chain several assertions together
 *
 * @author    Bernhard Wick <bw@appserver.io>
 * @copyright 2015 TechDivision GmbH - <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/doppelgaenger
 * @link      http://www.appserver.io/
 */
class ChainedAssertion extends AbstractAssertion
{
    /**
     * @var \AppserverIo\Doppelgaenger\Entities\Lists\AssertionList $assertionList List of assertion to chain together
     */
    public $assertionList;

    /**
     * @var array $combinators The combinating operators we have to use
     */
    public $combinators;

    /**
     * @var boolean $validatesTo The bool value the assertion should validate to
     */
    public $validatesTo;

    /**
     * @var array $inversionMapping Mapping to inverse the logical meaning of this assertion
     */
    private $inversionMapping;

    /**
     * Default constructor
     *
     * @param \AppserverIo\Doppelgaenger\Entities\Lists\AssertionList $assertionList List of assertion to chain together
     * @param array|string                                            $combinators   The combinating operators we have to use
     *
     * @throws  \InvalidArgumentException
     */
    public function __construct(AssertionList $assertionList, $combinators)
    {
        // Set our attributes
        $this->assertionList = $assertionList;

        // Set the mapping for our inversion
        $this->inversionMapping = array(
            'and' => 'or',
            '&&' => '||',
            'or' => 'and',
            '||' => '&&'
        );

        // There must be enough combinators to chain up the assertions.
        // If not, we do not stand a chance to make this work.
        // If we got only one combinator as a string (not in an array) we will use it throughout
        if (!is_array($combinators)) {
            $this->combinators = array();
            for ($i = 1; $i < $assertionList->count(); $i++) {
                $this->combinators[] = $combinators;
            }

        } else {
            $this->combinators = $combinators;
        }

        // No check if the counts are ok
        if ($assertionList->count() !== (count($this->combinators) + 1)) {
            throw new \InvalidArgumentException();
        }

        parent::__construct();
    }

    /**
     * Will return a string representation of this assertion
     *
     * @return string
     */
    public function getString()
    {
        // Simply iterate over all assertions and chain their string representation together.
        $string = '';
        $iterator = $this->assertionList->getIterator();
        for ($i = 0; $i < $iterator->count(); $i++) {
            // Get the string representation of this assertion
            $string .= $iterator->current()->getString();

            // Follow it up with a combinator
            if (isset($this->combinators[$i])) {
                $string .= ' ' . $this->combinators[$i] . ' ';
            }

            // Move the iterator
            $iterator->next();
        }

        return $string;
    }

    /**
     * Invert the logical meaning of this assertion
     *
     * @return bool
     */
    public function invert()
    {
        if ($this->inverted === true) {
            $this->inverted = false;

        } else {
            $this->inverted = true;
        }

        // Iterate over all assertions and invert them.
        $iterator = $this->assertionList->getIterator();
        for ($i = 0; $i < $iterator->count(); $i++) {
            // Get the string representation of this assertion
            $iterator->current()->invert();

            // Move the iterator
            $iterator->next();
        }

        // Now invert all combinators.
        foreach ($this->combinators as $key => $combinator) {
            if (isset($this->inversionMapping[$combinator])) {
                $this->combinators[$key] = $this->inversionMapping[$combinator];
            }
        }

        return true;
    }
}
