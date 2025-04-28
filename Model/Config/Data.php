<?php
declare(strict_types=1);

namespace PeachCode\FPCWarmer\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Serialize\SerializerInterface;

class Data{

    public const XML_MODULE_STATUS = 'module_fpcwarmer/module_status/status';
    public const XML_MODULE_DEVELOPER_STATUS = 'module_fpcwarmer/developer/basic_auth';
    public const XML_MODULE_DEVELOPER_LOGIN = 'module_fpcwarmer/developer/basic_auth_login';
    public const XML_MODULE_DEVELOPER_PASSWORD = 'module_fpcwarmer/developer/basic_auth_password';
    public const XML_SITEMAP_DATA = 'module_fpcwarmer/sitemap_array/sitemap';

    /**
     * @param SerializerInterface $serializer
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ScopeConfigInterface $scopeConfig
    ) {}

    /**
     * Get config
     *
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_MODULE_STATUS, ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * Get sitemaps configuration
     *
     * @return array
     */
    public function getSitemaps(): array
    {
        $value = $this->scopeConfig->getValue(self::XML_SITEMAP_DATA, ScopeInterface::SCOPE_WEBSITE);

        $decoded = $this->serializer->unserialize($value);

        return $decoded ?? [];
    }

    /**
     * Get Developer Mode Config
     *
     * @return bool
     */
    public function isDeveloperMode(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_MODULE_DEVELOPER_STATUS, ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * Get Developer Mode Login
     *
     * @return string
     */
    public function getDeveloperLogin(): string
    {
        return $this->scopeConfig->getValue(self::XML_MODULE_DEVELOPER_LOGIN, ScopeInterface::SCOPE_WEBSITE) ?? '';
    }

    /**
     * Get Developer Mode Password
     *
     * @return string
     */
    public function getDeveloperPassword(): string
    {
        return $this->scopeConfig->getValue(self::XML_MODULE_DEVELOPER_PASSWORD, ScopeInterface::SCOPE_WEBSITE) ?? '';
    }

}
