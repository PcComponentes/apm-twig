# Elastic APM for Twig

This library supports Span traces of [Twig](https://github.com/twigphp/Twig) renderings.

## Installation

1) Install via [composer](https://getcomposer.org/)

    ```shell script
    composer require pccomponentes/apm-twig
    ```

## Usage

In all cases, an already created instance of [ElasticApmTracer](https://github.com/zoilomora/elastic-apm-agent-php) is assumed.

### Service Container (Symfony)

```yaml
twig.extension.apm:
  class: PcComponentes\ElasticAPM\Twig\Extension\ApmExtension
  arguments:
    $profile: '@twig.profile'
    $elasticApmTracer: '@apm.tracer' # \ZoiloMora\ElasticAPM\ElasticApmTracer instance.
  public: false
  tags:
    - { name: twig.extension }
```

## License
Licensed under the [MIT license](http://opensource.org/licenses/MIT)

Read [LICENSE](LICENSE) for more information
