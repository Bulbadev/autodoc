<?php

namespace Bulbadev\Autodoc\Clients;

use Bulbadev\Autodoc\Collectors\Collector;
use Bulbadev\Autodoc\Elements\Document;
use Bulbadev\Autodoc\Elements\Endpoint;
use Bulbadev\Autodoc\Elements\Parameter;
use Bulbadev\Autodoc\Elements\Response;
use Bulbadev\Autodoc\Helpers\Helper;
use Bulbadev\Autodoc\Strategies\BuildStrategy;

class Swagger303
{

    use Helper;

    protected array $documentArray = [];

    public function create(BuildStrategy $strategy): string
    {
        $this->createTemplate();
        $this->fillInfo($strategy->document);
        $this->fillServers($strategy->document);
        $this->fillEndpoints($strategy->document);
        if ($oldDocumentArray = app(Collector::class)->getPreviousDocument()) {
            $this->documentArray['paths'] = $strategy->mergeForSwagger303(
                $oldDocumentArray['paths'],
                $this->documentArray['paths']
            );
        }

        ksort($this->documentArray['paths']);

        return json_encode($this->documentArray, JSON_PRETTY_PRINT);
    }

    protected function buildParameters(Endpoint $endpoint): array
    {
        $parameters = [];

        /** @var Parameter $parameter */
        foreach ($endpoint->parametersBag->parameters as $parameter) {
            $parameters[] = [
                'name'        => $parameter->getName(),
                'in'          => $parameter->getIn(),
                'description' => $parameter->getDescription(),
                'required'    => $parameter->isRequired(),
                'schema'      => [
                    'type'    => $parameter->getType(),
                    'pattern' => $parameter->rules->getPattern(),
                    'enum'    => $parameter->rules->getIn(),
                    'default' => $parameter->rules->getIn()[0] ?? null,
                ],
            ];
        }

        return $this->arrayFilterNullRecursive($parameters);
    }

    protected function buildResponses(Endpoint $endpoint): array
    {
        $responses = [];

        /** @var Response $response */
        foreach ($endpoint->responseBag->responses as $response) {
            $newResponse = [
                $response->getCode() => [
                    'description' => $response->getDescription(),
                    'content'     => [
                        $response->getContentType() => [
                            'schema' => [
                                'example' => $response->getExample(),
                            ]
                        ]
                    ]
                ],
            ];

            if (empty(key($newResponse[$response->getCode()]['content']))) {
                unset($newResponse[$response->getCode()]['content']);
            }

            $responses += $newResponse;
        }

        ksort($responses);

        return $responses;
    }

    protected function createTemplate(): void
    {
        $this->documentArray = [
            'openapi'  => '3.0.3',
            'info'     => [],
            'servers'  => [],
            'security' => [],
            'paths'    => [],
        ];
    }

    protected function fillServers(Document $document): void
    {
        $this->documentArray['servers'] = $document->api->getServers();
    }

    protected function fillEndpoints(Document $document): void
    {
        /** @var Endpoint $endpoint */
        foreach ($document->endpoints as $endpoint) {
            $this->documentArray['paths'] = array_merge_recursive(
                $this->documentArray['paths'],
                [
                    $endpoint->getUriTemplate() =>
                        [
                            strtolower($endpoint->getMethod()) => [
                                'tags'        => $endpoint->getTags(),
                                'description' => $endpoint->getDescription(),
                                'parameters'  => $this->buildParameters($endpoint),
                                'responses'   => $this->buildResponses($endpoint),
                            ],
                        ],
                ]
            );
        }
    }

    protected function fillInfo(Document $document): void
    {
        $this->documentArray['info'] = $this->arrayFilterEmptyArrayRecursive(
            $this->arrayFilterNullRecursive(
                [
                    'title'          => $document->getInfoTitle(),
                    'description'    => $document->getInfoDescription(),
                    'termsOfService' => $document->getTermsOfService(),
                    'contact'        => [
                        'name'  => $document->getContactName(),
                        'url'   => $document->getContactUrl(),
                        'email' => $document->getContactEmail(),
                    ],
                    'license'        => [
                        'name' => $document->getLicenseName(),
                        'url'  => $document->getLicenseUrl(),
                    ],
                    'version'        => $document->getVersion(),
                ]
            )
        );
    }
}