<?php
namespace app\helpers;

use app\models\Tasks;
use app\models\Users;
use taskforce\exceptions\status_action_exception;
use taskforce\logic\actions\Cancel_Action;
use taskforce\logic\actions\Complete_Action;
use taskforce\logic\actions\Deny_Action;
use taskforce\logic\actions\Response_Action;
use taskforce\logic\Available_Actions;
use yii\helpers\Html;
use yii\helpers\Url;

class UIHelper
{

    public static function showStarRating($value, $size = 'small', $starsCount = 5, $active = false)
    {
        $stars = '';

        for ($i = 1; $i <= $starsCount; $i++) {
            $className = $i <= $value ? 'fill-star' : '';
            $stars .= Html::tag('span', '&nbsp;', ['class' => $className]);
        }

        $className = 'stars-rating ' . $size;

        if ($active) {
            $className .= ' active-stars';
        }

        $result = Html::tag('div', $stars, ['class' => $className]);

        return $result;
    }

    public static function getActionButtons(Tasks $task, Users $user)
    {
        $buttons = [];

        $colorsMap = [
            Cancel_Action::getInternalName()   => 'orange',
            Response_Action::getInternalName() => 'blue',
            Deny_Action::getInternalName()     => 'pink',
            Complete_Action::getInternalName() => 'yellow'
        ];

        $roleName = $user->is_contractor ? Available_Actions::ROLE_PERFORMER : Available_Actions::ROLE_CLIENT;

        try {
            $availableActionsManger = new Available_Actions($task->status->slug, $task->performer_id, $task->client_id);
            $actions = $availableActionsManger->getAvailableActions($roleName, $user->id);

            foreach ($actions as $action) {
                $color = $colorsMap[$action::getInternalName()];
                $label = $action::getLabel();

                $options = [
                    'data-action' => $action::getInternalName(),
                    'class' => 'button action-btn button--' . $color
                ];

                if ($action::getInternalName() === 'act_cancel') {
                    $options['href'] = Url::to(['tasks/cancel', 'id' => $task->id]);
                }

                $btn = Html::tag('a', $label, $options);

                $buttons[] = $btn;
            }
        } catch (status_action_exception $e) {
            error_log($e->getMessage());
        }

        return $buttons;
    }

    public static function getMyTasksMenu($isContractor)
    {
        $items = [];

        if ($isContractor) {
            $items[] = ['label' => 'В процессе', 'url' => ['tasks/my', 'status' => 'in_progress']];
            $items[] = ['label' => 'Просрочено', 'url' => ['tasks/my', 'status' => 'expired']];
            $items[] = ['label' => 'Закрытые', 'url' => ['tasks/my', 'status' => 'close']];
        }
        else {
            $items[] = ['label' => 'Новые', 'url' => ['tasks/my', 'status' => 'new']];
            $items[] = ['label' => 'В процессе', 'url' => ['tasks/my', 'status' => 'in_progress']];
            $items[] = ['label' => 'Закрытые', 'url' => ['tasks/my', 'status' => 'close']];
        }

        return $items;
    }

}