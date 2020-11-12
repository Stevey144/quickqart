<?php

use App\Message;
use App\MessageTemplate;
use App\MessageVariable;
use App\Variable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class MessageTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //get the messages
        $messages = $this->getArrayOfMessages();
        //get the main base for all message templates

        $templateBase = '{{$template}}';

        foreach ($messages as $m) {
            //generate slug like example-slug
            $slug = strtolower(Str::slug($m['name']));
            $m['slug'] = $slug;
            //assign the subject for the message
            $subject =  Arr::pull($m, 'subject');
            //assign the message variables
            $var =  Arr::pull($m, 'variables');
            $template = Arr::pull($m, 'template');
            //create and persist a message
            $mesObj = Message::query()->updateOrCreate(['slug' => $slug], $m);

            foreach ($var as $v) {
                $name = str_replace('_', ' ', $v);
                //create and persist a variable
                $vObj = Variable::query()->firstOrCreate(['name' => $name, 'placeholder' => '{{$' . $v . '}}']);
                //attach the variable to a message
                MessageVariable::query()->firstOrCreate([
                    'message_id' => $mesObj->id,
                    'variable_id' => $vObj->id
                ]);
            }

            //create and persist message template, and associate it with a message
            MessageTemplate::query()->updateOrCreate(
                [
                    'message_id' => $mesObj->id,
                    'version' => '1.0'
                ],
                [
                    'template' => $this->buildMessageTemplateWithBase($template, $templateBase),
                    'subject' => $subject,
                    'parent_id' => null,
                    'is_active' => true
                ]
            );
        }
    }
    protected function getArrayOfMessages()
    {
        return [
            [
                'name' => 'User Registration',
                'description' => 'Message to users when they register successful',
                'variables' => ['link', 'name'],
                'subject' => 'Account Confirmation',
                'template' => 'Hi {{$name}},<br><br>Please follow <a href="{{$link}}">this link</a> or paste this link: {{$link}} in a browser to complete and activate your account.'
            ],
            [
                'name' => 'Registration Notification',
                'description' => 'Message to administrator when users register',
                'variables' => ['name', 'email', 'date', 'link'],
                'subject' => 'Notification of Registration',
                'template' => 'Admin,<br><br>A new user {{$name}} signed up on {{$date}} by {{$name}} ({{$email}}). <a href="{{$link}}">Click here for more details.</a>'
            ],
            [
                'name' => 'Change Password',
                'description' => 'Email with password reset link sent to users',
                'variables' => ['name', 'link'],
                'subject' => 'Password reset',
                'template' => '{{$name}},<br><br>You (or someone else using this email) has requested a password reset for your account. Click <a href="{{$link}}">this link</a> or visit this url: {{$link}} to complete the request.<br><br>If you did not initiate this request, please ignore this email and your password will remain unchanged.<br>Thanks.'
            ],
            [
                'name' => 'Notification',
                'description' => 'General Notification',
                'variables' => ['link', 'detail'],
                'subject' => 'Notification - QuickQart',
                'template' => '{!! $detail !!}'
            ],
        ];
    }

    protected function buildMessageTemplateWithBase($template, $base)
    {
        return str_replace('{{$template}}', $template, $base);
    }
}
