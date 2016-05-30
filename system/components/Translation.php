<?php

namespace system\components;


class Translation
{
    /**
     * @param $language
     * @return array
     */
    public function getTranslationFiles($language)
    {
        $dir = App::$systemPath . '/messages/' . $language;

        $files = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir)
        );

        foreach ($iterator as $file) {

            /** @var \SplFileInfo $file */
            if (
                $file->isFile()
                && $file->isReadable()
                && $file->getExtension() === 'php'
            ) {
                $files[] = $file->getPath() . DIRECTORY_SEPARATOR . $file->getFilename();
            }

        }

        return $files;
    }

    /**
     * @param $language
     * @return array
     */
    public function getMessages($language)
    {
        $files = $this->getTranslationFiles($language);
        $messages = [];

        foreach ($files as $file) {
            $messages[$this->getFilename($file)] = require_once $file;
        }

        return $messages;
    }

    public function getFilename($filePath)
    {
        $explodedArray = explode(DIRECTORY_SEPARATOR, $filePath);
        $filename = end($explodedArray);
        return substr($filename, 0, -4);
    }
}