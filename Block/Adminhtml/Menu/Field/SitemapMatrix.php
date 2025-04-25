<?php
declare(strict_types=1);

namespace PeachCode\FPCWarmer\Block\Adminhtml\Menu\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class SitemapMatrix extends AbstractFieldArray
{

    /**
     * Prepare rendering the new field by adding all the needed columns
     *
     */
    protected function _prepareToRender(): void
    {

        $this->addColumn('sitemap_alias', [
            'label' => __('Sitemap Alias'),
            'type' => 'text',
        ]);

        $this->addColumn('sitemap_url', [
            'label' => __('Sitemap URL'),
            'type' => 'url',
        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }
}
