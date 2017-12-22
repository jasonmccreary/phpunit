<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Suite for .phpt test cases.
 */
class \PHPUnit\Extensions\PhptTestSuite extends \PHPUnit\Framework\TestSuite
{
    /**
     * Constructs a new TestSuite for .phpt test cases.
     *
     * @param string $directory
     *
     * @throws \PHPUnit\Framework\Exception
     */
    public function __construct($directory)
    {
        if (is_string($directory) && is_dir($directory)) {
            $this->setName($directory);

            $facade = new File_Iterator_Facade;
            $files  = $facade->getFilesAsArray($directory, '.phpt');

            foreach ($files as $file) {
                $this->addTestFile($file);
            }
        } else {
            throw \PHPUnit\Util\InvalidArgumentHelper::factory(1, 'directory name');
        }
    }
}
