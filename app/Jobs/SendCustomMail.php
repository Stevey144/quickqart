<?php

namespace App\Jobs;

use App\Message;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Blade;
use Throwable;

class SendCustomMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var bool
     */
    private $recipient;
    private $mailData;
    private $mailTemplate;
    private $mailSubject;

    /**
     * Create a new job instance.
     *
     * @param $template
     * @param $mailData
     * @param $recipient
     * @param bool $mailSubject
     * @return void
     */
    public function __construct($template, $mailData, $recipient, $mailSubject = false)
    {
        $t = Message::query()->where('slug', $template)->first();
        if (!$t || !($tt = $template = $t->messageTemplates()->active()->first())) {
            return;
        }

        $this->mailSubject = $mailSubject ? $mailSubject : $tt->subject;
        $this->mailTemplate = $tt->template;
        $this->mailData = $mailData;
        $this->recipient = $recipient;

    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws Exception
     */
    public function handle()
    {
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

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: " . env('MAIL_FROM_NAME') . '<'. env('MAIL_FROM_ADDRESS') . '>' . "\r\n";

        mail($this->recipient, $this->mailData['subject'], $template, $headers);
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
     * @throws Exception
     */
    function cRender($__php, $__data)
    {
        $obLevel = ob_get_level();
        ob_start();
        extract($__data, EXTR_SKIP);
        try {
            eval('?' . '>' . $__php);
        } catch (Exception $e) {
            while (ob_get_level() > $obLevel)
                ob_end_clean();
            throw $e;
        } catch (Throwable $e) {
            while (ob_get_level() > $obLevel)
                ob_end_clean();
            throw new Exception($e);
        }
        return ob_get_clean();


    }
}
