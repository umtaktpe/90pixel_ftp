<?php

namespace Tests\Unit;

use App\Classes\FTP;
use PHPUnit\Framework\TestCase;

class FTPTest extends TestCase
{
    /** @test */
    public function it_should_bring_the_last_file()
    {
        $fileNames = [
            'categories/kategoriler-20200127100560.xlsx',
            'categories/kategoriler-20200418192346.xlsx'
        ];

        $ftp = new FTP();

        $lastFile = $ftp->getLastFile($fileNames);
        $this->assertEquals('kategoriler-20200418192346.xlsx', $lastFile);
    }

    /** @test */
    public function it_should_return_false_if_filenames_incorrect()
    {
        $wrongFileNames = [
            'categories/kategoriler-20200127100560.txt',
            'categories/kategoriler-test.xlsx'
        ];

        $ftp = new FTP();

        $lastFile = $ftp->getLastFile($wrongFileNames);
        $this->assertEquals(false, $lastFile);
    }

    /** @test */
    public function it_should_check_suitability_of_time()
    {
        $date = "20200127100560";

        $ftp = new FTP();

        $date = $ftp->isDateValid($date);
        $this->assertEquals(true, $date);

        $wrong_date = "wrong_date";
        $wrong_date = $ftp->isDateValid($wrong_date);
        $this->assertEquals(false, $wrong_date);
    }

    /** @test */
    public function it_should_return_false_if_date_format_incorrect()
    {
        $ftp = new FTP();

        $wrong_date = "wrong_date";
        $wrong_date = $ftp->isDateValid($wrong_date);
        $this->assertEquals(false, $wrong_date);
    }
}
