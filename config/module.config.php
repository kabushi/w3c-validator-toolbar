<?php
use W3cValidatorToolbar\Controller\W3cValidatorController;
use W3cValidatorToolbar\Controller\W3cValidatorControllerFactory;
use W3cValidatorToolbar\Service\W3cHtmlServiceFactory;
use W3cValidatorToolbar\Service\W3cCssServiceFactory;
/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */
return [
    'w3c-validator-module' => [
        "css" => [
            "exclude_files" => [
                'bootstrap-theme.unil.css',
                'jquery-ui-1.10.3.custom.css',
                'jquery-ui-1.10.3.theme.css',
                'jquery.tree.min.css'
            ]
        ]
    ],
    'controllers' => [
        'factories' => [
            W3cValidatorController::class => W3cValidatorControllerFactory::class,
        ]
    ],
    'service_manager' => [
        'invokables' => [
            'w3c.toolbar' => 'W3cValidatorToolbar\Collector\W3cValidatorCollector',
            
        ],
        'factories' => [
            'W3cHtml' => W3cHtmlServiceFactory::class,
            'W3cCss' => W3cCssServiceFactory::class,
        ]
    ],
    'view_manager' => [
        'template_map' => [
            'zend-developer-tools/toolbar/w3c-validator-data'
                => __DIR__ . '/../view/zend-developer-tools/toolbar/w3c-validator-data.phtml',
            'w3c-validator-toolbar/w3c-validator/ressource'
                =>  __DIR__ . '/../view/w3c-validator-toolbar/w3c-validator/ressource.phtml',
        ],
    ],
    'zenddevelopertools' => [
        'profiler' => [
            'collectors' => [
                'w3c.toolbar' => 'w3c.toolbar',
            ],
        ],
        'toolbar' => [
            'entries' => [
                'w3c.toolbar' => 'zend-developer-tools/toolbar/w3c-validator-data',
            ],
        ],
    ],
    'router'        => [
        'routes' => [
            'w3cvalidator' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/w3cvalidator',
                    'defaults' => [
                        'controller' => W3cValidatorController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'ajax' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'       => '/ajax',
                            'defaults'    => [
                                'controller' => W3cValidatorController::class,
                                'action'           => 'ajax',
                            ],
                        ]
                    ],
                    'res' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/res/[:url]',
                            'constraints' => [
                                'url' => '[a-zA-Z0-9\._-]+',
                            ],
                            'defaults'    => [
                                'controller' => W3cValidatorController::class,
                                'action'           => 'ressource'
                            ],
                        ]
                    ],
                ]
            ]
        ]
    ]    
];
