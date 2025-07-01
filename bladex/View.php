<?php

namespace Bladex;

class View
{
    private BladeRenderer $renderer;
    private string $view;
    private array $data = [];
    private array $sharedData = [];

    public function __construct(BladeRenderer $renderer, string $view)
    {
        $this->renderer = $renderer;
        $this->view = $view;
    }

    /**
     * Add data to this view instance
     */
    public function with(array|string $key, mixed $value = null): self
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * Add shared data (global for all views)
     */
    public function share(array|string $key, mixed $value = null): self
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->renderer->share($k, $v);
            }
        } else {
            $this->renderer->share($key, $value);
        }

        return $this;
    }

    /**
     * Render view as string
     */
    public function getHtml(): string
    {
        return $this->renderer->getHtml($this->view, $this->data);
    }

    /**
     * Render view as HttpResponse
     */
    public function getResponse(): \Bitrix\Main\HttpResponse
    {
        return $this->renderer->getResponse($this->view, $this->data);
    }

    /**
     * Magic method to render when used as string
     */
    public function __toString(): string
    {
        try {
            return $this->getHtml();
        } catch (\Exception $e) {
            return '';
        }
    }
}