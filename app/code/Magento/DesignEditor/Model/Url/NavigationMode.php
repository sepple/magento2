<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Magento
 * @package     Magento_DesignEditor
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Navigation mode design editor url model
 */
namespace Magento\DesignEditor\Model\Url;

class NavigationMode extends \Magento\Core\Model\Url
{
    /**
     * VDE helper
     *
     * @var \Magento\DesignEditor\Helper\Data
     */
    protected $_helper;

    /**
     * Current mode in design editor
     *
     * @var string
     */
    protected $_mode;

    /**
     * Current editable theme id
     *
     * @var int
     */
    protected $_themeId;

    /**
     * @param \Magento\App\Route\ConfigInterface $routeConfig
     * @param \Magento\App\RequestInterface $request
     * @param \Magento\Core\Model\Url\SecurityInfoInterface $urlSecurityInfo
     * @param \Magento\Core\Model\Store\Config $coreStoreConfig
     * @param \Magento\Core\Model\App $app
     * @param \Magento\Core\Model\StoreManagerInterface $storeManager
     * @param \Magento\Core\Model\Session $session
     * @param \Magento\Session\SidResolverInterface $sidResolver
     * @param \Magento\DesignEditor\Helper\Data $helper
     * @param string $areaCode
     * @param array $data
     * 
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\App\Route\ConfigInterface $routeConfig,
        \Magento\App\RequestInterface $request,
        \Magento\Core\Model\Url\SecurityInfoInterface $urlSecurityInfo,
        \Magento\Core\Model\Store\Config $coreStoreConfig,
        \Magento\Core\Model\App $app,
        \Magento\Core\Model\StoreManagerInterface $storeManager,
        \Magento\Core\Model\Session $session,
        \Magento\Session\SidResolverInterface $sidResolver,
        \Magento\DesignEditor\Helper\Data $helper,
        $areaCode,
        array $data = array()
    ) {
        $this->_helper = $helper;
        if (isset($data['mode'])) {
            $this->_mode = $data['mode'];
        }

        if (isset($data['themeId'])) {
            $this->_themeId = $data['themeId'];
        }
        parent::__construct(
            $routeConfig,
            $request,
            $urlSecurityInfo,
            $coreStoreConfig,
            $app,
            $storeManager,
            $session,
            $sidResolver,
            $areaCode,
            $data
        );
    }

    /**
     * Retrieve route URL
     *
     * @param string $routePath
     * @param array $routeParams
     * @return string
     */
    public function getRouteUrl($routePath = null, $routeParams = null)
    {
        $this->_hasThemeAndMode();
        $url = parent::getRouteUrl($routePath, $routeParams);
        $baseUrl = trim($this->getBaseUrl(), '/');
        $vdeBaseUrl = implode('/', array(
            $baseUrl, $this->_helper->getFrontName(), $this->_mode, $this->_themeId
        ));
        if (strpos($url, $baseUrl) === 0 && strpos($url, $vdeBaseUrl) === false) {
            $url = str_replace($baseUrl, $vdeBaseUrl, $url);
        }
        return $url;
    }

    /**
     * Verifies is theme and mode were set or not
     *
     * Ugly hack to make it possible to cover class with unit test
     *
     * @return $this
     */
    protected function _hasThemeAndMode()
    {
        if (!$this->_mode) {
            $this->_mode = $this->getRequest()->getAlias('editorMode');
        }

        if (!$this->_themeId) {
            $this->_themeId = $this->getRequest()->getAlias('themeId');
        }
        return $this;
    }
}
