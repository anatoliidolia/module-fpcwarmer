<?php
declare(strict_types=1);

namespace PeachCode\FPCWarmer\Model\PageCache;

use Exception;
use Generator;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;
use PeachCode\FPCWarmer\Logger\Logger;
use PeachCode\FPCWarmer\Model\Queue\Item;
use PeachCode\FPCWarmer\Api\Warmer\Processing\WarmPageCache;
use PeachCode\FPCWarmer\Model\ResourceModel\Queue\Item\CollectionFactory as ItemCollectionFactory;

class CacheGenerator implements WarmPageCache
{
    private const PAGE_SIZE = 20;

    /**
     * @param ItemCollectionFactory $itemCollectionFactory
     * @param Client $client
     * @param Logger $handler
     */
    public function __construct(
        private readonly ItemCollectionFactory $itemCollectionFactory,
        private readonly Client                $client,
        private readonly Logger                $handler
    ) {}

    /**
     * @return int
     */
    public function cacheGenerator(): int
    {
        $this->handler->notice("Starting cache warming with optimized generator...");

        $totalProcessed = 0;

        foreach ($this->generateQueueItems() as $chunk) {
            $promises = [];

            foreach ($chunk as $item) {
                $url = $item->getData('url');

                $promises[] = $this->client->getAsync($url)
                    ->then(
                        function () use ($item) {
                            $this->markAsProcessed($item);
                        },
                        function ($reason) use ($item, $url) {
                            $this->handler->error('Failed to warm URL: ' . $url . '. Reason: ' . $reason);
                        }
                    );
            }

            Utils::settle($promises)->wait();

            $totalProcessed += count($promises);
        }

        $this->handler->notice("Finished cache warming. Total processed: $totalProcessed");

        return $totalProcessed;
    }

    /**
     * Generate Queue Items
     *
     * @return Generator
     */
    private function generateQueueItems(): Generator
    {
        $currentPage = 1;

        do {
            $collection = $this->itemCollectionFactory->create();
            $collection->addFieldToFilter('status', ['eq' => '0']);
            $collection->setPageSize(self::PAGE_SIZE);
            $collection->setCurPage($currentPage);
            $collection->load();

            if (!$collection->count()) {
                break;
            }

            yield $collection->getItems();

            $currentPage++;
        } while (true);
    }

    /**
     * Set warm status for url
     *
     * @param Item $item
     * @return void
     * @throws Exception
     */
    private function markAsProcessed(Item $item): void
    {
        $item->setData('status', '1');
        $item->save();
        $this->handler->info('Warmed and marked URL: ' . $item->getData('url'));
    }
}
