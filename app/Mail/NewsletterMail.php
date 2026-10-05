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
<body style="margin:0; padding:0; background-color:#08090e; -webkit-text-size-adjust:100%;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#08090e" style="background-color:#08090e; border-collapse:collapse;">
<tr>
<td align="center" style="padding:48px 16px;">
<!--[if mso]>
<table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0"><tr><td>
<![endif]-->
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#0d1017" style="width:100%; max-width:600px; background-color:#0d1017; border:1px solid #30323b; border-top:2px solid #3fe0ff; border-collapse:separate; border-spacing:0; box-shadow:0 0 44px #f0b42914;">

  <!-- Brand -->
  <tr>
    <td style="padding:24px 28px; border-bottom:1px solid #282b34;">
      <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;">
        <tr>
          <td width="36" height="36" align="center" valign="middle" bgcolor="#f0b429" style="width:36px; height:36px; background-color:#f0b429; font-family:'Courier New',Courier,monospace; font-weight:700; color:#0a0c11; font-size:17px; line-height:36px;">
            T
          </td>
          <td style="padding-left:14px; font-family:Arial,Helvetica,sans-serif; font-size:15px; font-weight:700; color:#f4f5f8;">
            TradeJournal
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- Accent -->
  <tr>
    <td style="padding:32px 28px 24px;">
      <table role="presentation" width="40" cellpadding="0" cellspacing="0" border="0" style="width:40px; border-collapse:collapse;">
        <tr><td height="3" bgcolor="#f0b429" style="height:3px; line-height:3px; font-size:0; background-color:#f0b429;">&nbsp;</td></tr>
      </table>
    </td>
  </tr>

  <!-- Subject -->
  <tr>
    <td style="padding:0 28px 20px;">
      <h1 style="margin:0; font-family:Arial,Helvetica,sans-serif; font-size:27px; line-height:1.25; font-weight:700; color:#f4f5f8; overflow-wrap:anywhere; word-wrap:break-word;">
        {$subject}
      </h1>
    </td>
  </tr>

  <!-- Body -->
  <tr>
    <td style="padding:0 28px 36px; font-family:Arial,Helvetica,sans-serif; font-size:15px; line-height:1.75; color:#c4c7cf; overflow-wrap:anywhere; word-wrap:break-word;">
      {$body}
    </td>
  </tr>

  <!-- Footer -->
  <tr>
    <td style="padding:22px 28px; border-top:1px solid #282b34; font-family:'Courier New',Courier,monospace; font-size:12px; line-height:1.65; color:#969ca8;">
      You're receiving this because you're subscribed to the TradeJournal newsletter.
    </td>
  </tr>

</table>
<!--[if mso]>
</td></tr></table>
<![endif]-->
</td>
</tr>
</table>
</body>
</html>
HTML;
    }
}