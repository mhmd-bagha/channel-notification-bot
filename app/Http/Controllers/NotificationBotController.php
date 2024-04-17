<?php

namespace App\Http\Controllers;

use App\Contract\NotificationInterface;
use App\Utilities\ResponseUtility;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;

class NotificationBotController extends Controller implements NotificationInterface
{
    protected array $channelIds = [
        '-1001994871871'
    ];

    public function __construct(protected Api $telegram)
    {
    }

    public function sendMessageToChannel(Request $request): JsonResponse
    {
        $response = new ResponseUtility();
        $codalBaseUrl = 'https://www.codal.ir';

        try {
            $codalData = $request->get('codalData');

            $pdfUrl = $codalBaseUrl . '/' . $codalData['PdfUrl'];
            $attachmentUrl = $codalBaseUrl . '/' . $codalData['AttachmentUrl'];
            $excelUrl = $codalBaseUrl . '/' . $codalData['ExcelUrl'];


            $text = 'شرکت: ' . $codalData['CompanyName'] . PHP_EOL . $codalData['Title'];

            $replyMarkup = Keyboard::make()
                ->inline()
                ->row([
                    Keyboard::inlineButton([
                        'text' => 'اطلاعیه در سایت کدال',
                        'url' => $codalBaseUrl . '/' . $codalData['Url']
                    ]),
                    $codalData['HasPdf'] ? Keyboard::inlineButton([
                        'text' => 'دانلود pdf',
                        'url' => $pdfUrl,
                    ]) : null,
                    $codalData['HasAttachment'] ? Keyboard::inlineButton([
                        'text' => 'دانلود ضمیمه',
                        'url' => $attachmentUrl
                    ]) : null,
                    $codalData['HasExcel'] ? Keyboard::inlineButton([
                        'text' => 'دانلود excel',
                        'url' => $excelUrl
                    ]) : null
                ]);

            foreach ($this->channelIds as $chanelId) {
                $resSendMessage = $this->telegram->sendMessage([
                    'chat_id' => $chanelId,
                    'text' => $text,
//                    'reply_markup' => $replyMarkup
                ]);

                $response->isSucceed = true;
                $response->message = 'message successfully send';
                $response->datas = $resSendMessage->text;
            }
        } catch (\Exception $e) {
            $response->isSucceed = false;
            $response->message = $e->getMessage();
        }

        return $response->toJson();

        //TracingNo
        //Symbol
        //CompanyName
        //UnderSupervision
        //Title
        //LetterCode
        //HasHtml
        //SentDateTime
        //PublishDateTime
        //IsEstimate
        //Url //Reports/Decision.aspx?LetterSerial=HVcckBNldyxBJ4DPwJtIdg%3d%3d&rt=0&let=174&ct=0&ft=-1
        //HasExcel
        //HasPdf
        //HasXbrl
        //HasAttachment
        //AttachmentUrl //Reports/Attachment.aspx?LetterSerial=HVcckBNldyxBJ4DPwJtIdg%3d%3d
        //PdfUrl //DownloadFile.aspx?hs=HVcckBNldyxBJ4DPwJtIdg%3d%3d&ft=1005&let=174
        //ExcelUrl
        //XbrlUrl
        //TedanUrl
    }
}
