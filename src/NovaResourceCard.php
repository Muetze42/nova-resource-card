<?php

namespace NormanHuth\NovaResourceCard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ExpectedValues;
use Laravel\Nova\Card;
use Laravel\Nova\Resource;

class NovaResourceCard extends Card
{
    /**
     * The Resource instance.
     *
     * @var Resource
     */
    protected Resource $resource;

    /**
     * The Footer Link.
     *
     * @var array
     */
    protected array $footerLink = [
        'label'       => null,
        'url'         => null,
        'externalUrl' => null,
    ];

    /**
     * The classes for the Card component.
     *
     * @var array
     */
    protected array $cardClasses = [];

    /**
     * The classes for the ResourceCard component.
     *
     * @var array
     */
    protected array $resourceClasses = [
        'Heading' => '',
        'Card' => '',
        'IndexErrorDialog' => '',
        'IndexEmptyDialog' => '',
        'ResourceTable' => '',
    ];

    /**
     * Create a new element.
     *
     * @param Resource|string $resource
     * @return void
     */
    public function __construct($resource)
    {
        $this->resource = app($resource);
        $this->height = static::DYNAMIC_HEIGHT;
        $detailResourceName = Str::plural(Str::kebab(class_basename($this->resource::$model)));
        $this->withMeta([
            'resourceName'      => $this->resource::uriKey(),
            'detailResourceName' => $detailResourceName,
            'singularName'      => $this->resource::singularLabel(),
        ]);
        $this->footerLink['url'] = route('nova.pages.index', $detailResourceName);
        parent::__construct();
    }

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component(): string
    {
        return 'nova-resource-card';
    }

    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = 'full';

    /**
     * Set the width of the card.
     *
     * @param string $width
     * @return $this
     */
    public function width(
        #[ExpectedValues(values: ['full', '1/3', '1/2', '1/4', '2/3', '3/4'])]
        $width
    ): static {
        return parent::width($width);
    }

    /**
     * Determine if the filter or action should be available for the given request.
     *
     * @param Request $request
     * @return bool
     */
    public function authorizedToSee(Request $request): bool
    {
        return $this->seeCallback ? call_user_func($this->seeCallback, $request) :
            $this->authorizeToViewAny();
    }

    /**
     * Determine if the resource should be available for the given request.
     *
     * @return bool
     */
    public function authorizeToViewAny(): bool
    {
        if (is_null(Gate::getPolicyFor($this->resource::$model))) {
            return true;
        }

        return Gate::allows('viewAny', $this->resource::$model);
    }

    /**
     * Add Stylesheet classes to the Card component.
     *
     * @param string|array $classes
     * @return $this
     */
    public function addCardClasses(string|array $classes): static
    {
        $this->cardClasses = (array) $classes;

        return $this;
    }

    /**
     * Add Stylesheet classes to the Heading of the Resource component.
     *
     * @param string|array $classes
     * @return $this
     */
    public function addResourceHeadingClasses(string|array $classes): static
    {
        $this->resourceClasses['Heading'] = (array) $classes;

        return $this;
    }

    /**
     * Add Stylesheet classes to the Card of the Resource component.
     *
     * @param string|array $classes
     * @return $this
     */
    public function addResourceCardClasses(string|array $classes): static
    {
        $this->resourceClasses['Card'] = (array) $classes;

        return $this;
    }

    /**
     * Add Stylesheet classes to the IndexErrorDialog of the Resource component.
     *
     * @param string|array $classes
     * @return $this
     */
    public function addResourceIndexErrorDialogClasses(string|array $classes): static
    {
        $this->resourceClasses['IndexErrorDialog'] = (array) $classes;

        return $this;
    }

    /**
     * Add Stylesheet classes to the IndexEmptyDialog of the Resource component.
     *
     * @param string|array $classes
     * @return $this
     */
    public function addResourceIndexEmptyDialogClasses(string|array $classes): static
    {
        $this->resourceClasses['IndexEmptyDialog'] = (array) $classes;

        return $this;
    }

    /**
     * Add Stylesheet classes to the ResourceTable of the Resource component.
     *
     * @param string|array $classes
     * @return $this
     */
    public function addResourceResourceTableClasses(string|array $classes): static
    {
        $this->resourceClasses['ResourceTable'] = (array) $classes;

        return $this;
    }

    /**
     * Set Footer Link Label.
     *
     * @param string $label
     * @return $this
     */
    public function setFooterLinkLabel(string $label): static
    {
        $this->footerLink['label'] = $label;

        return $this;
    }

    /**
     * Set Footer Link Resource url.
     *
     * @param string|Resource $resource
     * @return $this
     */
    public function setFooterLinkResource(string|Resource $resource): static
    {
        $resource = Str::plural(Str::kebab(class_basename($resource::$model)));

        $this->footerLink['url'] = route('nova.pages.index', $resource);
        $this->footerLink['externalUrl'] = null;

        return $this;
    }

    /**
     * Set Footer Link external url.
     *
     * @param string $url
     * @return $this
     */
    public function setFooterLinkExternalUrl(string $url): static
    {
        $this->footerLink['url'] = null;
        $this->footerLink['externalUrl'] = $url;

        return $this;
    }

    /**
     * Set Detail Link Resource.
     *
     * @param string|Resource $resource
     * @return $this
     */
    public function setDetailLinkResource(string|Resource $resource): static
    {
        $this->withMeta([
            'detailResourceName' => Str::plural(Str::kebab(class_basename($resource::$model))),
        ]);

        return $this;
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $this->withMeta([
            'cardClasses'     => $this->cardClasses,
            'resourceClasses' => $this->resourceClasses,
            'footerLink'      => $this->footerLink,
        ]);
        return parent::jsonSerialize();
    }
}
