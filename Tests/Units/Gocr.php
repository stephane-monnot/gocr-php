<?php
/**
 * Test class for Gocr.
 *
 * @author  Stéphane Monnot <smonnot@solire.fr>
 * @license MIT http://mit-license.org/
 */

namespace Shinbuntu\Gocr\Tests\Units;

use atoum;
use Shinbuntu\Gocr\Gocr as TestClass;

/**
 * Test class for Gocr.
 *
 * @author  Stéphane Monnot <smonnot@solire.fr>
 * @license MIT http://mit-license.org/
 */
class Gocr extends atoum
{
    /**
     * Test construct
     *
     * @return void
     */
    public function testConstruct()
    {
        $this
            ->if($gocr = new TestClass(__DIR__ . DIRECTORY_SEPARATOR . 'testData/images/welcome.png'))
            ->object($gocr)
                ->isInstanceOf('\Shinbuntu\Gocr\Gocr')
            ->object()

            ->exception(
                function(){
                    new TestClass('');
                }
            )
                ->hasMessage('Path to the image can\'t be empty.')
                ->isInstanceOf('\InvalidArgumentException')

            ->exception(
                function(){
                    new TestClass(__DIR__ . DIRECTORY_SEPARATOR . 'testData/images/imagenotexist.png');
                }
            )
                ->hasMessage('The file "' . __DIR__ . DIRECTORY_SEPARATOR . 'testData/images/imagenotexist.png" does not exist.')
                ->isInstanceOf('\Exception')

        ;
    }

    /**
     * Test method recognize
     *
     * @return void
     */
    public function testRecognize()
    {
        $this
            ->if($gocr = new TestClass(__DIR__ . DIRECTORY_SEPARATOR . 'testData/images/welcome.png'))
            ->string($gocr->recognize())
                ->isEqualTo(
                    'Hello GOCR, Welcome to PHP\'s world'
                    . "\n"
                    . 'Enjoy it ! ! !'
                )

            ->and($gocr->setSpaceWidthParam(1))
            ->string($gocr->recognize())
                ->isEqualTo(
                    'H e l l o G O C R, W e l c o m e t o P H P \' s w o r l d'
                    . "\n"
                    . 'E n j o y i t ! ! !'
                )

            ->and($gocr->setSpaceWidthParam(25))
            ->string($gocr->recognize())
                ->isEqualTo(
                    'HelloGOCR,WelcometoPHP\'sworld'
                    . "\n"
                    . 'Enjoyit!!!'
                )
        ;
    }

    /**
     * Test method recognize with SpaceWidthParam
     *
     * @return void
     */
    public function testRecognizeWithSpaceWidthParam()
    {
        $this
            ->if($gocr = new TestClass(__DIR__ . DIRECTORY_SEPARATOR . 'testData/images/welcome.png'))
            ->and($gocr->setSpaceWidthParam(1))
            ->string($gocr->recognize())
            ->isEqualTo(
                'H e l l o G O C R, W e l c o m e t o P H P \' s w o r l d'
                . "\n"
                . 'E n j o y i t ! ! !'
            )

            ->and($gocr->setSpaceWidthParam(25))
            ->string($gocr->recognize())
            ->isEqualTo(
                'HelloGOCR,WelcometoPHP\'sworld'
                . "\n"
                . 'Enjoyit!!!'
            )
        ;
    }

    /**
     * Test method recognize with ValueForCertaintyOfRecognitionParam
     *
     * @return void
     */
    public function testRecognizeWithValueForCertaintyOfRecognitionParam()
    {
        $this
            ->if($gocr = new TestClass(__DIR__ . DIRECTORY_SEPARATOR . 'testData/images/welcome.png'))
            ->and($gocr->setValueForCertaintyOfRecognitionParam(100))
            ->string($gocr->recognize())
            ->isEqualTo(
                'He__o G_CR_ We_come to PHP\'s wor_d'
                . "\n"
                . '_njoy it _ _ _'
            )

            ->and($gocr->setValueForCertaintyOfRecognitionParam(0))
            ->string($gocr->recognize())
            ->isEqualTo(
                'Hello GOCR, Welcome to PHP\'s world'
                . "\n"
                . 'Enjoy it ! ! !'
            )
        ;
    }

    /**
     * Test method recognize with DatabasePathParam
     *
     * @return void
     */
    public function testRecognizeWithDatabasePathParam()
    {
        $this
            ->if($gocr = new TestClass(__DIR__ . DIRECTORY_SEPARATOR . 'testData/images/welcome.png'))
            ->and($gocr->setDatabasePathParam(__DIR__ . DIRECTORY_SEPARATOR . 'testData/db/'))
            ->and($gocr->setModeParam(258))
            ->string($gocr->recognize())
            ->isEqualTo(
                'ABCCD EFGHi JBCkD1B 2D 3A345 6D7C8'
                . "\n"
                . '9;/DX *2 A A A'
            )

//            ->and($gocr->setValueForCertaintyOfRecognitionParam(0))
//            ->string($gocr->recognize())
//            ->isEqualTo(
//                'Hello GOCR, Welcome to PHP\'s world'
//                . "\n"
//                . 'Enjoy it ! ! !'
//            )
        ;
    }

    /**
     * Test getters et setters
     *
     * @return void
     */
    public function testGetAndSet()
    {
        $this
            ->if($gocr = new TestClass(__DIR__ . DIRECTORY_SEPARATOR .  'testData/images/welcome.png'))
            ->and($gocr->setDatabasePathParam('./db/'))
            ->string($gocr->getDatabasePathParam())
                ->isEqualTo('./db/')

            ->and($gocr->setModeParam(225))
            ->integer($gocr->getModeParam())
                ->isEqualTo(225)

            ->and($gocr->setSpaceWidthParam(30.5))
            ->float($gocr->getSpaceWidthParam())
                ->isEqualTo(30.5)

            ->and($gocr->setSpaceWidthParam(30))
            ->integer($gocr->getSpaceWidthParam())
                ->isEqualTo(30)

            ->and($gocr->setValueForCertaintyOfRecognitionParam(30))
            ->integer($gocr->getValueForCertaintyOfRecognitionParam())
                ->isEqualTo(30)

            ->exception(function()use($gocr){
                $gocr->setValueForCertaintyOfRecognitionParam(30.5);
            })
                ->hasMessage('The parameter "$valueForCertaintyOfRecognitionParam" must be an integer or null.')
                ->isInstanceOf('\InvalidArgumentException')

            ->exception(function()use($gocr){
                $gocr->setModeParam(30.5);
            })
                ->hasMessage('The parameter "$modeParam" must be an integer or null.')
                ->isInstanceOf('\InvalidArgumentException')
        ;

    }
}
