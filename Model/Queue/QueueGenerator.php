<?php
declare(strict_types=1);

namespace PeachCode\FPCWarmer\Model\Queue;

use Exception;
use Magento\Framework\HTTP\Client\Curl;
use PeachCode\FPCWarmer\Api\Warmer\Queue\GenerateQueueInterface;
use PeachCode\FPCWarmer\Logger\Logger;
use PeachCode\FPCWarmer\Model\Config\Data;
use Symfony\Component\Console\Command\Command;

class QueueGenerator  implements GenerateQueueInterface {

    /**
     * @param ItemFactory $itemFactory
     * @param Data $config
     * @param Logger $handler
     * @param Curl $curl
     */
    public function __construct(
        private readonly ItemFactory $itemFactory,
        private readonly Data        $config,
        private readonly Logger      $handler,
        private readonly Curl        $curl
    ) {}

    /**
     * Start queue generation
     *
     * @return int
     */
    public function process(): int
    {
        $this->handler->notice("Processing queue...");

        $sitemaps = $this->config->getSitemaps();

        foreach ($sitemaps as $sitemap) {
            $sitemapUrl = $sitemap['sitemap_url'] ?? null;

            if (!$sitemapUrl) {
                $this->handler->error("Missing sitemap URL", $sitemap['sitemap_url']);
                continue;
            }

            $this->prepareValue($sitemapUrl);
        }

        return Command::SUCCESS;
    }

    /**
     * Url extraction from sitemap
     *
     * @param string $xmlContent
     * @return array
     */
    private function extractUrlsFromSitemap(string $xmlContent): array
    {
        $urls = [];

        libxml_use_internal_errors(true);

        $xml = simplexml_load_string($xmlContent);

        if ($xml === false) {
            return [];
        }

        $namespaces = $xml->getDocNamespaces(true);

        $locElements = $xml->xpath('//url/loc');

        if (isset($namespaces[''])) {
            $xml->registerXPathNamespace('ns', $namespaces['']);
            $locElements = $xml->xpath('//ns:url/ns:loc');
        }

        foreach ($locElements as $loc) {
            $urls[] = (string)$loc;
        }

        return $urls;
    }

    /**
     * Set data to table - queue generation
     *
     * @param $sitemapUrl
     * @return void
     */
    private function prepareValue($sitemapUrl): void
    {
        try {

            if($this->config->isDeveloperMode()){
                $sitemapUrl = $this->prepareDeveloperValue($sitemapUrl);
            }

            $this->curl->get($sitemapUrl);
            $xmlContent = $this->curl->getBody();

            $urlsFromSitemap = $this->extractUrlsFromSitemap($xmlContent);

            foreach ($urlsFromSitemap as $url) {

                if($this->config->isDeveloperMode()){
                    $url = $this->prepareDeveloperValue($url);
                }

                $cartItem = $this->itemFactory->create();
                $cartItem->setData([
                    'url' => $url,
                    'status' => '0'
                ]);

                // TODO: I need to use repository
                $cartItem->save();
            }

        } catch (Exception $e) {
            $this->handler->error("Failed to fetch sitemap: " . $e->getMessage());
        }
    }

    /**
     * Add Basic Auth to url
     *
     * @param string $url
     * @return string
     */
    private function prepareDeveloperValue(string $url): string
    {
        $login = $this->config->getDeveloperLogin();
        $password = $this->config->getDeveloperPassword();

        if (!$login || !$password) {
            return $url;
        }

        $parsedUrl = parse_url($url);

        if (!isset($parsedUrl['host'])) {
            return $url;
        }

        $auth = urlencode($login) . ':' . urlencode($password);

        $scheme = $parsedUrl['scheme'] ?? 'https';
        $host = $parsedUrl['host'];
        $port = isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '';
        $path = $parsedUrl['path'] ?? '';
        $query = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';
        $fragment = isset($parsedUrl['fragment']) ? '#' . $parsedUrl['fragment'] : '';

        return $scheme . '://' . $auth . '@' . $host . $port . $path . $query . $fragment;
    }
}
