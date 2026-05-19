<?php

namespace App\Bundles\SharingImageGeneratorBundle;

use Dflydev\DotAccessConfiguration\Configuration;
use Sculpin\Core\Event\SourceSetEvent;
use Sculpin\Core\Sculpin;
use Sculpin\Core\Source\FileSource;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Filesystem\Filesystem;

class SharingImageGenerator implements EventSubscriberInterface
{
    protected $configuration;

    public static function getSubscribedEvents()
    {
        return [
            Sculpin::EVENT_BEFORE_RUN => 'beforeRun',
        ];
    }

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function beforeRun(SourceSetEvent $sourceSetEvent)
    {
        $sourceSet = $sourceSetEvent->sourceSet();

        $env = $this->configuration->get('env') ?? 'dev';

        $filesystem = new Filesystem();
        if (!$filesystem->exists("output_$env/assets/share/")) {
            $filesystem->mkdir("output_$env/assets/share/");
        }

        /** @var FileSource $source */
        foreach ($sourceSet->allSources() as $source) {
            if ($source->isGenerated()) {
                continue;
            }

            if ($source->file()->getExtension() !== 'md') {
                continue;
            }

            if (!$source->data()->get('title')) {
                continue;
            }

            $filename = str_replace('.md', '.jpg', $source->file()->getFilename());
            if ($filesystem->exists("assets/share/$filename")) {
                continue;
            }

            $image = new \App\Seo\SharingImageGenerator();
            if ($title = $source->data()->get('title')) {
                $image->setTitle($title);
            }

            if ($author = $source->data()->get('author.name')) {
                $image->setAuthor("by $author");
            }

            if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $source->file()->getFilename(), $m)) {
                try {
                    $image->setDate(new \DateTimeImmutable($m[1]));
                } catch (\Exception $e) {
                    // skip
                }
            }

            $tags = $source->data()->get('tags');
            if (is_array($tags) && !empty($tags)) {
                $image->setTag((string) reset($tags));
            }

            $image->save("output_$env/assets/share/$filename");
        }
    }
}
