<?php
namespace TMFW\Support\Filters\Email;

use TMFW\Contracts\Filters\EmailTemplate;

class EventTemplate implements EmailTemplate
{
    private $email;

    public function template($type = 'new.user'){

        switch($type){
            default:
                $event = 'user.register';
                break;
            case 'password.changed':
                $event = 'user.password.changed';
                break;
            case 'profile.changed':
                $event = 'user.profile.updated';
                break;
            case 'new.complain':
                $event = 'complain.register';
                break;
            case 'update':
                $event = 'complaint.update.complainer';
                break;
            case 'forwarded':
                $event = 'complaint.assigned.department';
                break;
            case 'forward.failed':
                $event = 'complain.assigned.department.failed';
                break;
            case 'assign':
                $event = 'complaint.assigned.fieldworker';
                break;
            case 'update.department':
                $event = 'complaint.update.department.employees';
                break;
            case 'new.employee':
                $event = 'employee.registered';
                break;
            case 'notice.alert':
                $event = 'user.notice.alert';
                break;
            case 'update.status.validate':
                $event = 'complaint.status.changed.validate';
                break;
            case 'update.status.pending':
                $event = 'complaint.status.changed.pending';
                break;
            case 'update.status.cancelled':
                $event = 'complaint.status.changed.cancelled';
                break;
            case 'update.status.forwarded':
                $event = 'complaint.status.changed.forwarded';
                break;
            case 'update.status.assigned':
                $event = 'complaint.status.changed.assigned';
                break;
            case 'update.status.inprocess':
                $event = 'complaint.status.changed.inprocess';
                break;
            case 'update.status.reschedule':
                $event = 'complaint.status.changed.reschedule';
                break;
            case 'update.status.attended':
                $event = 'complaint.status.changed.attended';
                break;
            case 'update.status.delayed':
                $event = 'complaint.status.changed.delayed';
                break;
            case 'update.status.resolved':
                $event = 'complaint.status.changed.resolved';
                break;
        }
        $this->email = sys('model.email.event.template')->where('event_alias', '=', $event)->first(['subject', 'body']);
        return $this;
    }

    private function filterContent($content, $user = null, $complain = null, $employee = null, $department = null, $comment = null){
        $match = '/\[(.*)]/';
        preg_match_all($match, $content, $matches);
        $collections = collect($matches[1]);
        $filter = $collections->map(function($value, $key) use ($user, $complain, $employee, $department, $comment){
            $split = explode('.', $value);
            if($var = ${$split[0]}){
                unset($split[0]);
                foreach($split as $val) $var = $var->{$val};
                return $var;
            }
            else return false;
        });
        return str_replace($matches[0], $filter->all(), $content);
    }

    public function raw(){
        return $this->email;
    }

    public function filter($models = []){
        list($user, $complain, $employee, $department, $comment) = array_values(array_merge(['user' => null, 'complain' => null, 'employee' => null, 'department' => null, 'comment'], $models));
        $output = null;
        $email = $this->email->toArray();
        foreach($email as $key => $content)
            $output[$key] = $this->filterContent($content, $user, $complain, $employee, $department, $comment);
        return $output ? collect($output) : $output;
    }
}
