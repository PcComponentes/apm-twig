<?php
declare(strict_types=1);

namespace PcComponentes\ElasticAPM\Twig\Extension;

use Twig\Extension\ProfilerExtension;
use Twig\Profiler\Profile;
use ZoiloMora\ElasticAPM\ElasticApmTracer;

final class ApmExtension extends ProfilerExtension
{
    private const SPAN_TYPE = 'template';
    private const SPAN_SUBTYPE = 'twig';
    private const SPAN_ACTION = 'render';
    private const STACKTRACE_SKIP = 9;

    private ElasticApmTracer $elasticApmTracer;
    private array $events;

    public function __construct(Profile $profile, ElasticApmTracer $elasticApmTracer)
    {
        parent::__construct($profile);

        $this->elasticApmTracer = $elasticApmTracer;
        $this->events = [];
    }

    public function enter(Profile $profile)
    {
        if ($this->elasticApmTracer->active() && $profile->isTemplate()) {
            $key = $profile->getName();

            $this->events[$key] = $this->elasticApmTracer->startSpan(
                $this->getSpanName($profile),
                self::SPAN_TYPE,
                self::SPAN_SUBTYPE,
                self::SPAN_ACTION,
                null,
                self::STACKTRACE_SKIP
            );
        }

        parent::enter($profile);
    }

    public function leave(Profile $profile)
    {
        parent::leave($profile);

        if ($this->elasticApmTracer->active() && $profile->isTemplate()) {
            $key = $profile->getName();

            /** @var \ZoiloMora\ElasticAPM\Events\Span\Span $span */
            $span = $this->events[$key];
            $span->stop();

            unset($this->events[$key]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'elastic-apm';
    }

    private function getSpanName(Profile $profile): string
    {
        return \sprintf(
            'Twig Render: %s',
            $profile->getName()
        );
    }
}
