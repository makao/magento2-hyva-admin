<?php
/**
 * Csv
 * @copyright Copyright © 2021 CopeX GmbH. All rights reserved.
 * @author    andreas.pointner@copex.io
 */

namespace Hyva\Admin\Model\Export;

use Hyva\Admin\ViewModel\HyvaGrid\CellInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;

class Csv extends AbstractExport
{

    protected string $fileName = "export/export.csv";

    protected WriteInterface $directory;

    private Filesystem $filesystem;

    private SourceIteratorFactory $sourceIteratorFactory;


    public function __construct( Filesystem $filesystem, SourceIteratorFactory $sourceIteratorFactory)
    {
        $this->filesystem = $filesystem;
        $this->sourceIteratorFactory = $sourceIteratorFactory;
    }

    public function create()
    {
        $file = $this->getFileName();
        $this->directory = $this->filesystem->getDirectoryWrite($this->getRootDir());
        $stream = $this->directory->openFile($file, 'w+');
        $iterator = $this->sourceIteratorFactory->create(['grid' => $this->getGrid()]);
        $stream->lock();
        $addHeader = true;
        foreach($iterator as $row){
            if ($addHeader) {
                $stream->writeCsv($this->getHeaderData());
                $addHeader = false;
            }
            $stream->writeCsv(
                array_map(function (CellInterface $cell) {
                    return $cell->getTextValue();
                }, $row->getCells())
            );
        }
        $stream->unlock();
        $stream->close();
    }

}