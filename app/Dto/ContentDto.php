<?php


namespace App\Dto;


class ContentDto implements \JsonSerializable
{
    private string $title;
    private string $text;

    public function __construct(string $title, string $text)
    {
        $this->title = $title;
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }


    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
