<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $newsletterSubject;
    public string $bodyHtml;

    public function __construct(string $subject, string $bodyHtml)
    {
        $this->newsletterSubject = $subject;
        $this->bodyHtml = $bodyHtml;
    }

    public function build(): self
    {
        return $this
            ->subject($this->newsletterSubject)
            ->html($this->renderHtml());
    }

    /**
     * Renders the full email as a table-based HTML string (no Blade
     * view needed). Table layout + inline styles because most email
     * clients (Outlook especially) don't reliably render flex/grid.
     */
    private function renderHtml(): string
    {
        $subject = e($this->newsletterSubject);
        $body = $this->bodyHtml; // raw HTML from the WYSIWYG editor, intentionally not escaped

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$subject}</title>
</head>
<body style="margin:0; padding:0; background-color:#0d0f14; -webkit-text-size-adjust:100%;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#0d0f14; padding:32px 16px;">
<tr>
<td align="center">
<table role="presentation" width="100%" style="max-width:600px;" cellpadding="0" cellspacing="0">

  <!-- Brand -->
  <tr>
    <td style="padding:0 4px 20px;">
      <table role="presentation" cellpadding="0" cellspacing="0">
        <tr>
          <td style="width:30px; height:30px; border-radius:8px; background-color:#f0b429; text-align:center; vertical-align:middle; font-family:Georgia,'Times New Roman',serif; font-weight:700; color:#0d0f14; font-size:14px;">
            T
          </td>
          <td style="padding-left:10px; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif; font-size:13px; font-weight:700; color:#e2e4e9; letter-spacing:0.02em;">
            TradeJournal
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- Card -->
  <tr>
    <td style="background-color:#ffffff; border-radius:14px;">
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0">

        <!-- Accent bar -->
        <tr>
          <td style="height:3px; background-color:#f0b429; border-radius:14px 14px 0 0;"></td>
        </tr>

        <!-- Subject -->
        <tr>
          <td style="padding:28px 32px 8px;">
            <h1 style="margin:0; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif; font-size:20px; font-weight:700; color:#15171c; letter-spacing:-0.01em;">
              {$subject}
            </h1>
          </td>
        </tr>

        <!-- Body -->
        <tr>
          <td style="padding:12px 32px 32px; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif; font-size:14.5px; line-height:1.7; color:#2a2d34;">
            {$body}
          </td>
        </tr>

      </table>
    </td>
  </tr>

  <!-- Footer -->
  <tr>
    <td style="padding:20px 8px 0; text-align:center; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif; font-size:11px; color:#6c7178;">
      You're receiving this because you're subscribed to the TradeJournal newsletter.
    </td>
  </tr>

</table>
</td>
</tr>
</table>
</body>
</html>
HTML;
    }
}