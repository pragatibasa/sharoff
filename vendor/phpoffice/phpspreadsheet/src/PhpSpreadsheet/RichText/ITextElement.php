<?php

namespace PhpOffice\PhpSpreadsheet\RichText;

use PhpOffice\PhpSpreadsheet\Style\Font;

interface ITextElement
{
    /**
     * Get text.
     *
     * @return string Text
     */
    public function getText();

    /**
     * Set text.
     *
     * @param $text string Text
     *
     * @return ITextElement
     */
    public function setText($text);

    /**
     * Get font.
     *
     * @return null|Font
     */
    public function getFont();

    /**
     * Get hash code.
     *
     * @return string Hash code
     */
    public function getHashCode();
}
