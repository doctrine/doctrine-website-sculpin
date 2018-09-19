<?php

declare(strict_types=1);

namespace Doctrine\Website\DataSources;

use Doctrine\Website\Builder\SourceFile;
use Doctrine\Website\Builder\SourceFileRepository;
use Doctrine\Website\DataSource\DataSource;
use function array_reverse;
use function usort;

class BlogPosts implements DataSource
{
    /** @var SourceFileRepository */
    private $sourceFileRepository;

    public function __construct(SourceFileRepository $sourceFileRepository)
    {
        $this->sourceFileRepository = $sourceFileRepository;
    }

    /**
     * @return mixed[][]
     */
    public function getData() : array
    {
        $sourceFiles = $this->sourceFileRepository->getFiles('', 'source/blog');

        usort($sourceFiles, function (SourceFile $a, SourceFile $b) : int {
            return $a->getDate()->getTimestamp() - $b->getDate()->getTimestamp();
        });

        $reversedSourceFiles = array_reverse($sourceFiles);

        $blogPostsData = [];

        foreach ($reversedSourceFiles as $sourceFile) {
            $blogPostsData[] = [
                'url' => $sourceFile->getParameter('url'),
                'title' => $sourceFile->getParameter('title'),
                'authorName' => (string) $sourceFile->getParameter('authorName'),
                'authorEmail' => (string) $sourceFile->getParameter('authorEmail'),
                'contents' => $sourceFile->getContents(),
                'date' => $sourceFile->getDate(),
            ];
        }

        return $blogPostsData;
    }
}