<?php
/**
 * Fontis Australia Extension for Magento 2
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Fontis
 * @package    Fontis_Australia
 * @copyright  Copyright (c) 2016 Fontis Pty. Ltd. (https://www.fontis.com.au)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Fontis\Australia\Block\Payment\BPAY;

use Magento\Framework\View\Element\Template;
use Fontis\Australia\Model\Payment\BPAY\PaymentMethod;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Sales\Api\Data\OrderPaymentInterface;

class CheckoutSuccess extends Template implements Details
{
    use Display;

    /**
     * @var OrderPaymentInterface
     */
    protected $orderPayment;

    /**
     * @var bool
     */
    protected $shouldOutput = false;

    /**
     * Constructor
     *
     * @param Template\Context $context
     * @param CheckoutSession $checkoutSession
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CheckoutSession $checkoutSession,
        array $data = []
    ) {
        $this->orderPayment = $checkoutSession->getLastRealOrder()->getPayment();
        parent::__construct($context, $data);
    }

    /**
     * @return OrderPaymentInterface
     */
    public function getInfo()
    {
        return $this->orderPayment;
    }

    /**
     * @return string
     */
    public function getBillerCode()
    {
        // The interface here is incorrect. The implementation allows passing through of a key.
        return $this->getInfo()->getAdditionalInformation("biller_code");
    }

    /**
     * @return string
     */
    public function getCustomerRef()
    {
        // The interface here is incorrect. The implementation allows passing through of a key.
        return $this->getInfo()->getAdditionalInformation("customer_ref");
    }

    /**
     * @return CheckoutSuccess
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();

        if ($this->getInfo()->getMethod() === PaymentMethod::METHOD_CODE) {
            $this->shouldOutput = true;
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->shouldOutput === true) {
            return parent::_toHtml();
        } else {
            return "";
        }
    }
}
