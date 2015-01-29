<?php 

namespace Oktolab\IntakeBundle\Extension;

class FilesizeExtension extends \Twig_Extension
{
    /**
     * [@inheritDoc]
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('filesize', array($this, 'filesizeFilter'))
        );
    }

    public function filesizeFilter($filesizeInByte, $actualsize=true)
    {
        if ($actualsize) {
            if ($filesizeInByte >= 1000000000) {
                return ( round( ($filesizeInByte/1000000000), 2).' GB' );
            } else if ($filesizeInByte >= 1000000) {
                return ( round( ($filesizeInByte/1000000), 2).' MB' );
            } else if ($filesizeInByte >= 1000) {
                return ( round( ($filesizeInByte/1000), 2).' KB' );
            } else {
                return $filesizeInByte.' B';
            }
        } else {
            if ($filesizeInByte >= 1073741824) {
                return ( round( ($filesizeInByte/1073741824), 2).' GiB' );
            } else if ($filesizeInByte >= 1048576) {
                return ( round( ($filesizeInByte/1048576), 2).' MiB' );
            } else if ($filesizeInByte >= 1024) {
                return ( round( ($filesizeInByte/1024), 2).' KiB' );
            } else {
                return $filesizeInByte.' B';
            }
        }
    }

        public function getName()
    {
        return 'filesize';
    }
}