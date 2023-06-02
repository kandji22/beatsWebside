<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private $api_key = 'e7635815442f314ad612ed692d268165';
    private $api_key_secret = '053bf5076e14f1f2871366e0847893bc';

    public function send($to_email, $to_name, $subject, $content,$tabFiles = null)
    {
        $newArray = [];

        foreach ($tabFiles as $index => $attachment) {
            $newArray[] = [
                "Content-type" => $attachment["ContentType"],
                "Filename" => $attachment["Filename"],
                "content" => $attachment["Base64Content"],
            ];
        }

        $mj = new Client($this->api_key, $this->api_key_secret,true,['version' => 'v3.1']);
        $body =
            [
                'Messages' =>
                [
                    [
                        'From' =>
                        [
                            'Email' => "kandji.k@outlook.com",
                            'Name' => "webBeat"
                        ],
                        'To' =>
                            [
                                [
                                    'Email' => $to_email,
                                    'Name' => $to_name
                                ]
                            ],
                            'TemplateID' => 4037264,
                            'TemplateLanguage' => true,
                            'Subject' => $subject,
                            'Variables' =>
                                [
                                    'content' => $content,
                                ],
                        'InlinedAttachments' => $tabFiles
                        /*[
                            [
                                'ContentType' => "application/pdf",
                                'Filename' => 'test.pdf',
                                'ContentID' => "id1",
                                'Base64Content' => $tabFiles[0]['content']
                            ]

                        ]*/




                    ]
                ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();



    }
}