<?php

namespace Vulcan\HashUpload;

use SilverStripe\Assets\File;
use SilverStripe\Assets\Upload;

/**
 * Class HashUpload
 * @package Vulcan\HashUpload
 */
class HashUpload extends Upload
{
    /**
     * The amount of tries that a rename will be attempted if the first already exists
     *
     * @config
     * @var int
     */
    private static $max_rename_retries = 30;

    /**
     * Prefixes all filenames with this string
     *
     * @config
     * @var string
     */
    private static $filename_prefix = 'vd_';

    /**
     * Takes the result from the parent and hashes the filename
     *
     * @param array       $tmpFile
     * @param null|string $folderPath
     *
     * @return string
     * @throws \Exception
     */
    public function getValidFilename($tmpFile, $folderPath = null)
    {
        $filename = basename(parent::getValidFilename($tmpFile, $folderPath));

        $maxTries = $this->config()->get('max_rename_retries') ?: 30;

        if (!$folderPath) {
            $folderPath = $this->config()->uploads_folder;
        }

        for ($i = 0; $i < $maxTries; $i++) {
            $filename = $this->generateFilenameHash($filename, $folderPath);

            if (!File::find($filename)) {
                return $filename;
            }
        }

        throw new \Exception("Could not rename {$filename} with {$maxTries} tries");
    }

    /**
     * Generates a hashed filename and returns it with the folder path
     * included if provided
     *
     * @param string      $filename
     * @param null|string $folderPath
     *
     * @return string
     */
    public function generateFilenameHash($filename, $folderPath = null)
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        $prefix = $this->config()->get('filename_prefix');

        return File::join_paths($folderPath, $prefix . substr(md5(microtime() . $filename), 0, 25) . ".{$extension}");
    }
}