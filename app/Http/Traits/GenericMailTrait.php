<?php


namespace App\Http\Traits;


use App\MessageTemplate;
use Illuminate\Support\Facades\Blade;

trait GenericMailTrait
{
    public $mailTemplate;
    public $mailData;
    public $mailSubject;

    public function getView($slug, $subject = '', $body = '') {
        $this->mailData['year'] = date('Y');
        $default_email = 'noreply@abc.com';
        $default_name = 'ABC Ltd';
        $reply_to_address = 'noreply@abc.com';

        $template = MessageTemplate::query()->where('slug', $slug)->first();

        if ($template) {
            $this->mailSubject = $template->subject;
            $this->mailTemplate = $template->template;
        } else {
            $this->mailSubject = $subject;
            $this->mailTemplate = $body;
        }

        $mail_body = $this->compileTemplate($this->mailTemplate, $this->mailData);

        $view = $this->from($default_email, $default_name)
            ->subject($this->mailSubject)
            ->view('mails.default', compact('mail_body'));
        return $view;
    }

    public function compileTemplate($blade, array $data) {
        $php = Blade::compileString($blade);
        return $this->customRender($php, $data);
    }

    /**
     * @param $__php
     * @param $__data
     * @return false|string
     * @throws \Exception
     */
    function customRender($__php, $__data)
    {
        $obLevel = ob_get_level();
        ob_start();
        extract($__data, EXTR_SKIP);
        try {
            eval('?' . '>' . $__php);
        } catch (\Exception $e) {
            while (ob_get_level() > $obLevel) ob_end_clean();
            throw $e;
        } catch (\Throwable $e) {
            while (ob_get_level() > $obLevel) ob_end_clean();
            throw new \Exception($e);
        }
        return ob_get_clean();
    }
}
