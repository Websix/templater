<?php

namespace Websix\Templater;

class TemplaterTest extends \PHPUnit_Framework_TestCase
{
    private $subject;

    private $data;

    private $template;

    public function setUp()
    {
        $this->data = [
            "glossary" => [
                "title" => "example glossary",
                "GlossDiv" => [
                    "title" => "S",
                    "GlossList" => [
                        "GlossEntry" => [
                            "ID" => "SGML",
                            "SortAs" => "SGML",
                            "GlossTerm" => "Standard Generalized Markup Language",
                            "Acronym" => "SGML",
                            "Abbrev" => "ISO 8879:1986",
                            "GlossDef" => [
                                "para" => "A meta-markup language, used to create markup languages such as DocBook.",
                                "GlossSeeAlso" => ["GML", "XML"]
                            ],
                            "GlossSee" => "markup"
                        ]
                    ]
                ]
            ]
        ];

        $this->subject = new Templater();
        $this->template = realpath(__DIR__ . '/./testTemplate.docx');
    }

    public function tearDown()
    {
        unset($this->data, $this->subject);
    }

    public function testCompile()
    {
        $res = $this->subject->compileJson($this->template, json_encode($this->data));
        file_put_contents('test.docx', $res);
    }
}