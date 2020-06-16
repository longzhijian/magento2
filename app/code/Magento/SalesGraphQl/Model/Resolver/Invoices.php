<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SalesGraphQl\Model\Resolver;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Sales\Model\Order;
use Magento\Sales\Api\Data\InvoiceInterface as Invoice;

/**
 * Resolver for Invoice
 */
class Invoices implements ResolverInterface
{
    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if (!isset($value['model']) || !($value['model'] instanceof Order)) {
            throw new LocalizedException(__('"model" value should be specified'));
        }

        /** @var Order $orderModel */
        $orderModel = $value['model'];
        $invoices = [];
        /** @var Invoice $invoice */
        foreach ($orderModel->getInvoiceCollection() as $invoice) {
            $invoices[] = [
                'id' => base64_encode($invoice->getEntityId()),
                'number' => $invoice['increment_id'],
                'model' => $invoice,
                'order' => $orderModel
            ];
        }
        return $invoices;
    }
}
