<?php
declare(strict_types=1);

namespace PeachCode\FPCWarmer\Model\PageCache;

use Exception;
use Generator;
use GuzzleHttp\Client;
use PeachCode\FPCWarmer\Model\Queue\Item;
use PeachCode\FPCWarmer\Api\Warmer\Processing\WarmPageCache;
use PeachCode\FPCWarmer\Model\ResourceModel\Queue\Item\CollectionFactory as ItemCollectionFactory;
use GuzzleHttp\Pool;
use Psr\Log\LoggerInterface;

class CacheGenerator implements WarmPageCache
{
    private const PAGE_SIZE = 20;
    private const CONCURRENCY = 5;

    /**
     * @param ItemCollectionFactory $itemCollectionFactory
     * @param Client $client
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly ItemCollectionFactory $itemCollectionFactory,
        private readonly Client $client,
        private readonly LoggerInterface $logger
    ) {}

    /**
     * @return int
     */
    public function cacheGenerator(): int
    {
        $this->logger->notice("Starting cache warming with optimized generator using Pool...");

        $totalProcessed = 0;

        foreach ($this->generateQueueItems() as $chunk) {
            $promises = function () use ($chunk) {
                foreach ($chunk as $item) {
                    $url = $item->getData('url');
                    yield function () use ($url, $item) {
                        return $this->client->requestAsync('GET', $url, [
                            'headers' => [
                                'Cache-Control' => 'public, max-age=3600',
                                'Pragma' => 'cache',
                                'Vary' => 'Accept-Encoding',
                            ]
                        ])->then(
                            function ($response) use ($item) {
                                $headers = $response->getHeaders();
                                $cacheDebugHeader = $headers['X-Magento-Cache-Debug'][0] ?? 'UNKNOWN';
                                $this->logger->info('Warmed and marked URL: ' . $item->getData('url') . ' - Cache Status: ' . $cacheDebugHeader);
                                $this->markAsProcessed($item);
                            },
                            function ($reason) use ($item, $url) {
                                $this->logger->error('Failed to warm URL: ' . $url . '. Reason: ' . $reason);
                            }
                        );
                    };
                }
            };

            $pool = new Pool(
                $this->client,
                $promises(),
                [
                    'concurrency' => self::CONCURRENCY,
                    'fulfilled' => function ($response, $index) {
                    },
                    'rejected' => function ($reason, $index) {
                    },
                ]
            );

            $pool->promise()->wait();
            $totalProcessed += count($chunk);
        }

        $this->logger->notice("Finished cache warming. Total processed: $totalProcessed");

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
        $this->logger->info('Warmed and marked URL: ' . $item->getData('url'));
    }
}
