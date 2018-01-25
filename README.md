# Symfony API Data helpers

## Configuration

```yaml
# config/services.yaml

services:
    ## With autowire and autoconfigure
    _defaults:
        autowire: true
        autoconfigure: true
        public: false 
    ...

    Jochlain\API\:
        resource: '../vendor/jochlain/api/src'
        exclude: '../vendor/jochlain/api/src/{Annotation,Controller,Exception,Form,Mapping,Response}'
        public: true

    Jochlain\API\Controller\:
        resource: '../vendor/jochlain/api/src/Controller'
        tags: ['controller.service_arguments']
        public: true

    Jochlain\API\Form\:
        resource: '../vendor/jochlain/api/src/Form'
        exclude: '../vendor/jochlain/api/src/Form/{Type}'
        public: true

    Jochlain\API\Form\Type\:
        resource: '../vendor/jochlain/api/src/Form/Type'
        tags: [{ name: "form.type" }]
        public: true

    ## Without autowire and autoconfigure
    Jochlain\API\Form\Builder:
        arguments: ["@form.factory"]

    Jochlain\API\Form\Encoder: ~

    Jochlain\API\Form\Type\FormType:
        arguments: ["Jochlain\\API\\Form\\Parser"]
        tags: [{ name: "form.type" }]

    Jochlain\API\Manager\CatalogManager:
        arguments: 
            - "@doctrine.orm.entity_manager"
            - "@form.factory"
            - "@request_stack"
            - "@Jochlain\\API\\Parser\\TableParser"

    Jochlain\API\Manager\CreateManager:
        arguments: 
            - "@doctrine.orm.entity_manager"
            - "@form.factory"
            - "@request_stack"

    Jochlain\API\Manager\DeleteManager:
        arguments: 
            - "@doctrine.orm.entity_manager"
            - "@form.factory"
            - "@request_stack"

    Jochlain\API\Manager\IndexManager:
        arguments: 
            - "@doctrine.orm.entity_manager"
            - "@request_stack"
            - "@Jochlain\\API\\Manager\\CatalogManager"

    Jochlain\API\Manager\ReadManager:
        arguments: 
            - "@doctrine.orm.entity_manager"
            - "@request_stack"

    Jochlain\API\Manager\UpdateManager:
        arguments: 
            - "@doctrine.orm.entity_manager"
            - "@form.factory"
            - "@request_stack"

    Jochlain\API\Parser\FormParser:
        arguments:
            - "@doctrine.orm.entity_manager"
            - "@security.token_storage"

    Jochlain\API\Parser\TableParser:
        arguments: ["@doctrine.orm.entity_manager"]
```