<?php


namespace App\Services\Exporters\XML;


use App\Services\Exporters\BasicExporter;
use App\Services\Exporters\IExport;
use DOMDocument;
use DOMAttr;
use Illuminate\Support\Facades\Storage;

class XMLExporter extends BasicExporter implements IExport
{
    const PATH = 'public/xml';

    public function __construct(array $data, $filename)
    {
        parent::__construct($data, $filename);
        $this->openFile();
    }

    public function export()
    {
        $root = $this->file->createElement('posts');
        foreach ($this->data as $key => $item)
        {
            $movie_node = $this->file->createElement('post');
            $attr_movie_id = new DOMAttr('post_id', $key);
            $movie_node->setAttributeNode($attr_movie_id);
            $child_node_title = $this->file->createElement('title', $item['post_title']);
            $description = $this->file->appendChild($this->file->createElement('content'));
            $description->appendChild($this->file->createCDATASection($item['post_content']));
            $movie_node->appendChild($child_node_title);
            $movie_node->appendChild($description);
            $root->appendChild($movie_node);
            $this->file->appendChild($root);
        }

        Storage::put(self::PATH . '/' .  $this->filename . '_' . date('Y_m_d') . '.xml', $this->file->saveXml());
        $this->link = self::PATH . '/' .  $this->filename . '_' . date('Y_m_d') . '.xml';
    }
    public function getLink()
    {
        return $this->link;
    }

    private function openFile()
    {
        $this->file = new DOMDocument('1.0');
        $this->file->encoding = 'utf-8';
        $this->file->formatOutput = true;
    }
}
