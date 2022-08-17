<?php

namespace taskforce\logic\actions;

class Response_Action extends Abstract_Action
{
    public static function get_label(): string
    {
        return "Откликнуться";
    }

    public static function get_internal_name(): string
    {
        return "act_response";
    }

    public static function check_rights(int $user_id, ?int $performer_id, ?int $client_id): bool
    {
        return $user_id !== $performer_id;
    }
}