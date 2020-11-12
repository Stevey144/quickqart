<?php

namespace App\Mail;

use App\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Blade;

class GeneralMail extends Mailable
{
    use Queueable, SerializesModels;


    public $mailTemplate;
    public $mailData;
    public $mailSubject;
    public $data = [];
    public $recipient;

    /**
     * Create a new message instance.
     *
     * @param $template
     * @param array $mailData
     * @param bool $recipient
     * @param bool $mailSubject
     */
    public function __construct($template, $mailData = [], $recipient = false, $mailSubject = false)
    {
        $t = Message::query()->where('slug', $template)->first();
        if (!$t || !($tt = $template = $t->messageTemplates()->active()->first())) {
            return;
        }

        $this->mailSubject = $mailSubject ? $mailSubject : ($tt->subject . ' - Kolo');
        $this->mailTemplate = $tt->template;
        $this->mailData = $mailData;
        $this->recipient = $recipient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $default_email = config('mail.from.address');
        $default_name = config('mail.from.name');
        $address = config('app.address');

        if (!isset($this->mailData['date'])) {
            $this->mailData['date'] = date('Y');
        }

        $this->mailData = array_merge($this->mailData, [
            'app_name' => $default_name,
            'address' => $address,
            'subject' => $this->mailSubject
        ]);

        $template = $this->cCompileTemplate($this->mailTemplate, $this->mailData);


        $view = $this->from($default_email, $default_name)
            ->subject($this->mailData['subject'])
            ->view('emails.default', compact('template'));
        return $view;
    }

    public function cCompileTemplate($blade, array $data)
    {
        $php = Blade::compileString($blade);
        return $this->cRender($php, $data);
    }

    /**
     * @param $__php
     * @param $__data
     * @return false|string
     * @throws \Exception
     */
    function cRender($__php, $__data)
    {
        $obLevel = ob_get_level();
        ob_start();
        extract($__data, EXTR_SKIP);
        try {
            eval('?' . '>' . $__php);
        } catch (\Exception $e) {
            while (ob_get_level() > $obLevel)
                ob_end_clean();
            throw $e;
        } catch (\Throwable $e) {
            while (ob_get_level() > $obLevel)
                ob_end_clean();
            throw new \Exception($e);
        }
        return ob_get_clean();
    }
}
