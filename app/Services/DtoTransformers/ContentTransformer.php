<?php


namespace App\Services\DtoTransformers;


use App\Dto\ContentDto;
use JetBrains\PhpStorm\Pure;

class ContentTransformer
{
    /**
     **
     * @param object[] $posts
     * @return ContentDto[]
     */

    #[Pure] public function transform(array $posts): array
    {
        $arrayDto = [];
        foreach ($posts as $value) {
            $arrayDto[] = $this->getDto($value);
        }

        return $arrayDto;
    }

    #[Pure] private function getDto(object $post): ContentDto
    {
        $title = $this->strip_tags_content($post->post_title ?? '');
        $content = $this->strip_tags_content($post->post_content ?? '');

        return new ContentDto($title, $content);
    }

    private function strip_tags_content($string): string
    {
        // ----- remove HTML TAGs -----
        $string = preg_replace('/<[^>]*>/', ' ', $string);
        // ----- remove control characters -----
        $string = str_replace("\r", '', $string);
        $string = str_replace("\n", ' ', $string);
        $string = str_replace("\t", ' ', $string);
        $string = preg_replace("/&(.*?);/", '', $string);
        // ----- remove multiple spaces -----
        return trim(preg_replace('/ {2,}/', ' ', $string));

    }
}
