<?php

namespace GoldenLine\ProductBundle\Model;

use GoldenLine\ProductBundle\Model\om\BaseProduct;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Product extends BaseProduct
{
    /**
     * @var UploadedFile
     */
    private $file;

    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getAbsolutePath()
    {
        return $this->getUploadRootDir() . '/' . $this->photo;
    }

    public function getWebPath()
    {
        return $this->getUploadDir() . '/' . $this->photo;
    }

    protected function getUploadRootDir()
    {
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'uploads/photos';
    }

    /**
     * @inheritdoc
     */
    public function preSave(\PropelPDO $con = null)
    {
        if (null === $this->getFile()) {
            return true;
        }

        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->getFile()->getClientOriginalName()
        );

        $this->setPhoto($this->getFile()->getClientOriginalName());

        $this->file = null;

        return true;
    }
}
