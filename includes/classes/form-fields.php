<?php

if ( ! defined('ABSPATH')) exit;

class Customer 
{
    // Para facilitar, vou usar camelCase por conta do request
    private $name;
    private $emails;
    private $phones;
    private $document;
    private $documentType;
    private $personType;
    private $contactPreferenceType;
    private $addresses;

    // Tornando o construtor público
    public function __construct($name = NULL, $emails = NULL, $phones = [], $document = NULL,
    $documentType = NULL, $personType = NULL, $contactPreferenceType = NULL, $addresses = []) {
        $this->name = $name;
        $this->emails = $emails;
        $this->phones = $phones;
        $this->document = $document;
        $this->documentType = $documentType;
        $this->personType = $personType;
        $this->contactPreferenceType = $contactPreferenceType;
        $this->addresses = $addresses;
    }

    // Renomeando para toArray() para seguir a convenção
    public function toArray() {
        return [
            'name' => $this->name,
            'emails' => $this->emails,
            'phones' => $this->phones,
            'document' => $this->document,
            'documentType' => $this->documentType,
            'personType' => $this->personType,
            'contactPreferenceType' => $this->contactPreferenceType,
            'addresses' => $this->addresses,
        ];
    }
}

class Event
{
    private $companyId;
    private $eventGroup;
    private $eventType;
    private $source;
    private $media;
    private $comment;
    private $userId;
    private $originalEvent;
    private $leadInfo;

    // Tornando o construtor público
    public function __construct($companyId = NULL, $eventGroup = NULL, $eventType = NULL, 
    $source = NULL, $media = NULL, $comment = NULL, $userId = NULL, $originalEvent = NULL, $leadInfo = []) {
        $this->companyId = $companyId;
        $this->eventGroup = $eventGroup;
        $this->eventType = $eventType;
        $this->source = $source;
        $this->media = $media;
        $this->comment = $comment;
        $this->userId = $userId;
        $this->originalEvent = $originalEvent;
        $this->leadInfo = $leadInfo;
    }

    // Renomeando para toArray() para seguir a convenção
    public function toArray() {
        return [
            'companyId' => $this->companyId,
            'eventGroup' => $this->eventGroup,
            'eventType' => $this->eventType,
            'source' => $this->source,
            'media' => $this->media,
            'comment' => $this->comment,
            'userId' => $this->userId,
            'originalEvent' => $this->originalEvent,
            'leadInfo' => $this->leadInfo
        ];
    }
}

class AdditionalFields
{
    private $kind;
    private $value;

    public function __construct($kind, $value) {
        $this->kind = $kind;
        $this->value = $value;
    }

    public function toArray() {
        return [
            'kind' => $this->kind,
            'value' => $this->value
        ];
    }
}

class RequestPayload    
{
    private $customer;
    private $event;
    private $daysToUpdateOpenEvent;
    private $rules;
    private $additionalFields;

    public function __construct(Customer $customer, Event $event, $daysToUpdateOpenEvent, $rules, $additionalFields) {
        $this->customer = $customer;
        $this->event = $event;
        $this->daysToUpdateOpenEvent = $daysToUpdateOpenEvent;
        $this->rules = $rules;
        $this->additionalFields = $additionalFields;
    }

    public function toArray() {
        return [
            'customer' => $this->customer->toArray(),
            'event' => $this->event->toArray(),
            'daysToUpdateOpenEvent' => $this->daysToUpdateOpenEvent,
            'rules' => $this->rules,
            'additionalFields' => array_map(function($field) {
                return $field->toArray();
            }, $this->additionalFields) // Mapeia o array de AdditionalFields para array
        ];
    }
}
