<?php
namespace Mandala\DesignModule;

use Exception;

/**
 * Takes a Design and generates SVG content on the server-side using PhantomJS. We have to do this
 * even though SVG data is generated on the client-side since we can't trust the data from the client
 * and it will be impossible to validate SVG content directly.
 */
class DesignRenderer
{
    private $scriptPath;

    /**
     * @param string $scriptPath path to phantomjs render script to use
     */
    function __construct($scriptPath)
    {
        $this->scriptPath = $scriptPath;
    }

    /**
     * @param Design $design
     * @throws Exception
     * @return string SVG content
     */
    public function render(Design $design)
    {
        exec('phantomjs ' . $this->scriptPath . ' \'' . $design->data . '\'', $output, $success);
        if ($success !== 0) {
            throw new Exception('Failed to render design SVG for data: ' . $design->data);
        }
        return join('', $output);
    }
} 