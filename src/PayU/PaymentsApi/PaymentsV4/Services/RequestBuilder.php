<?php


namespace PayU\PaymentsApi\PaymentsV4\Services;

use PayU\Alu\Request;
use PayU\PaymentsApi\PaymentsV4\Entities\AuthorizationData;
use PayU\PaymentsApi\PaymentsV4\Entities\AuthorizationRequest;
use PayU\PaymentsApi\PaymentsV4\Entities\BillingData;
use PayU\PaymentsApi\PaymentsV4\Entities\CardDetails;
use PayU\PaymentsApi\PaymentsV4\Entities\ClientData;
use PayU\PaymentsApi\PaymentsV4\Entities\DeliveryData;
use PayU\PaymentsApi\PaymentsV4\Entities\FxData;
use PayU\PaymentsApi\PaymentsV4\Entities\IdentityDocumentData;
use PayU\PaymentsApi\PaymentsV4\Entities\MerchantTokenData;
use PayU\PaymentsApi\PaymentsV4\Entities\ProductData;
use PayU\PaymentsApi\PaymentsV4\Entities\AirlineInfoData;
use PayU\PaymentsApi\PaymentsV4\Entities\FlightSegments;
use PayU\PaymentsApi\PaymentsV4\Entities\TravelAgency;

class RequestBuilder
{
    /**
     * @param Request $request
     * @return false|string
     */
    public function buildAuthorizationRequest($request)
    {
        $authorizationData = new AuthorizationData($request->getOrder()->getPayMethod());

        //HOSTED_PAGE in V3
        //$authorizationData->setUsePaymentPage($request->getOrder()->getHostedPage());

        $authorizationData->setInstallmentsNumber($request->getOrder()->getInstallmentsNumber());
        $authorizationData->setUseLoyaltyPoints($request->getOrder()->getUseLoyaltyPoints());
        $authorizationData->setLoyaltyPointsAmount($request->getOrder()->getLoyaltyPointsAmount());
        $authorizationData->setCampaignType($request->getOrder()->getCampaignType());
        /*
         *      applePayToken object
         */

        if ($request->getCard() !== null && $request->getCardToken() === null) {
            $cardDetails = new CardDetails(
                $request->getCard()->getCardNumber(),
                $request->getCard()->getCardExpirationMonth(),
                $request->getCard()->getCardExpirationYear()
            );

            $cardDetails->setCvv($request->getCard()->getCardCVV());
            $cardDetails->setOwner($request->getCard()->getCardOwnerName());

            if ($request->getCard()->hasTimeSpentTypingNumber()) {
                $cardDetails->setTimeSpentTypingNumber($request->getCard()->getTimeSpentTypingNumber());
            }

            if ($request->getCard()->hasTimeSpentTypingOwner()) {
                $cardDetails->setTimeSpentTypingOwner($request->getCard()->getTimeSpentTypingOwner());
            }

            $authorizationData->setCardDetails($cardDetails);
        }


        if ($request->getCard() === null && $request->getCardToken() !== null) {
            $merchantToken = new MerchantTokenData($request->getCardToken()->getToken());

            if ($request->getCardToken()->hasCvv()) {
                $merchantToken->setCvv($request->getCardToken()->getCvv());
            }

            if ($request->getCardToken()->hasOwner()) {
                $merchantToken->setOwner($request->getCardToken()->getOwner());
            }

            $authorizationData->setMerchantToken($merchantToken);
        }

        if ($request->getFx() !== null) {
            $fxData = new FxData(
                $request->getFx()->getAuthorizationCurrency(),
                $request->getFx()->getAuthorizationExchangeRate()
            );

            $authorizationData->setFx($fxData);
        }

        $billingData = new BillingData(
            $request->getBillingData()->getFirstName(),
            $request->getBillingData()->getLastName(),
            $request->getBillingData()->getEmail(),
            $request->getBillingData()->getPhoneNumber(),
            $request->getBillingData()->getCity(),
            $request->getBillingData()->getCountryCode()
        );

        $billingData->setState($request->getBillingData()->getState());
        $billingData->setCompanyName($request->getBillingData()->getCompany());
        $billingData->setTaxId($request->getBillingData()->getCompanyFiscalCode());
        $billingData->setAddressLine1($request->getBillingData()->getAddressLine1());
        $billingData->setAddressLine2($request->getBillingData()->getAddressLine2());
        $billingData->setZipCode($request->getBillingData()->getZipCode());

        if ($request->getBillingData()->getIdentityCardNumber() !== null ||
            $request->getBillingData()->getIdentityCardType() !== null
        ) {
            $identityDocumentData = new IdentityDocumentData();

            $identityDocumentData->setNumber($request->getBillingData()->getIdentityCardNumber());
            $identityDocumentData->setType($request->getBillingData()->getIdentityCardType());

            $billingData->setIdentityDocument($identityDocumentData);
        }


        $clientData = new ClientData($billingData);

        if ($request->getDeliveryData() !== null) {
            $deliveryData = new DeliveryData();

            $deliveryData->setFirstName($request->getDeliveryData()->getFirstName());
            $deliveryData->setLastName($request->getDeliveryData()->getLastName());
            $deliveryData->setPhone($request->getDeliveryData()->getPhoneNumber());
            $deliveryData->setAddressLine1($request->getDeliveryData()->getAddressLine1());
            $deliveryData->setAddressLine2($request->getDeliveryData()->getAddressLine2());
            $deliveryData->setZipCode($request->getDeliveryData()->getZipCode());
            $deliveryData->setCity($request->getDeliveryData()->getCity());
            $deliveryData->setState($request->getDeliveryData()->getState());
            $deliveryData->setCountryCode($request->getDeliveryData()->getCountryCode());
            $deliveryData->setEmail($request->getDeliveryData()->getEmail());

            $clientData->setDeliveryData($deliveryData);
        }

        if ($request->getUser() !== null) {
            $clientData->setIp($request->getUser()->getUserIPAddress());
            $clientData->setTime($request->getUser()->getClientTime());
            //"communicationLanguage"
        }

        $authorizationRequest = new AuthorizationRequest(
            $request->getOrder()->getOrderRef(),
            $request->getOrder()->getCurrency(),
            $request->getOrder()->getBackRef(),
            $authorizationData,
            $clientData,
            $this->getProductArray($request)
        );

        /*
         * no PosCode object in Request
        if (!empty($request->getMerchant())){
            $merchantData = new MerchantData($request->getMerchant());

            $authorizationRequest->setMerchant($merchantData);
        }
        */

//        if (airlineInfo){}
//        if (threeDSecure){}
//        if (storedCredentials){}

        return json_encode($authorizationRequest);
    }

    /**
     * @param Request $request
     * @return ProductData[]
     */
    private function getProductArray($request)
    {
        $cnt = 0;
        $productsArray = [];

        /**
         * @var Request $request
         * @var Product $product
         */
        foreach ($request->getOrder()->getProducts() as $product) {
            $productData = new ProductData(
                $product->getName(),
                $product->getCode(),
                $product->getPrice(),
                $product->getQuantity()
            );

            $productData->setAdditionalDetails($product->getInfo());
            $productData->setVat($product->getVAT());

            $productsArray[$cnt++] = $productData;
        }

        return $productsArray;
    }
}
