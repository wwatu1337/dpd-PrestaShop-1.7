<?php

namespace Invertus\dpdBaltics\Validate\Carrier;

use DPDBaltics;
use Invertus\dpdBaltics\Repository\ProductRepository;
use Invertus\dpdBaltics\Repository\PudoRepository;
use PrestaShop\PrestaShop\Core\Foundation\IoC\Exception;

class PudoValidate
{

    /**
     * @var DPDBaltics
     */
    private $module;
    /**
     * @var PudoRepository
     */
    private $pudoRepository;
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(DPDBaltics $module, PudoRepository $pudoRepository, ProductRepository $productRepository)
    {
        $this->module = $module;
        $this->pudoRepository = $pudoRepository;
        $this->productRepository = $productRepository;
    }

    public function validatePickupPoints($cartId, $carrierReference)
    {
        if (!$this->productRepository->isProductPudo($carrierReference)) {
            return true;
        }
        $pudoId = $this->pudoRepository->getIdByCart($cartId);
        if (!$pudoId) {
            return false;
        }

        return true;
    }

    public function isPudoSelected($cartId, $carrierReference)
    {
        if (!$this->validatePickupPoints($cartId, $carrierReference)) {
            throw new Exception($this->module->l('Pudo point is missing, please select valid terminal point'));
        }
    }
}
