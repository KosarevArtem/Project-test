<?php
declare(strict_types=1);

namespace taskforce\logic;

use taskforce\logic\actions\Abstract_Action;
use taskforce\logic\actions\Cancel_Action;
use taskforce\logic\actions\Complete_Action;
use taskforce\logic\actions\Deny_Action;
use taskforce\logic\actions\Response_Action;
use taskforce\exceptions\status_action_exception;

class Available_Actions 
{
    const STATUS_NEW = 'Новое';
    const STATUS_CANCEL = 'Отменено';
    const STATUS_IN_PROGRESS = 'В работе';
    const STATUS_COMPLETE = 'Выполнено';
    const STATUS_EXPIRED = 'Провалено';

    const ACTION_RESPONSE = 'act_response';
    const ACTION_CANCEL = 'act_cancel';
    const ACTION_DENY = 'act_deny';
    const ACTION_COMPLETE = 'act_complete';

    const ROLE_PERFORMER = 'performer';
    const ROLE_CLIENT = 'customer';

    private ?int $performer_id;
    private ?int $cliend_id;

    public ?string $status = null;

    public function __construct(string $status, int $client_id, ?int $performer_id = null)
    {
        if (!in_array($status, $this->get_status_map())) 
        {
            throw new status_action_exception("Передан неверный статус (конструктор)");
        };
        $this->set_status($status);

        $this->performer_id = $performer_id;
        $this->client_id = $client_id;
    }

    public function get_available_actions(string $role, int $id): array
    {
        $this->check_role($role);
        $status_actions = $this->status_allowed_actions()[$this->status];
        $role_actions = $this->role_allowed_actions()[$role];

        $allowed_actions = array_intersect($status_actions, $role_actions);

        $allowed_actions = array_filter($allowed_actions, function ($action) use ($id) {
            return $action::check_rights($id, $this->performer_id, $this->client_id);
        });

        return array_values($allowed_actions);
    }

    private function get_status_map(): array
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_CANCEL => 'Отменено',
            self::STATUS_IN_PROGRESS => 'В работе',
            self::STATUS_COMPLETE => 'Выполнено',
            self::STATUS_EXPIRED => 'Провалено',
        ];
    }

    public function check_role(string $role): void
    {
        $available_roles = 
        [
            self::ROLE_PERFORMER,
            self::ROLE_CLIENT
        ];

        if (!in_array($role, $available_roles)) {
            throw new status_action_exception("Неизвестная роль: $role");
        }
    }

    
    public function get_next_status(Abstract_Action $action): ?string
    {
        $map = [
            Complete_Action::class => self::STATUS_COMPLETE,
            Cancel_Action::class => self::STATUS_CANCEL,
            Deny_action::class => self::STATUS_CANCEL,
            Response_Action::class => null,
            Abstract_Action::class => null
        ];

        if (!in_array($action, $map)) 
        {
            throw new status_action_exception("Передано неверное действие (get_next_status)");
        };

        return $map[get_class($action)];
    }

    
    public function set_status(string $status): void
    {
        $avialable_statuses = [
            self::STATUS_NEW, 
            self::STATUS_CANCEL, 
            self::STATUS_IN_PROGRESS, 
            self::STATUS_COMPLETE, 
            self::STATUS_EXPIRED
        ];

        if (in_array($status, $avialable_statuses)) {
            $this->status = $status;
        } else {
            throw new status_action_exception("Передан неверный статус (set_status)");
        }
    }


    private function status_allowed_actions(): array
    {
        $map = [
            self::STATUS_NEW => [Cancel_Action::class, Response_Action::class],
            self::STATUS_IN_PROGRESS => [Deny_Action::class, Complete_Action::class],
        ];

        return $map ?? [];
    }

    private function role_allowed_actions(): array
    {
        $map = [
            self::ROLE_CLIENT => [Cancel_Action::class, Complete_Action::class],
            self::ROLE_PERFORMER => [Response_Action::class, Deny_Action::class],
        ];

        return $map ?? [];
    }
}