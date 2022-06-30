<?php

namespace Tests\Unit;

use App\Models\File;
use App\Services\Parsers\SQLParser\SQLParseFile;
use Tests\TestCase;

class SQLParseFileTest extends TestCase
{
    protected $file;
    protected $file2;
    protected $sql_parser_file_obj;
    protected $sql_parser_file_obj2;

    protected function setUp(): void
    {
        parent::setUp();
        $this->file = File::query()->where('id', 2)->first();
        $this->sql_parser_file_obj = new SQLParseFile($this->file);
        $this->file2 = File::query()->where('id', 10)->first();
    }

    public function test_readFile()
    {
        $this->assertTrue($this->sql_parser_file_obj->readFile());
    }

    public function test_readFileException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Файл public/files_db/db7.sql пустой');
        $this->sql_parser_file_obj2 = new SQLParseFile($this->file2);
    }

    public function test_parse()
    {
        $this->sql_parser_file_obj->parse();
        $this->assertNotEmpty($this->sql_parser_file_obj->getInsertTables());
    }

    public function test_parseEmptyFile()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Файл public/files_db/db7.sql пустой');
        $this->sql_parser_file_obj2 = new SQLParseFile($this->file2);
        $this->assertEmpty($this->sql_parser_file_obj2->getInsertTables());
    }
}
