<?php

use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;

function generate_email_template($template, $email = "")
{
    $template = EmailTemplate::where('template_key', $template)->first()->toArray();
    $template_content = "";
    if (!empty($template)) {
        $email_variables = isset($template['email_variables']) ? json_decode($template['email_variables'], true) : [];
        if (!empty($email_variables)) {
            $template_content = "";
            foreach ($email_variables as $key => $value) {
                $label = isset($value['label']) ? $value['label'] : "";
                $name = isset($value['name']) ? $value['name'] : "";
                $references = isset($value['references']) ? $value['references'] : "";
                $where = isset($value['where']) ? $value['where'] : null;

                $db_data = DB::table($references)->select($name);
                if ($email != "") {
                    $db_data = $db_data->where('email', $email);
                }
                if (null != $where) {
                    foreach ($where as $k => $v) {
                        $db_data = $db_data->where($k, $v);
                    }
                }
                $db_data = (array) $db_data->first();
                if (isset($db_data[$name]) && $key == 0) {
                    $template_content = str_replace($label, $db_data[$name], $template['content']);
                } else {
                    $template_content = str_replace($label, $db_data[$name], $template_content);
                }
            }
        } else {
            $template_content = $template['content'];
        }
    }
    return $template_content;
}


function send_email($template, $email, $where = [])
{
    $template_data = EmailTemplate::where('template_key', $template)->first()->toArray();
    if (!empty($template_data)) {
        $subject = isset($template_data['subject']) ? $template_data['subject'] : "";
        $template = generate_email_template($template, $email);
        try {
            Mail::send([], [], function ($message) use ($email, $subject, $template) {
                $message->to($email)
                    ->subject($subject)
                    ->html($template);
            });
            return true;
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Something Went Wrong"
            ]);
        }
    }
}