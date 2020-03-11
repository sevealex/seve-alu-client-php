<?php


namespace PayU\Payments\Gateways\PaymentsApi\Entities;


use PayU\Payments\Gateways\PaymentsApi\Entities\IdentityDocumentData;

class BillingData implements \JsonSerializable
{
    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $countryCode;

    /**
     * @var string
     */
    private $state;

    /**
     * @var string
     */
    private $companyName;

    /**
     * @var string
     */
    private $taxId;

    /**
     * @var string
     */
    private $addressLine1;

    /**
     * @var string
     */
    private $addressLine2;

    /**
     * @var string
     */
    private $zipCode;

    /**
     * @var IdentityDocumentData
     */
    private $identityDocument;

    /**
     * BillingData constructor.
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $phone
     * @param string $city
     * @param string $countryCode
     */
    public function __construct(
        $firstName,
        $lastName,
        $email,
        $phone,
        $city,
        $countryCode
    )
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phone = $phone;
        $this->city = $city;
        $this->countryCode = $countryCode;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @param string $companyName
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
    }

    /**
     * @param string $taxId
     */
    public function setTaxId($taxId)
    {
        $this->taxId = $taxId;
    }

    /**
     * @param string $addressLine1
     */
    public function setAddressLine1($addressLine1)
    {
        $this->addressLine1 = $addressLine1;
    }

    /**
     * @param string $addressLine2
     */
    public function setAddressLine2($addressLine2)
    {
        $this->addressLine2 = $addressLine2;
    }

    /**
     * @param string $zipCode
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    }

    /**
     * @param IdentityDocumentData $identityDocument
     */
    public function setIdentityDocument($identityDocument)
    {
        $this->identityDocument = $identityDocument;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @return string
     */
    public function getTaxId()
    {
        return $this->taxId;
    }

    /**
     * @return string
     */
    public function getAddressLine1()
    {
        return $this->addressLine1;
    }

    /**
     * @return string
     */
    public function getAddressLine2()
    {
        return $this->addressLine2;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @return IdentityDocumentData
     */
    public function getIdentityDocument()
    {
        return $this->identityDocument;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'city' => $this->city,
            'countryCode' => $this->countryCode,
            'state' => $this->state,
            'companyName' => $this->companyName,
            'taxId' => $this->taxId,
            'addressLine1' => $this->addressLine1,
            'addressLine2' => $this->addressLine2,
            'zipCode' => $this->zipCode,
            'identityDocument' => $this->identityDocument
        ];
    }
}