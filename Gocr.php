<?php
/**
 * A wrapper to work with Gocr inside PHP scripts.
 *
 * @author  Stéphane Monnot <smonnot@solire.fr>
 * @license MIT http://mit-license.org/
 */

namespace Shinbuntu\Gocr;

/**
 * A wrapper to work with Gocr inside PHP scripts.
 *
 * @author  Stéphane Monnot <smonnot@solire.fr>
 * @license MIT http://mit-license.org/
 */
class Gocr
{
    /**
     * Path to Gocr binary
     * @var string
     */
    protected $gocrBinPath = '/usr/bin/gocr';

    /**
     * Path of the image to be recognized
     * @var string
     */
    protected $imagePath;

    protected $spaceWidthParam;
    protected $modeParam;
    protected $valueForCertaintyOfRecognitionParam;
    protected $databasePathParam;

    /**
     * Construct
     *
     * @param string $imagePath Path to the image to be recognized
     *
     * @throws \Exception
     */
    public function __construct($imagePath)
    {
        if ($imagePath == '') {
            throw new \InvalidArgumentException('Path to the image can\'t be empty.');
        }

        if (!file_exists($imagePath)) {
            throw new \Exception(sprintf('The file "%s" does not exist.', $imagePath));
        }

        $this->imagePath = $imagePath;
    }

    /**
     * Get spacewidth between words in units of dots
     *
     * @return float|int|null
     */
    public function getSpaceWidthParam()
    {
        return $this->spaceWidthParam;
    }

    /**
     * Set spacewidth between words in units of dots (default: 0 for autodetect), wider widths are interpreted as word
     * spaces, smaller as character spaces
     *
     * @param float|int|null $spaceWidthParam Spacewidth to set
     * @return void
     */
    public function setSpaceWidthParam($spaceWidthParam)
    {
        $this->spaceWidthParam = $spaceWidthParam;
    }

    /**
     * Get Operational mode
     *
     * @return int|null
     */
    public function getModeParam()
    {
        return $this->modeParam;
    }

    /**
     * Set Operational mode; mode is a bitfield (default: 0)
     *
     * @param int|null $modeParam Operational mode to set
     * @return void
     */
    public function setModeParam($modeParam)
    {
        if (!is_null($modeParam) && !is_int($modeParam)) {
            $exception = new \InvalidArgumentException(
                sprintf('The parameter "$modeParam" must be an integer or null.')
            );
            throw $exception;
        }
        $this->modeParam = $modeParam;
    }

    /**
     * Get value for certainty of recognition
     *
     * @return int|null
     */
    public function getValueForCertaintyOfRecognitionParam()
    {
        return $this->valueForCertaintyOfRecognitionParam;
    }

    /**
     * Set value for certainty of recognition (0..100; default: 95), characters with a higher certainty are accepted,
     * characters with a lower certainty are treated as unknown (not recognized); set higher values, if you want to have
     * only more certain recognized characters
     *
     * @param int|null $valueForCertaintyOfRecognitionParam Value for certainty of recognition to set.
     *                                                      If null, the default value (95) will be used
     * @return void
     */
    public function setValueForCertaintyOfRecognitionParam($valueForCertaintyOfRecognitionParam)
    {
        if (!is_null($valueForCertaintyOfRecognitionParam) && !is_int($valueForCertaintyOfRecognitionParam)) {
            $exception = new \InvalidArgumentException(
                sprintf('The parameter "$valueForCertaintyOfRecognitionParam" must be an integer or null.')
            );
            throw $exception;
        }
        $this->valueForCertaintyOfRecognitionParam = $valueForCertaintyOfRecognitionParam;
    }

    /**
     * Get database path
     *
     * @return string|null
     */
    public function getDatabasePathParam()
    {
        return $this->databasePathParam;
    }

    /**
     * Set database path, a final slash must be included, default is ./db/, this path will be populated with images of
     * learned characters
     *
     * @param string|null $databasePathParam Database path
     * @return void
     */
    public function setDatabasePathParam($databasePathParam)
    {
        $this->databasePathParam = $databasePathParam;
    }

    /**
     * Run recognize
     *
     * @return string|null Text extract from image
     */
    public function recognize()
    {
        return $this->execute();
    }

    /**
     * Execute Gocr command and return output
     *
     * @return string
     */
    protected function execute()
    {
        exec($this->getCommand(), $output);
        return implode("\n", $output);
    }

    /**
     * Get Gocr command line with params
     *
     * @return string
     */
    protected function getCommand()
    {
        $command = $this->gocrBinPath
            . ' '
            . $this->getParams()
            . ' '
            . $this->imagePath
        ;

        return $command;
    }

    /**
     * Get Gocr command line params string
     *
     * @return string
     */
    protected function getParams()
    {
        $stringParams = '';
        if ($this->valueForCertaintyOfRecognitionParam) {
            $stringParams .= '-a '
                . $this->valueForCertaintyOfRecognitionParam;
        }

        if ($this->databasePathParam) {
            $stringParams .= '-p '
                . $this->databasePathParam;
        }

        if ($this->modeParam) {
            $stringParams .= '-m '
                . $this->modeParam;
        }

        if ($this->spaceWidthParam) {
            $stringParams .= '-s '
                . $this->spaceWidthParam;
        }

        return $stringParams;
    }
}
