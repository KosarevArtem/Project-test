<?php

namespace taskforce\logic\actions;

abstract class Abstract_Action
{
    abstract public static function get_label(): string;

    abstract public static function get_internal_name(): string;

    abstract public static function check_rights(int $user_id, ?int $performer_id, ?int $client_id): bool;
};