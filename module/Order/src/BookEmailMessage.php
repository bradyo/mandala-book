<?php
namespace Mandala\OrderModule;

use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Mime;

class BookEmailMessage extends Message
{
    public function __construct(Order $order, $pdfPath)
    {
        $bodyPart = new MimePart(
            '<p>Enjoy your book from <a href="https://www.mandalabook.com">MandalaBook.com</a>!</p>'
            . '<p>- The MandalaBook Team</p>');
        $bodyPart->type = 'text/html';

        $attachmentPart = new MimePart(fopen($pdfPath, 'r'));
        $attachmentPart->type = 'application/pdf';
        $attachmentPart->encoding = Mime::ENCODING_BASE64;
        $attachmentPart->disposition = Mime::DISPOSITION_ATTACHMENT;
        $attachmentPart->filename = 'mandala-book-' . $order->confirmationCode . '.pdf';

        $body = new MimeMessage();
        $body->setParts(array($bodyPart, $attachmentPart));

        $this->setEncoding('utf-8');
        $this->setTo($order->recipientEmail);
        $this->setReplyTo('support@mandalabook.com');
        $this->setBcc('admin@mandalabook.com');
        $this->setFrom('support@mandalabook.com', 'MandalaBook.com');
        $this->setSubject('Your Mandala Book - Order #' . strtoupper($order->confirmationCode));
        $this->setBody($body);
    }
} 