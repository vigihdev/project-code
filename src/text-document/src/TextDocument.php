<?php

namespace textDocument;

use helpers\Collection;
use Spatie\Regex\Regex;

class TextDocument
{

    private $documents;

    private $filename;
    
    private $lineCount;

    public static function fromFile(string $filename)
    {
        return new self($filename);
    }

    public function __construct(string $filename)
    {
        $documents = file($filename);
        $this->documents = $documents;
        $this->filename = $filename;
        $this->getText();
    }

    public function getText(): string
    {
  
        $documents = Collection::from($this->documents)->map(function($text,$index){
            return htmlspecialchars((string)$text);
        })->all();

        $text = implode("\n",$documents);
        $match = Regex::matchAll('/namespace\s(?<namespace>[\w+\\\\]+)[^\r]+.*class\s(?<class>\w+\d+)/',$text)->results();
        
        if(isset($match[0])){
            // echo "<pre>";
            //     var_dump($match[0]->namedGroup('class'));
            //     var_dump($match[0]->namedGroup('namespace'));
            // echo "</pre>";
        }
        return implode("\n",$documents);
    }

    public function getLineCount(): int
    {
        return count($this->documents);
    }

    public function getPath(): array
    {
        return pathinfo($this->filename);
    }

}
