<?php

namespace App\Events\Admin;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;

class DoActionEvent
{
    use Dispatchable;

    public Request $request;
    public ?string $action;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->action = $request->get('action');
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function isAction($action): bool
    {
        return $this->getAction() === $action;
    }

    public function isTarget($key, $value): bool
    {
        return $this->request->get($key) === $value;
    }

    public function abortAction($action): void
    {
        abort_unless($this->isAction($action), 404);
    }

    public function abortTarget($key, $value): void
    {
        abort_unless($this->isTarget($key, $value), 404);
    }
}
